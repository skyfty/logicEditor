<template>
  <div class="nav">
    <p v-if="introd[1].id ? introd[1].id:false">当前Id：{{introd[1].id}}</p>
    <el-form :inline="true" :rules="rules" 
        v-if="ruleFormShow == true"
        label-position="left" ref="ruleForm" :model="ruleForm" size="small" label-width="65px" 
        style="position: absolute;top: 13%;">
      <el-form-item label="难度值" prop="region">
        <el-select style="width: 90px !important;" v-model="ruleForm.region">
          <el-option :label="item.name" :value="item.name" v-for="(item,index) in data" :key="index"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" style="margin: 5px 0 0 25px !important;height: 35px;" @click="submitForm('ruleForm')">修改</el-button>
        <el-button type="primary" style="margin: 5px 0 0 20px !important;height: 35px;" @click="resetForm('ruleForm')">关闭</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {EditData} from '../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd'],
  data() {
    return {
      data:'',
      ruleForm: {
          region: '',
        },
      ruleFormShow:false,
      rules: {
        region: [
          { required: true, message: '选择难度系数', trigger: 'change' }
        ],
      }
    };
  },

  watch: {
    introd(newValue) {
      this.ruleForm.region = newValue[1].level;
    }
  },

  mounted() {
      this.$store.dispatch('fetchData')
        .then(() => {
          this.data = this.$store.state.data
        })
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
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          EditData({children:'poke',level:this.ruleForm.region,id:this.introd[1].id})
          .then(res => {
            this.$emit('update',this.introd[0].id,this.introd[1].id)
            this.ruleFormShow = false
            this.succe(res);
          })
        } else {
          this.error('error submit!!');
          return false;
        }
      });
    },
    resetForm(formName) {
            this.ruleFormShow = false
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
    line-height: 50px;
    font-size: 18px;
    text-align: center;
    border-radius: 6px;
    background-image: linear-gradient(to right, #ff0000, #00ff00);
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