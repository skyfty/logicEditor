<template>
  <div>
    <!-- 注册 -->
    <el-dialog title="来拥有一个属于自己的空间吧！"
        :close-on-click-modal=false
        :close-on-press-escape=false
        :show-close="false"
        width='500px'
        :center="true"
       :visible.sync="dialogs">
      <el-form :model="form1">
        <el-form-item label="用户名" :label-width="formLabelWidth">
          <el-input v-model="form1.name" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="密码" :label-width="formLabelWidth">
          <el-input v-model="form1.password" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="验证码" :label-width="formLabelWidth">
          <el-input v-model="form1.auth" auto-complete="off"></el-input>
        </el-form-item>      
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" round @click="$emit('change',a = false,b = true)">去 登 录</el-button>
        <el-button type="primary" round @click="auth()">注 册</el-button>
      </div>

    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'GameLogin',
  props:['dialogs','dialog'],
  data() {
    return {
      form1: {
          name: '',
          password: '',
          auth:''
        },
      a:this.dialogs,
      b:this.dialog,
      formLabelWidth: '120px'
    };
  },

  mounted() {
    console.log(this.a)
  },

  methods: {
      // 注册
      auth(){
        console.log(this.form1);
      },
  },
};
</script>

<style lang="scss" scoped>

</style>