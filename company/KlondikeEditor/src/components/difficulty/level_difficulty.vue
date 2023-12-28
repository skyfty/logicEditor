<template>
  <div>
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm" style="display: flex;">
      <el-form-item label="活动名称" prop="name">
        <el-input v-model="ruleForm.name" style="width: 100px;"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm(ruleForm)">立即创建</el-button>
        <el-button @click="resetForm('ruleForm')">重置</el-button>
      </el-form-item>
    </el-form>
    <el-table
      :data="this.$store.state.data.slice((currentPage-1)*sizeChange,currentPage*sizeChange)"
      border
      style="width: 100%">
      <el-table-column prop="id" label="日期" width="120"> </el-table-column>
      <el-table-column prop="name" label="姓名"> </el-table-column>
      <el-table-column fixed="right" label="操作" width="120">
        <template slot-scope="scope">
          <el-button @click="handleClick(scope.row)" type="text" size="small">查看</el-button>
          <el-button @click="editClick(scope.row)" type="text" size="small">编辑</el-button>
          <el-button @click="removeClick(scope.row)" type="text" size="small">移除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      @size-change="handleSizeChange"
      hide-on-single-page
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[2, 3, 4, 5]"
      :page-size="2"
      layout="total, sizes, prev, pager, next, jumper"
      :total="this.$store.state.data.length">
    </el-pagination>
  </div>
</template>

<script>
import { AddData, removeData } from '../../api/api-pro';
export default {

  data() {
    return {
      tableData: [],
      ruleForm: {
          name: '',
        },
      rules: {
        name: [
          { required: true, message: '请输入活动名称', trigger: 'blur' },
        ],
      },
      currentPage: 1,
      sizeChange:2
    };
  },

  mounted() {
    this.$store.dispatch('fetchData')
        .then(() => {
          console.log(this.$store.state.data);
        })
        .catch((error) => {
          console.error(error);
        });
  },

  methods: {
    handleClick(row) {
      console.log(row);
    },
    editClick(row) {
      console.log(row);
    },
    removeClick(row) {
      removeData({name:'level_difficulty',id:row.id})
      .then(res => {
        this.$store.dispatch('fetchData')
      })
      .catch(err=>{
        console.log(err);
      })
    },
    message(a){
      this.$message({
          message: a,
          type: 'success'
        });
    },
    submitForm(formName) {
        this.$refs.ruleForm.validate((valid) => {
          if (valid) {
            AddData({name:formName.name,children:'level_difficulty'})
            .then(res => {
              this.$store.dispatch('fetchData')
              this.message(res);
            })
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    handleSizeChange(val) {
      this.sizeChange = val;
    },
    handleCurrentChange(val) {
      this.currentPage = val;
    }
  },
};
</script>

<style lang="scss" scoped>

</style>