<template>
  <div>
    <!-- 登录 -->
    <el-dialog title="欢迎登录"
        :close-on-click-modal=false
        :close-on-press-escape=false
        :show-close="false"
        width='500px'
        :center="true"
       :visible.sync="dialog">
      <el-form :model="form" action="./views/popups/register.php" method='post'>
        <el-form-item label="用户名" :label-width="formLabelWidth">
          <el-input v-model="form.name" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="密码" :label-width="formLabelWidth">
          <el-input v-model="form.password" auto-complete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" round @click="$emit('change1',a = true,b = false)"> 去 注 册</el-button>
        <el-button type="primary" round @click="box()">登 录</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'GameRegister',
  props:['dialogs','dialog'],
  data() {
    return {
      form: {
          name: '',
          password: ''
        },
        a:this.dialogs,
        b:this.dialog,
        formLabelWidth: '120px'
    };
  },

  mounted() {
    
  },

  methods: {
      // 登录
      box(){
        if(this.form.name != ''){
          if(this.form.password != ''){
            this.$emit('change1',this.a = false,this.b = false)
          }else{
            alert('密码不能为空')
          }
        }else{
          alert('用户名不能为空')
        }
      },
  },
};
</script>

<style lang="scss" scoped>

</style>