<template>
  <div class="nav">
    <div class="head">
      <div><el-button icon="el-icon-back" class="btn" @click="$router.push({path:'/'})"></el-button></div>
      <div><h1>工具栏</h1></div>
      <div>
      <img src="../../assets/favicon.jpg" width="35" alt=""></div>
    </div>
    <div></div>
    <div class="main" ref="main">
      <el-tabs type="border-card" @tab-click="tabClick" :active-name="tabPane[0].value">
        <el-tab-pane :label="item.label" :name="item.value" v-for="(item,index) in tabPane" :key="index">
          <parts v-if="tabIndex == 1"></parts>
          <imageDispose v-else-if="tabIndex == 0"></imageDispose>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import { getdata, } from '../../api/api-pro'
import parts from './data/parts.vue'
import imageDispose from './data/imageDispose.vue'
export default {
  name: 'KlondikeEditorIndex',
  components:{
    parts,imageDispose
  },
  data() {
    return {
      screenHeight: document.body.clientHeight,  //屏幕尺寸
      loading:false,
      tabPane:[
        {label:'图像', value:'图像'},
        {label:'零部件', value:'零部件'},
      ],
      tabIndex: 0
    };
  },
  computed: {

  },

  mounted() {
    window.onresize = () => {   //屏幕尺寸变化就重新赋值
      return (() => {
        this.screenHeight = document.body.clientHeight
      })()
    }
  },

  methods: {
    tabClick(targetName, action){
      this.tabIndex = targetName.index
    }
  },
};
</script>

<style lang="scss" scoped>
  .nav{
    width: 100%;
    height: 100vh;
    margin: 0;
    padding: 0;
    .head{
      height: 50px;
      background: rgba($color: #b1b1b1, $alpha: 0.5);
      margin: 0 0 20px 0;
      padding: 0 100px;
      box-shadow: 0px 3px 3px 2px #acabab;
      display: flex;
      justify-content: space-between;
      div{
        display: flex;
        align-items: center;
      }
      .btn{
        background: rgba(238, 238, 238, 0.1);
        font-size: 30px;border: 0;
      }
    }
    .main{
      height: calc(100% - 100px);
      margin: 0 100px;
      margin-bottom: 20px;
      overflow: auto;
      box-shadow: 0px 3px 3px 5px #bebebe;
    }
  }
</style>