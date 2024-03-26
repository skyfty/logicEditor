<template>
  <div class="fbxs" ref="fbxs" v-loading="loading">
    <div id="threeContainers" style="display: block;background: transparent;"></div>
  </div>
</template>

<script>
import * as THREE from "three";
import { FBXLoader } from "three/examples/jsm/loaders/FBXLoader";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls";

export default {
  name: "VueThree",
  props:['previewFbxData'],
  data(){
    return {
      loading: true,
    }
  },
  watch:{
    previewFbxData(newValue){
      this.createModels(newValue) 
    }
  },
  mounted() {
    this.init(this.previewFbxData);
    this.animate();
  },
  methods: {
    init(previewFbxData) {
      this.createScene();
      this.createModels(previewFbxData);

      window.addEventListener("resize", this.onWindowResize);
    },
    createScene() {
      const container = document.getElementById("threeContainers");
      this.scene = new THREE.Scene();
      // this.scene.background = new THREE.Color(0x8cc7de);

      this.camera = new THREE.PerspectiveCamera(20, this.$refs.fbxs.clientWidth / this.$refs.fbxs.clientHeight, 0.1, 1000000);
      this.camera.position.set(0, 100, 400);
      this.camera.lookAt(this.scene.position);

      const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444);
      hemiLight.position.set(0, 1, 0);
      this.scene.add(hemiLight);

      const ambientLight = new THREE.AmbientLight(0xffffff, 0.25);
      this.scene.add(ambientLight);

      this.renderer = new THREE.WebGLRenderer({ alpha: true});
      this.renderer.setPixelRatio(window.devicePixelRatio);
      this.renderer.setClearColor(0xeeeeee, 0.5);
      this.renderer.setSize(this.$refs.fbxs.clientWidth, this.$refs.fbxs.clientHeight);
      container.appendChild(this.renderer.domElement);

      this.controls = new OrbitControls(this.camera, this.renderer.domElement);
      this.controls.enableRotate = true;
      this.controls.target.set(0, 0, 0);
      this.controls.update();
    },
    createModels(previewFbxData) {
      this.loading = true;
      this.loader = new FBXLoader();
      if( this.previewFbxData != ''){
        this.scene.children.splice(2)
        this.loader.load('/x'+previewFbxData, object => {
          object.traverse(child => {
            if (child.isMesh) {
              const textureLoader = new THREE.TextureLoader();
              const texture = textureLoader.load('/htp/fastAdmin/resource/chartlet.png');
              child.material.map = texture;
            }
          });
          object.scale.set(1, 1, 1);
          object.position.set(0, 0, 0);
          this.camera.lookAt(0, 45, 0);
          this.loading = false;
          this.scene.add(object);
        });
      }
    },
    onWindowResize() {
      this.camera.aspect = this.$refs.fbxs.clientWidth / this.$refs.fbxs.clientHeight;
      this.camera.updateProjectionMatrix();
      this.renderer.setSize(this.$refs.fbxs.clientWidth, this.$refs.fbxs.clientHeight);
    },
    animate() {
      requestAnimationFrame(this.animate);
      this.renderScene();
    },
    renderScene() {
      this.renderer.render(this.scene, this.camera);
    }
  }
};
</script>

<style lang="scss" scoped>
.fbxs {
  width: 100%;
  height: 200px;
  padding: 0;
  margin: 0;
  border: 1px solid red;
}
</style>