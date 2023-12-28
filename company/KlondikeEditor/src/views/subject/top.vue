<template>
  <div>
    <span>牌桌</span>&nbsp;&nbsp;
    <span v-if="introd != 0">{{'路径：'+ a[0].name + '  /  ' + a[1].name +' 关卡数：'+a[0].levels.length}}</span>
    <span>{{  }}</span>
    <!-- <router-link to="/a">点击</router-link> -->
    <div @click="lph()" style="float: right;width: 60px;cursor: pointer;">
      <span>全屏</span>&nbsp;
      <i class="layui-icon  layui-icon-screen-full" style="position: relative; top:1.8px;"></i>
    </div>
  </div>
</template>

<script>
import screenfull from 'screenfull'
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