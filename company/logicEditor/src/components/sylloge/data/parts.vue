<template>
  <div class="navs">
    <div style="width: 55%;">
      <!-- 
      http://8.141.89.131/logicEditor/AddData.php
      http://localhost:8025/logicEditor/AddData.php
      -->
      <el-upload
        class="upload-demo"
        ref="upload" multiple
        :data="{name:'parts'}"
        action="http://localhost:8025/logicEditor/AddData.php"
        :on-remove="handleRemove"
        :on-success="handleSuccess"
        :before-upload="beforeUpload"
        :file-list="fileList"
        :auto-upload="false">
        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button>
        <div slot="tip" class="el-upload__tip">只能上传fbx文件，且不超过500kb</div>
      </el-upload>
      <el-table
        :data="tableData.slice((currentPage4-1)*SizePage4,currentPage4 * SizePage4)"
        border
        @cell-mouse-enter="handleCellMouseEnter"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(202,220,235, 0.8)"
        style="width: 100%;">
        <el-table-column prop="id" label="ID"></el-table-column>
        <el-table-column prop="name" label="Name"></el-table-column>
        <el-table-column prop="filepath" label="Filepath">
          <template slot-scope="scope">
            <!-- <el-popover trigger="hover" placement="top">
              <div slot="reference" class="name-wrapper"> -->
                <el-tag size="medium">{{ scope.row.filepath }}</el-tag>
              <!-- </div>
            </el-popover> -->
          </template>
        </el-table-column>
        <el-table-column prop="filename" label="Filename"></el-table-column>
        <el-table-column fixed="right" label="操作">
          <template slot-scope="scope">
            <el-button @click="deleteRow(scope.row)" type="text" size="small">移除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="block">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page="currentPage4"
          :page-sizes="[5, 6, 7, 8]"
          :page-size="7"
          layout="total, sizes, prev, pager, next, jumper"
          :total="tableData.length">
        </el-pagination>
      </div>
    </div>
    <div ref="previewFbx" style="width: 43%;">
      <el-divider content-position="left">{{ previewFbxData == '' ? '模型预览' : '预览模型：'+previewFbxData.name}}</el-divider>
      <previewFbx v-if="previewFbxData" :previewFbxData="previewFbxData.filepath"></previewFbx>
    </div>
  </div>
</template>

<script>
import {GetData,removeData,editData} from '../../../api/api-pro' 
export default {
  name: 'PuzzleGameImage', 

  data() {
    return {
      tableData: [],
      dialogVisible: false, // 控制编辑框的显示与隐藏
      show2:false,
      currentPage4: 1,
      SizePage4:7,
      loading: true,
      fileList: [],
      previewFbxData:''
    };
  },

  mounted() {
    this.getdate()
  }, 

  methods: {
    // 数据
    getdate(){
      GetData({name:'parts'})
        .then(res => {
          this.loading = false; // 显示加载组件
          this.tableData = res;
        })
        .catch(err => {
          console.log('Error fetching images:', err);
        })
    },
    // 删除
    deleteRow(val){
      this.loading = true;
      removeData({id:val.id,children:'parts'})
        .then(res => {
          this.loading = false;
          this.success(res.message,1);
          this.getdate()
        })
        .catch(err => {
          this.loading = false;
          this.success(err.responseText,1);
          this.getdate()
        })
    },
    handleCellMouseEnter(row, column, cell, event) {
      // console.log('鼠标进入了单元格：', row, column, cell, event);  
      this.previewFbxData = row
    },
    handleCurrentChange(val) {
      this.currentPage4 = val
    },
    handleSizeChange(val) {
      this.SizePage4 = val
    },
    // 上传
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleSuccess(response, file, fileList) {
      this.success(response.message,1)
      this.getdate()
    },
    handleRemove(file, fileList) {
      this.success('删除'+file.name,0)
    },
    success(a,b){
      layer.msg(a, {icon: b});
    },
    beforeUpload(file) {
      const extension = file.name.split('.').pop().toLowerCase();
      if (extension !== 'fbx') {
        this.$message.error('只能上传fbx文件');
        return false; // 阻止文件上传
      }
      return true; // 允许文件上传
    }
  },
};
</script>

<style lang="scss" scoped>
  .navs{
    // overflow: auto;
    display: flex;
    justify-content: space-between;
    .transition-box {
      z-index: 1000;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 300px;
      height: 265px;
      border-radius: 10px;
      background-color: #c5e1fc;
      color: #fff;
      padding: 20px 15px;
      box-sizing: border-box;
      .dialog-footer{
        display: flex;
        position: fixed;
        bottom: 20px;
        right: 15px;
        justify-content: space-between;
      }
    }
  }
</style>

