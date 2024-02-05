<template>
  <div class="nav">
    <el-button type="primary" size="mini" :loading="loading2" @click="resetGenerateLevel('resetGenerateLevel')">重置关卡包</el-button><br>

    <el-table :data="tableData.slice((currentPage-1)*sizeChange,currentPage*sizeChange)"
      border style="width: 100%"  v-loading="loading">
      <el-table-column prop="id" label="ID"> </el-table-column>
      <el-table-column prop="difficultyLevel" label="难度"> </el-table-column>
      <el-table-column prop="deckGame" label="牌局ID"></el-table-column>
      <el-table-column prop="gold" label="金币"> </el-table-column>
      <el-table-column prop="experience" label="经验"> </el-table-column>
      <el-table-column prop="diamond" label="钻石"> </el-table-column>
      <el-table-column prop="prop" label="道具"> </el-table-column>
      <el-table-column prop="stamina" label="体力"> </el-table-column>
      <el-table-column prop="state" label="状态">
        <template slot-scope="scope">
          <div slot="reference" class="name-wrapper">
            <el-switch v-model="scope.row.state" style="width: 100px !important;"></el-switch>
          </div>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="操作">
        <template slot-scope="scope">
          <el-button @click="editClick(scope.row,scope.$index)" type="text" size="small">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      v-show="tableData.length>5"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[4, 5, 6, 7]"
      :page-size="5"
      layout="total, sizes, prev, pager, next, jumper"
      :total="tableData.length">
    </el-pagination>
    <transition name="el-zoom-in-center">
        <div v-show="show" class="transition-box">
          <el-form ref="formEidt" :model="formEidt" label-width="70px" label-position="left">
            <el-form-item label="ID" class="FormItem">
              <el-input v-model="formEidt.id" disabled style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="牌局ID" class="FormItem">
              <el-input type="number" disabled v-model="formEidt.deckGame" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>
            <el-form-item label="难度" class="FormItem">
              <el-select v-model="formEidt.difficultyLevel" @change="difficultyLevel()" :disabled="formEidt.state" style="width: 100px !important;">
                <el-option :value="item.id" v-for="(item,index) in this.$store.state.data" :key="index"></el-option>
              </el-select>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="经验" class="FormItem">
              <el-input type="number" :disabled="formEidt.difficultyLevel == ''" v-model="formEidt.experience" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>
            <el-form-item label="钻石" class="FormItem">
              <el-input type="number" :disabled="formEidt.difficultyLevel == ''" v-model="formEidt.diamond" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="金币" class="FormItem">
              <el-input type="number" :disabled="formEidt.difficultyLevel == ''" v-model="formEidt.gold" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>
            <el-form-item label="道具" class="FormItem">
              <el-input type="number" v-model="formEidt.prop" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="体力" class="FormItem">
              <el-input type="number" v-model="formEidt.stamina" style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>
            <el-form-item label="状态" class="FormItem">
              <el-switch v-model="formEidt.state" :disabled="formEidt.deckGame == ''" style="width: 100px !important;"></el-switch>
            </el-form-item><br>
            <el-form-item class="dialog-footer">
              <el-button @click="show = !show">取消</el-button>
              <el-button type="primary" @click="saveEditedValue(formEidt)" @keyup.enter.native="saveEditedValue(formEidt)">保存</el-button>
            </el-form-item>
          </el-form>
        </div>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import { deckGame, deckGamePost, addklondike } from '../../api/api-pro';
export default {
  props:['add2','state1'],
  data() {
    return {
      tableData: [],
      formInline: [{
        amount: '',
      }],
      formData:'',
      formEidt:{},
      loading:false,
      loading1:true,
      loading2:false,
      show:false,
      currentPage: 1,
      sizeChange:5,
    };
  },

  watch:{
    state1(newValue, oldValue) {
      // 对state1变化做出响应
      this.tableData = []
      this.getdata(newValue.levels)
    }
  },

  computed: {
    levelAmount() {
      // this.getdata(this.state1)
    },
  },

  mounted() {
    this.getdata(this.state1.levels)
    this.$store.dispatch('fetchData')
        .then(() => {
          // console.log(this.$store.state.data);
        })
        .catch((error) => {
          console.error(error);
        });
  },

  methods: {
    getdata(data){
      data.forEach((res,index) => {
        let difficultyScope = this.$store.state.data.filter(e => e.id == res.difficultyLevel)
        this.tableData.push({id: index+1,
            difficultyLevel: res.difficultyLevel,
            deckGame: res.deckGameId,
            gold: res.gold,
            prop: res.prop,
            stamina: res.stamina,
            difficultyScope: {min:difficultyScope[0].movesMin,max:difficultyScope[0].movesMax},
            experience: res.experience,
            diamond: res.diamond, state:false})
      })
    },
    editClick(row,index) {
      this.show = !this.show;
      row.index = index
      this.formEidt = row
    },
    saveEditedValue(row,index) {
      this.show = false;
    },
    difficultyLevel(){
      let difficultyScope = this.$store.state.data.filter(e => e.id == this.formEidt.difficultyLevel)
      for (const [index, e] of this.tableData.entries()) {
          if(e.id == this.formEidt.id) {
            e.difficultyScope = {min:difficultyScope[0].movesMin,max:difficultyScope[0].movesMax}
            e.gold = difficultyScope[0].gold
            e.experience = difficultyScope[0].experience
            e.diamond = difficultyScope[0].diamond
          }
        }
    },
    message(a){
      this.$message({
          message: a,
          type: 'success'
        });
    },    
    error(a){
      this.$message({
          message: a,
          type: 'error'
        });
    },
    resetGenerateLevel(){
      this.loading2 = true
      this.loading = true
      let c = [];
        for (const [index, res] of this.tableData.entries()) {
          if (res.state == false) {
            c.push(res)
          }
        }
      deckGamePost({ids:JSON.stringify(c)})      
          .then((res) =>{
      console.log(res,c);
            const keys = Object.keys(res);
            keys.forEach(key => {
              this.tableData.forEach((w,i) => {
                if(w.id == key - 0){
                  w.deckGame = res[key]
                }
              })
            });
            this.loading = false
            this.loading2 = false
            this.$emit('getData', );
          })
          .catch((err) =>{console.log(err);})
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
.nav{
  width: 100%;
  form{
    width: 100%;
    display: flex;
    flex-wrap: wrap;
  }
  .transition-box {
      z-index: 1000;
      position: fixed;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 400px;
      height: 338px;
      border-radius: 10px;
      background-color: #c5e1fc;
      color: #fff;
      padding: 20px 15px;
      box-sizing: border-box;
      .dialog-footer{
        display: flex;
        position: fixed;
        bottom: 10px;
        right: 15px;
        justify-content: space-between;
      }
    }
}
</style>