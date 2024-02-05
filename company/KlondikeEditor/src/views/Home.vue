<template>
  <div class="home">
    <!-- 头部 -->
    <ul class="layui-nav">
      <li class="layui-nav-item" v-for="(item, index) in box" @click="onClickChildren(item,index)" :key="index" lay-unselect>
        <a href="javascript:;">
          <i class="layui-icon" :class="item.class"></i>
          <span>{{ item.name }}</span>
        </a>
      </li>
    </ul>
    <global-dialog :title="value" ref="globalDialog">
      <!-- 弹出框内容 -->
      <level_difficulty v-if="index == 1"></level_difficulty>
      <!-- <sylloge_index v-else-if="index == 2"></sylloge_index> -->
    </global-dialog>
    <navigation></navigation>
  </div>
</template>

<script>
import level_difficulty from '../components/difficulty/level_difficulty.vue'
import sylloge_index from '../components/sylloge/index.vue'
import navigation from './navigation.vue';
export default{
  data(){
    return {
      box:[
        {name:"admin",class:'layui-icon-github',tui:'退出'},
        {name:"难度",class:'layui-icon-slider'},
        {name:"总汇",class:'el-icon-s-data'},
      ],
      value:'',
      index:''
    }
  },
  components:{
    navigation,level_difficulty, sylloge_index
  },
  mounted() {

  },
  methods:{
    onClickChildren(data,index){
      switch(index){
        case 1:
            this.index = index
            this.value = data.name;
            this.$refs.globalDialog.dialogVisible = true;
          break;
        case 2:
            this.index = index
            this.value = data.name;
            this.$router.push({path: '/sylloge_indexa',query: {}})
            this.$refs.globalDialog.dialogVisible = true;
          break;
      }
    }
  }
}

</script>

<style scoped  lang="scss">
  .layui-nav .layui-nav-item{
    height: 45px;
    line-height: 45px;
  }
  .layui-nav{
    padding: 0 ;
    li{
      i{
        font-size: 20px;
        color: #1E9FFF;
        margin-right: 8px;
        position: relative;
        top: 3px;
      }
      .layui-nav-child{
        left: 30px;
        top:45px;
        a{
          &:hover{
            background-color: rgb(2, 181, 246);
          }
        }
        // .layui-this {
        //   background-color: red;
        // }
      }
    }
  }
</style>
