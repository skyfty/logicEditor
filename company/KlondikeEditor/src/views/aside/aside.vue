<template>
  <div class="nav"><br>
    <p v-if="introd[1].id ? introd[1].id:false">关卡组-ID：{{introd[2].id}}</p><br>
    <p v-if="introd[1].id ? introd[1].id:false">关卡包-ID：{{introd[0].id}}</p><br>
    <p v-if="introd[1].id ? introd[1].id:false">关卡-ID：{{introd[1].id}}</p><br>
  </div>
</template>

<script>
import $ from 'jquery'
import {EditData} from '../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd'],
  data() {
    return {

    };
  },

  watch: {
    
  },

  mounted() {
    
  },

  methods: {
    // 成功提示
    succe(name) {
      this.$message({
        type: 'success',
        message: name
      })
    },
    // 失败提示
    error(name) {
      this.$message({
        type: 'info',
        message: name
      })
    },
  },
};
</script>

<style lang="scss" scoped>
.nav{
  width: 100%;
  box-sizing: border-box;
  border-left: 1px solid #ccc;height: 100%;
  padding: 5px 10px;
  p{
    line-height: 40px;
    font-size: 17px;
    text-align: center;
    border-radius: 6px;
    font-weight: 600;
    background-image: linear-gradient(to top, #525252, #d9d9d9);
    opacity: 0.7;
  }
  form{
      padding: 10px 10px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      border-radius: 6px;
      background: #aba9a9;
      input{
        background: #aba9a9;
      }
    }
}
</style>