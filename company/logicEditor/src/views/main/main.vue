<template>
  <div class="main" ref="main">
    <div id="threeContainer" style="display: block;background: transparent;"></div>
    <div class="home">
      <el-button type="primary" v-if="introd.length == 5">{{ introd.length == 5 ? introd[4].name : '' }}</el-button>
    </div>
    <div class="shift">
      <el-button type="primary" size="mini" icon="el-icon-arrow-left" @click="rightShift('-')"></el-button>
      <el-button type="primary" size="mini" icon="el-icon-arrow-right" @click="rightShift('+')"></el-button>
    </div>
    <handlePlatform ref="handlePlatform" :introd="introd" :pokeData="pokeData" :navigation="navigation"  @cameraChange="cameraChange"
        @toggleWallVisibility="toggleWallVisibility" @toggleGridPlatformVisibility="toggleGridPlatformVisibility" ></handlePlatform>
  </div>
</template>

<script>
import * as THREE from "three";
import handlePlatform from './handle/handlePlatform.vue'
import { FBXLoader } from "three/examples/jsm/loaders/FBXLoader";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls";
export default {
  name: 'LogicEditorMain',
  props:['introd','partsData','pokeData', 'navigation','screenWidth','screenHeight'],
  data() {
    return {
      scene: null,
      camera: null,
      wall:null,
      renderer: null,
      controls: null,
      loader: null,
      clock: null,
      rotationEnabled: false, 
      raycaster: null,
      mouse: null,
      interactiveTriggers: [], 
      loading: true,
      shift:0,
      visible: false,
      isDragging:false,
      gridPlatformVisible:false,
      gridPlatform:null,
      orthographicCamera:null,
      perspectiveCamera:null,
      events: { raycaster: new THREE.Raycaster(), pickedObject: null, pickedObjectSavedColor: 0, pickPosition: { x: 0, y: 0 } }
    };
  },

  components:{
    handlePlatform
  },

  watch:{
    '$store.state.pokeData'(newValue, oldValue){
      this.partsDatas(newValue)
    },
    '$store.state.partsData'(newValue, oldValue){
      this.activate(newValue)
    },
    screenHeight(newValue){
      // console.log(newValue);
    },
    screenWidth(newValue){
      // console.log(newValue);
    }
  }, 

  methods: {
    init() {
      this.createScene();
      this.createModels();
      // 网格平台
      this.gridPlatforms()
      // 坐标系
      this.createLeftHandStyleAxes();
      // 鼠标事件 
      this.mouseEvent() 

      window.addEventListener("resize", this.onWindowResize);
    },

    createScene() {
      const container = document.getElementById("threeContainer");
      this.scene = new THREE.Scene();
      // 创建正交相机 
      this.createPerspectiveCamera()
      
      // 创建一个平面几何体，用于表示一堵墙
      this.MeshStandardMaterial()

      const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444);
      hemiLight.position.set(0, 1, 0);
      this.scene.add(hemiLight);

      const ambientLight = new THREE.AmbientLight(0xffffff, 0.25);
      this.scene.add(ambientLight);

      this.renderer = new THREE.WebGLRenderer({ alpha: true});
      this.renderer.setPixelRatio(window.devicePixelRatio);
      this.renderer.setClearColor(0xeeeeee, 0.5);
      this.renderer.setSize(this.$refs.main.clientWidth, this.$refs.main.clientHeight);
      container.appendChild(this.renderer.domElement);

      // 创建射线投射器
      this.raycaster = new THREE.Raycaster();
      this.mouse = new THREE.Vector2();
    },
    
    createPerspectiveCamera(){
      // 创建正交相机和透视相机实例  
      this.orthographicCamera = new THREE.OrthographicCamera(  
        window.innerWidth / -2, window.innerWidth / 2,  
        window.innerHeight / 2, window.innerHeight / -2,  
        this.introd[3].Near-0, this.introd[3].Far-0 
      );
      // 创建透视相机  
      this.perspectiveCamera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, .1, 2000);  
        
      this.camera = this.perspectiveCamera
    },

    cameraChange(newValue){
      // 根据表单的Projection值更新相机 
      if (newValue[1][0].Projection === 'Orthographic') {  
        this.camera = this.orthographicCamera; 
        this.orthographicCamera.zoom = newValue[1][3].Zoom; 
        this.orthographicCamera.position.set( newValue[0][0].x, newValue[0][0].y ,newValue[0][0].z )
        this.orthographicCamera.rotation.set( newValue[0][1].x * ( Math.PI / 180 ), newValue[0][1].y * ( Math.PI / 180 ) ,newValue[0][1].z * ( Math.PI / 180 ) )
        this.orthographicCamera.near = newValue[1][4].Near - 0;
        this.orthographicCamera.far = newValue[1][4].Far - 0;
        this.orthographicCamera.left = -window.innerWidth / (2 * newValue[1][3].Zoom);
        this.orthographicCamera.right = window.innerWidth / (2 * newValue[1][3].Zoom);
        this.orthographicCamera.top = window.innerWidth / (2 * newValue[1][3].Zoom);
        this.orthographicCamera.bottom = -window.innerWidth / (2 * newValue[1][3].Zoom); 
        this.orthographicCamera.updateProjectionMatrix(); 
      } else {
        this.camera = this.perspectiveCamera 
        this.perspectiveCamera.fov = newValue[1][2].FieldOfView - 0
        this.perspectiveCamera.near = newValue[1][4].Near - 0;
        this.perspectiveCamera.far = newValue[1][4].Far - 0;
        // this.perspectiveCamera.zoom = newValue[1][3].Zoom; 
        this.perspectiveCamera.position.set( newValue[0][0].x, newValue[0][0].y ,newValue[0][0].z );
        this.perspectiveCamera.rotation.set( newValue[0][1].x * ( Math.PI / 180 ), newValue[0][1].y * ( Math.PI / 180 ) ,newValue[0][1].z * ( Math.PI / 180 ) )
        this.perspectiveCamera.lookAt(this.scene.position);
        this.perspectiveCamera.updateProjectionMatrix(); 
      }
      this.camera.scale.set( newValue[0][2].x, newValue[0][2].y ,newValue[0][2].z );
    },

    // 网格平台
    gridPlatforms(){
      // 网格平台的尺寸  
      const gridSize = 200; // 网格大小  
      const gridStep = 20;  // 网格步长  
      // 创建网格的几何体  
      const gridGeometry = new THREE.PlaneGeometry(gridSize, gridSize, gridStep, gridStep);  
      const gridMaterial = new THREE.MeshBasicMaterial({  
      color: 0x4B4746, // 网格颜色  
      wireframe: true, // 是否显示为线框  
      transparent: true,
      opacity: 0.5 // 网格的不透明度  
      });  
      // 创建网格平台  
      this.gridPlatform = new THREE.Mesh(gridGeometry, gridMaterial);  
      this.gridPlatform.rotation.x = -Math.PI / 2; // 旋转网格，使其平放在X-Z平面上  
      this.gridPlatform.position.y = 0; // 设置网格平台的高度  
      this.gridPlatform.receiveShadow = true; // 设置网格平台可以接收阴影
      this.gridPlatform.visible  = this.gridPlatformVisible; // 设置网格平台可以接收阴影
      this.scene.add(this.gridPlatform); // 将网格平台添加到场景中
    },

    // 空气墙
    MeshStandardMaterial(){
      const wallGeometry = new THREE.PlaneGeometry(200, 100);
      const wallMaterial = new THREE.MeshStandardMaterial({ color: 0xaaaaaa, transparent: true, opacity: 0.5 });
      this.wall = new THREE.Mesh(wallGeometry, wallMaterial);
      this.wall.position.set(0, 0, 0);
      this.wall.receiveShadow = true;
      this.wall.visible = this.visible; // 设置墙体不可见
      this.scene.add(this.wall);
    },

    // 坐标轴
    createLeftHandStyleAxes(size, segments) { 
      const arrowLength = 100; // 坐标轴箭头的长度
      // X轴箭头（红色），指向右  
      const xAxisArrow = new THREE.ArrowHelper(new THREE.Vector3(1, 0, 0), new THREE.Vector3(0, 0, 0), arrowLength, 0xff0000);  
      this.scene.add(xAxisArrow);  
        
      // Y轴箭头（绿色），指向上  
      const yAxisArrow = new THREE.ArrowHelper(new THREE.Vector3(0, 1, 0), new THREE.Vector3(0, 0, 0), arrowLength, 0x00ff00);  
      this.scene.add(yAxisArrow);  
        
      // Z轴箭头（蓝色），指向屏幕内（左手坐标系）  
      const zAxisArrow = new THREE.ArrowHelper(new THREE.Vector3(0, 0, -1), new THREE.Vector3(0, 0, 0), arrowLength, 0x0000ff);  
      this.scene.add(zAxisArrow);
    },

    createModels() {
      this.loader = new FBXLoader();
    },

    partsDatas(newValue){  
      if(newValue.length == 4){
        this.interactiveTriggers = []
        this.scene.children.splice(7)
        if( newValue[3].parts ){
          newValue[3].parts.forEach(element => {
            this.loader.load('/x'+element.picture, object => {
              object.traverse(child => {
                if (child.isMesh) {
                  const textureLoader = new THREE.TextureLoader();
                  const texture = textureLoader.load('/x'+element.chartlet); // 使用贴图路径
                  child.material.map = texture; 
                }
              }); 
              object.rotation.set(element.rotationX * (Math.PI / 180), element.rotationY * (Math.PI / 180), element.rotationZ * (Math.PI / 180)); // 初始旋转角度为0
              object.position.set(element.x, element.y, -1 * element.z);
              this.scene.add(object);
              object.userData = element;
              this.interactiveTriggers.push(object);
            });
            this.loading = false;
          }); 
        }else{
          this.error('还没有创建');
        }
      }else{
        this.activate(newValue)
      }
    },
    data(newValue){
      this.interactiveTriggers = []
      this.scene.children.splice(7)
      if( newValue[3].parts ){
        newValue[3].parts.forEach((element,index) => {
        this.loader.load('/x'+element.picture, object => {
          object.traverse(child => {
            if (child.isMesh) {
              const textureLoader = new THREE.TextureLoader();
              const texture = textureLoader.load('/x'+element.chartlet); // 使用贴图路径
              child.material.map = texture; 
            }
          });
          object.rotation.set(element.rotationX * (Math.PI / 180), element.rotationY * (Math.PI / 180), element.rotationZ * (Math.PI / 180)); // 初始旋转角度为0
          object.position.set(element.x, element.y, -1 * element.z);
          this.scene.add(object);
          object.userData = element;
          this.interactiveTriggers.push(object);
        });
        this.loading = false;
        if(index == newValue[3].parts.length - 1){
          this.activate(newValue)
        }
        });
      }else{
        this.error('还没有创建');
      }
    },
    mouseEvent(){
      // const raycaster = new THREE.Raycaster();  
      // const mouse = new THREE.Vector2();
      // // 添加mousedown事件监听器到DOM元素（通常是renderer的domElement）  
      // this.renderer.domElement.addEventListener('mousedown', (event) => {  
      //   // 将浏览器坐标转换为NDC坐标（标准化设备坐标，-1到+1之间）  
      //   // mouse.x = (event.clientX / window.innerWidth) * 2 - 1;  
      //   // mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;  
        
      //   const rect = this.renderer.domElement.getBoundingClientRect();
      //   this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
      //   this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
      //   // 通过相机和鼠标位置更新射线投射器  
      //   this.raycaster.setFromCamera(this.mouse, this.camera);  
        
      //   // 计算射线与场景中对象的交点  
      //   const intersects = this.raycaster.intersectObjects(this.scene.children, true); // true表示也检查子对象  
        
      //   // 如果射线与任何对象相交  
      //   if (intersects.length > 0) {  
      //     // intersects[0].object 是你点击到的第一个对象  
      //     console.log('你点击到了:', intersects[0].object, intersects[0].object.id);  
      //     // 你可以在这里添加你想要的逻辑，比如选中模型、改变颜色等  
      //   } else {  
      //     // 没有点击到任何模型  
      //     console.log('没有点击到任何模型');  
      //   }  
      // });
    },

    activate(newValue){
      let interactiveTriggers = this.interactiveTriggers.find((e) => e.userData.id === newValue[4].id)
      if(interactiveTriggers){
        this.interactiveTriggers.forEach((object) => {
          const material = object.children[0].material;
          material.color.set(material.defaultColor); // 恢复初始颜色
        });
        const selectedMaterial = interactiveTriggers.children[0].material;
        selectedMaterial.defaultColor = selectedMaterial.color.clone(); // 保存初始颜色
        selectedMaterial.color.set(0x00FCE6); // 设置选中的颜色 
        interactiveTriggers.position.set(newValue[4].x, newValue[4].y, -1 * newValue[4].z);
        interactiveTriggers.rotation.set(newValue[4].rotationX * (Math.PI / 180), newValue[4].rotationY * (Math.PI / 180), newValue[4].rotationZ * (Math.PI / 180));
      }
    }, 
    // 成功提示
    succe(name) {
      this.$notify.success({
          title: name,
          type: 'success',duration:1000,
          customClass:'notify-success'
        });
    },
    // 失败提示
    error(name) {
      this.$notify.error({
          title: name,
          type: 'error',
          customClass:'notify-error'
        });
    },

    toggleWallVisibility(data){
      this.visible = data;
      if (this.wall) {
        this.wall.visible = this.visible;
      }
    },

    toggleGridPlatformVisibility(data){
      this.gridPlatformVisible = data;
      if (this.gridPlatform) {
        this.gridPlatform.visible = this.gridPlatformVisible;
      }
    },

    rightShift(data){
        data == '-' ? this.shift -= 50 : this.shift += 50
        this.camera.position.x = this.shift
        // this.controls.target.x = this.shift
    },

    onWindowResize() {
      this.camera.aspect = this.$refs.main.clientWidth / this.$refs.main.clientHeight;
      this.camera.updateProjectionMatrix();
      this.renderer.setSize(this.$refs.main.clientWidth, this.$refs.main.clientHeight);
    },

    animate() {
      requestAnimationFrame(this.animate);
      this.renderScene();
    },

    renderScene() {
      this.renderer.render(this.scene, this.camera);
    },

  },

  mounted() {
    this.init();
    this.clock = new THREE.Clock();
    this.animate(); 
    this.partsDatas(this.introd)
  }
};
</script>

<style lang="scss" scoped>
.main{
  width: 100% !important;
  height: 100%;
  padding: 0;
  margin: 0;
  position: relative;
  background: #8cc7de;
  .home,.shift{
    padding: 10px;
    position: absolute;
  }
  .home{
    top: 0;
    left: 0;
    &>button{
      padding: 10px;
      margin-bottom:15px !important;
      opacity: 0.8;
      width: 90px;
    }
  }
  .shift{
    top: 50% !important;
    display: flex;
    justify-content: space-between;
    width: 100%;
    box-sizing: border-box;
    height: 60px;
    &>button{
        border-radius: 10px;
        background: rgba($color: #e5e5e5, $alpha: 0.2);
      &:hover{
        background: rgba($color: #e5e5e5, $alpha: 0.3);
      }
    }
  }
}
</style>