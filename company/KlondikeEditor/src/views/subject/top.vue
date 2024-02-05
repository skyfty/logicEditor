<template>
  <div>
    <span v-if="introd != 0" style="font-size: 15px;"> 
      <span style="color: rgb(62, 62, 62);font-weight: 600;">关卡组：</span>{{ a[2].name }}&nbsp;&nbsp;&nbsp;
      <span style="color: rgb(62, 62, 62);font-weight: 600;">关卡包：</span>{{ a[0].name }}&nbsp;&nbsp;&nbsp;
      <span style="color: rgb(62, 62, 62);font-weight: 600;">关卡：</span>{{ a[1].name }}&nbsp;&nbsp;&nbsp;
      <span style="color: rgb(62, 62, 62);font-weight: 600;">关卡数：</span>{{ a[0].levels.length }}&nbsp;&nbsp;&nbsp;
      <span style="color: rgb(62, 62, 62);font-weight: 600;">关卡难度：</span>{{ a[1].difficultyLevel +'('+$store.state.data.filter(e => e.id == this.introd[1].difficultyLevel)[0].difficulty+')' }}
    </span>
    <!-- <router-link to="/a">点击</router-link> -->
    <div @click="lph()" style="float: right;width: 60px;cursor: pointer;">
      <span>全屏</span>&nbsp;
      <i class="layui-icon  layui-icon-screen-full" style="position: relative; top:1.8px;"></i>
    </div>
  </div>
</template>

<script>
import screenfull from 'screenfull'
import $ from 'jquery'
export default{
  name: 'GameTop',
  props:['lph', "introd"],
  data() {
    return {
      a:this.introd
    };
  },
  components:{
    screenfull
  },

  mounted() {
    
  },

  methods: {
    
  },
};
</script>

<style lang="scss" scoped>

</style>