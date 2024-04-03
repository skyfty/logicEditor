<template>
  <div class="main" ref="main">
    <!-- 存放three.js的渲染效果 -->
    <div class="container" ref="container" v-loading="loading"
    element-loading-text="加载数据中..."
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"></div>
    <handlePlatform ref="handlePlatform"  @cameraChange="cameraChange" @toggleWallVisibility="toggleWallVisibility" @toggleGridPlatformVisibility="toggleGridPlatformVisibility" ></handlePlatform>
  </div>
</template>

<script>
import { EditData } from '../../api/api-pro'
import * as THREE from 'three'
import handlePlatform from './handle/handlePlatform.vue'
import { FBXLoader } from "three/examples/jsm/loaders/FBXLoader";
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { DragControls } from 'three/examples/jsm/controls/DragControls.js'
import { TransformControls } from 'three/examples/jsm/controls/TransformControls'
import { CSS3DRenderer } from 'three/examples/jsm/renderers/CSS3DRenderer'

export default {
  name: 'model',
  data() {
    return {
      dragObj: [],
      loading: true,
      scene: null,
      renderer: null,
      cssRenderer: null,
      control: null,
      camera: null,
      dControls: null,
      transformControls: null,
      visible: false,
      gridPlatformVisible: false,
      wall:null,
      visible:true,
      gridPlatformVisible:true,
      orthographicCamera:null,
      perspectiveCamera:null,
    }
  },

  watch: {
    '$store.state.pokeData'(newValue, oldValue) {
      this.loading = true;
    },
    '$store.state.partsData'(newValue, oldValue){
      this.activate(newValue)
    },
  },

  components: {
    handlePlatform
  },

  mounted() {
    this.init()
    this.render() 
  },

  methods: {
    init() {
      this.dom = this.$refs['container']

      this.scene = new THREE.Scene()
      this.renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        logarithmicDepthBuffer: true
      })
      this.cssRenderer = new CSS3DRenderer()

      const axes = new THREE.AxesHelper(500)
      const directLight = new THREE.AmbientLight(0xffffff, 2)
      this.scene.add(axes, directLight)

      const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
      directionalLight.position.set(0, 1, 0); // 设置光源位置
      this.scene.add(directionalLight);

      const pointLight = new THREE.PointLight(0xffffff, 1, 100);
      pointLight.position.set(0, 1, 0); // 设置光源位置
      this.scene.add(pointLight);

      this.configureCamera()

      this.renderer.setSize(this.dom.offsetWidth, this.dom.offsetHeight)
      this.dom.appendChild(this.renderer.domElement)

      this.cssRenderer.setSize(this.dom.offsetWidth, this.dom.offsetHeight)
      this.cssRenderer.domElement.style.position = 'absolute'
      this.cssRenderer.domElement.style.top = 0
      this.dom.appendChild(this.cssRenderer.domElement)
 
      // 网格平台
      this.gridPlatforms()
      // 创建一个平面几何体，用于表示一堵墙
      this.MeshStandardMaterial()

      // 加载数据
      this.loader = new FBXLoader()
      this.loader.setCrossOrigin(''); // 允许跨域加载资源  
      this.pokeData(this.$store.state.pokeData)
      // 拖拽
      this.transformControlsShow()
    },

    configureCamera(){
      let pokeData = this.$store.state.pokeData[3]
      const aspectRatio = this.dom.offsetWidth / this.dom.offsetHeight;
      this.orthographicCamera = new THREE.OrthographicCamera(
        -(pokeData.Size - 0) * aspectRatio, (pokeData.Size - 0) * aspectRatio,  
        (pokeData.Size - 0), -(pokeData.Size - 0),  
        pokeData.Near-0, pokeData.Far-0 
      )
      this.orthographicCamera.zoom = pokeData.zoom; 
      // 创建透视相机  
      this.perspectiveCamera = new THREE.PerspectiveCamera(pokeData.FieldOfView, aspectRatio, pokeData.Near-0, pokeData.Far-0 );
      this.camera = pokeData.Projection === 'OrthographicCamera' ? this.orthographicCamera : this.perspectiveCamera
      this.camera.position.set( pokeData.PositionX - 0, pokeData.PositionY - 0 ,pokeData.PositionZ - 0 )
      this.camera.rotation.set( pokeData.RotationX * ( Math.PI / 180 ), pokeData.RotationY * ( Math.PI / 180 ) ,pokeData.RotationZ * ( Math.PI / 180 ) )
      this.camera.scale.set( pokeData.ScaleX, pokeData.ScaleY ,pokeData.ScaleZ );
    },
    
    updateCameraConfiguration(newValue){
      if(this.camera.type != newValue[1][0].Projection){
        this.$store.dispatch('updataPoke') 
          .then(data => { 
          })  
          .catch(error => {
            console.error(error);  
          });
      }else{
        const aspectRatio = this.$refs.main.clientWidth / this.$refs.main.clientHeight;
      if (newValue[1][0].Projection === 'OrthographicCamera' ) {  
        this.orthographicCamera.zoom = newValue[1][3].Zoom; 
        this.orthographicCamera.near = newValue[1][4].Near - 0;
        this.orthographicCamera.far = newValue[1][4].Far - 0;
        this.orthographicCamera.left = -(newValue[1][1].Size - 0) * aspectRatio;
        this.orthographicCamera.right = (newValue[1][1].Size - 0) * aspectRatio;
        this.orthographicCamera.top = (newValue[1][1].Size - 0);
        this.orthographicCamera.bottom = -(newValue[1][1].Size - 0);
        this.orthographicCamera.updateProjectionMatrix();  
      } else {
        this.perspectiveCamera.fov = newValue[1][2].FieldOfView - 0
        this.perspectiveCamera.near = newValue[1][4].Near - 0;
        this.perspectiveCamera.far = newValue[1][4].Far - 0; 
        this.perspectiveCamera.lookAt( newValue[0][1].x, newValue[0][1].y, newValue[0][1].z );
        this.perspectiveCamera.updateProjectionMatrix();  
      }
        this.camera.scale.set( newValue[0][2].x, newValue[0][2].y ,newValue[0][2].z );
        this.camera.position.set( newValue[0][0].x, newValue[0][0].y ,newValue[0][0].z );
        this.camera.rotation.set( newValue[0][1].x * ( Math.PI / 180 ), newValue[0][1].y * ( Math.PI / 180 ) ,newValue[0][1].z * ( Math.PI / 180 ) )

      } 
      this.loading = false;
    },

    transformControlsShow() {
      this.control = new OrbitControls(this.camera, this.cssRenderer.domElement)

      this.transformControls = new TransformControls(this.camera, this.cssRenderer.domElement)
      this.scene.add(this.transformControls)

      this.dControls = new DragControls(this.dragObj, this.camera, this.cssRenderer.domElement)
      this.updateTransformControlsMode()
    },

    pokeData(newValues) {
      let newValue = newValues[3].parts
      newValue.forEach(element => {
        this.loader.load('/x' + element.picture, (loadedGroup) => {
          const meshes = loadedGroup.children.filter(child => child.isMesh);  
          if (meshes.length === 1) {  
            const mesh = meshes[0]; 
            
            // const textureLoader = new THREE.TextureLoader();
            // const texture = textureLoader.load('/htp/fastAdmin/resource/chartlet.png');
            // console.log(texture);
            // const material = new THREE.MeshPhongMaterial({ color: 0xffffff, map: texture });
            // mesh.material = material;

            // 设置旋转和位置  
            mesh.rotation.set(element.rotationX * (Math.PI / 180), element.rotationY * (Math.PI / 180), element.rotationZ * (Math.PI / 180));  
            mesh.position.set(element.x - 0, element.y - 0, element.z - 0); 
            mesh.scale.set(element.scaleX, element.scaleY, element.scaleZ); 
            // mesh.scale.set(0.005, 0.005, 0.005);
            this.scene.add(mesh);  
            mesh.userData = element;  
            this.dragObj.push(mesh);
          } else { 
            console.warn('Loaded more than one mesh, handling them might be required.');  
          }  
        });
      })
      this.loading = false;
    },

    activate(newValue){
      let dragObj = this.dragObj.find((e) => e.userData.id === newValue[4].id)
      if(dragObj){
        this.dragObj.forEach((object) => {
          const material = object.material;
          material.color.set(material.defaultColor); // 恢复初始颜色
        });
        const selectedMaterial = dragObj.material;
        selectedMaterial.defaultColor = selectedMaterial.color.clone(); // 保存初始颜色
        selectedMaterial.color.set(0x00FCE6); // 设置选中的颜色 
        dragObj.scale.set(newValue[4].scaleX - 0, newValue[4].scaleY - 0, newValue[4].scaleZ);
        dragObj.position.set(newValue[4].x - 0, newValue[4].y - 0, newValue[4].z);
        dragObj.rotation.set(newValue[4].rotationX * (Math.PI / 180), newValue[4].rotationY * (Math.PI / 180), newValue[4].rotationZ * (Math.PI / 180));
      }
    }, 
    updateTransformControlsMode(mode){        
      this.dControls.addEventListener('hoveron', (event) => {
        this.$store.dispatch('partsActive', event.object.userData.id)
        this.transformControls.setSize(0.4);// 设置三维坐标轴大小
        this.transformControls.attach(event.object)
        if(mode){
          this.transformControls.setMode(mode[2][2].translate);//缩放
        }
      })

      this.dControls.addEventListener('dragstart', () => {
        this.control.enabled = false
      })

      this.dControls.addEventListener('dragend', (event) => {
        let a = this.dragObj.filter(res => res.id == event.object.id)[0]
        a.position = event.object.position
        a.rotation = event.object.rotation 
        a.scale = event.object.scale

        EditData({children: 'editPokeparts',id: a.userData.id,data: JSON.stringify([a.position, a.rotation, a.scale])})
        .then(res => {
          this.succe(res.message);
          this.$store.dispatch('fetchData')
                    .then(data => {
                      this.$store.dispatch('updata') 
                    })  
                    .catch(error => {  
                      // 处理错误  
                      console.error(error);  
                    });
            })  
          .catch(error => {
            this.error(error.error);  
          });

        this.control.enabled = true
      })
      
      this.dControls.addEventListener('hoveroff', () => {
        // this.control.enabled = false
        this.transformControls.detach()  
      })
    },

    cameraChange(newValue){
      this.updateTransformControlsMode(newValue)
      this.updateCameraConfiguration(newValue)
    },

    // 网格平台
    gridPlatforms(){
      // 网格平台的尺寸  
      const gridSize = 1000; // 网格大小  
      const gridStep = 10;  // 网格步长  
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
      const wallGeometry = new THREE.PlaneGeometry(2000, 1000);
      const wallMaterial = new THREE.MeshStandardMaterial({ color: 0xaaaaaa, transparent: true, opacity: 0.5 });
      this.wall = new THREE.Mesh(wallGeometry, wallMaterial);
      this.wall.position.set(0, 0, 0);
      this.wall.receiveShadow = true;
      this.wall.visible = this.visible; // 设置墙体不可见
      this.scene.add(this.wall);
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

    render() {
      requestAnimationFrame(this.render.bind(this))
      this.control.update()
      this.cssRenderer.render(this.scene, this.camera)
      this.renderer.render(this.scene, this.camera)
    },

    // 成功提示
    succe(name) {
      // layer.msg(name, {icon: 1})
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
          type: 'error',duration:1000,
          customClass:'notify-error'
        });
    },
  }
}
</script>
 
<style lang="scss" scoped>
.main{
  width: 100% !important;
  height: 100%;
  padding: 0;
  margin: 0;
  position: relative; 
  background: rgb(171, 254, 254);
    .container {
        width: 100%;
        height: 100%;
        overflow: hidden; 
        display: inline-block;
    } 
}
</style>