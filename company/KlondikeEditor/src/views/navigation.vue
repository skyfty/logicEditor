<template>
  <div class="app">
    <el-container>
      <!-- 左侧导航栏 -->
      <menu1 @introduction="introduction" ref="menu"></menu1>
      <!-- 中部内容 -->
      <el-container>
        <el-header>
          <head1 :lph="toggleScreen" :key="item" :introd="introd[1].game != null ? introd : '0'"></head1>
        </el-header>
        <el-main :style="{backgroundColor:introd[1].game == null ? 'white' : 'rgb(1, 136, 1)'}">
          <div ref="bo" style="width: 100%;box-sizing: border-box;height: 100%;">
            <main1 :key="item" :introd="introd" v-if="introd[1].game != null"></main1>
            <h1 v-else style="text-align: center;padding-top: 20%;font-size: 30px;color: rgb(255, 0, 0);">开启你的游戏之旅吧！！！</h1>
          </div></el-main>
        <el-footer>Footer</el-footer>
      </el-container>
      <!-- 右侧导航栏 -->
      <el-aside width="250px">
        <asides :introd="introd" @update="update" ref="asides"></asides>
      </el-aside>
    </el-container>
  </div>
</template>



<script>
import $ from 'jquery';
import { getdata } from '../api/api-pro'
import screenfull from 'screenfull'
import menu1 from './menu/menu.vue'
import head1 from './subject/top.vue'
import main1 from './main/main.vue'
import asides from './aside/aside.vue'
export default {
  data() {
    return {
      introd:['0',{game:null}],
      // 刷新牌局
      item:''
    };
  },

  components:{
    head1,menu1,main1,asides
  },  

  mounted() {
  },

  methods: {
    // 全屏
    toggleScreen () {
      screenfull.toggle(this.$refs.bo);
    },
    // 路由传参
    introduction(index){
      if(index != 0){
        if(index[1].game != null){
          this.introd = index
          this.$refs.asides.ruleFormShow = true
          this.item = new Date().getTime() 
        }else{
          this.introd = index
          this.$message({
            message: '你还没有创建关卡呦，请先创建关卡',
            type: 'info',
            duration: 1000
          });
        }
      }
    },
    update(id,ids){
      getdata()
        .then(res => {
          let item = res.data.filter(d => d.id == id)[0]
          let items = item.levels.filter(d => d.id == ids)[0]
          this.introd = [item, items]
          // this.$refs.menu.klondikeList(item, items)
          this.$refs.menu.getData()
      })
    }
  },
};
</script>

<style lang="scss" scoped>
    .app{
      height: calc(100vh - 45px) !important;
    }
      .el-container{
        height: 100% !important;
      }
  .el-header, .el-footer {
    background-color: #B3C0D1;
    color: #333;
    height: 48px !important;
    line-height: 48px !important;
    padding:0 10px;
    font-size: 15px;
  }
  
  .el-main {
    background-color: #ffffff;
    color: #333;
    padding: 0;
  }
</style>