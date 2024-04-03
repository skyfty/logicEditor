<template>
  <div class="app">
    <el-row type="flex" class="row-bg" justify="space-around">
      <el-col :span="3">
        <div class="grid-content bg-purple-dark">
          <menu1 :navigation="navigation" @updateData="updateData" @updateParts="updateParts" @introduction="introduction" ref="menu"></menu1>
        </div>
      </el-col>
      <el-col :span="16">
        <div class="grid-content bg-purple-light">
          <main1 v-if="loading" @updataMain="updataMain" ></main1>
          <h1 v-else style="text-align: center;padding-top: 25%;font-size: 30px;color: rgb(161, 245, 138);">开启你的游戏之旅吧！！！</h1>
        </div>
      </el-col>
      <el-col :span="5">
        <div class="grid-content bg-purple">
          <asides 
              :introd="data" :pokeData="pokeData" 
              @updateData="updateData" @introduction="introduction" @updateParts="updateParts" @updataPoke="updataPoke"  
              :navigation="navigation" :partsData="partsData"
              :screenWidth="screenWidth" :screenHeight="screenHeight" v-if="data != ''" ref="asides"></asides>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import $ from 'jquery';
import { getdata } from '../api/api-pro'
import screenfull from 'screenfull'
import menu1 from './menu/menu.vue'
import main1 from './main/main.vue'
import asides from './aside/aside.vue'
export default {
  data() {
    return {
      item:'',
      data:'',
      partsData:'',
      pokeData:'',
      screenWidth: 0,  //屏幕尺寸
      screenHeight: 0,  //屏幕尺寸
      navigation:"",
      loading: false,
    };
  },

  components:{
    menu1,main1,asides
  },

  computed:{
    
  }, 

  watch:{
    '$store.state.pokeData'(newValue, oldValue) {
      this.updataMain()
    },
    data(newValue,oldValue) {
      // console.log(newValue);
    },
    screenHeight(newValue){
      // console.log(newValue);
    },
    screenWidth(newValue){
      // console.log(newValue);
    }
  },

  mounted() {
    this.screenWidth = document.body.clientWidth
    this.screenHeight = document.body.clientHeight
    window.onresize = () => {   //屏幕尺寸变化就重新赋值
      return (() => {
        this.screenWidth = document.body.clientWidth
        this.screenHeight = document.body.clientHeight
      })()
    }
  },

  methods: {
    updataMain(){
      this.loading = false;
      setTimeout(() => {
        this.loading = true;
      }, 1); 
    },
    updateData(){
      this.$refs.menu.loading = true
      getdata()
        .then(res => {
          this.navigation = res.data
          this.$refs.menu.loading = false 
        })
        .catch(err => {
          console.log(err);
        })
    },
    // 路由传参
    introduction(index){
      this.data = index
    },
    updateParts(index){
      this.partsData = index
    },
    // 路由传参
    updataPoke(index){
      this.pokeData = index
    },
  },
};
</script>

<style lang="scss" scoped>
  .app{
    height: calc(100vh - 45px) !important;
    .el-row {
      margin-bottom: 20px;
      &:last-child {
        margin-bottom: 0;
      }
    }
    .el-col {
      border-radius: 4px;
    }
    .bg-purple-dark {
      background: #737474;
    }
    .bg-purple {
      width: 100%;
      background: rgba($color: #3c3c3c, $alpha: 0.5);
    }
    .bg-purple-light {
      width: 100%;
      &::-webkit-scrollbar {
        display: none; /* Chrome Safari */
      }
      background: rgba($color: #838383, $alpha: 0.5);
    }
    .grid-content {
      border-radius: 4px;
      height: calc(100vh - 45px) !important;
    }
  }
</style>