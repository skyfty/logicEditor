<template>
  <div class="navs">
    <el-form ref="formInline" :model="formInline[0]" label-position="left" :rules="ruleInline[0]" inline>
        <el-form-item :prop="item.user" :label="item.label" class="FormItem" v-for="(item,index) in formData[0]" :key="index">
            <el-input :type="item.type" v-if="item.type != 'Select'" v-model="formInline[0][item.user]" :value="item.user" style="width: 100px !important;" :placeholder="item.label"></el-input>
            <el-select v-model="formInline[0][item.user]" v-else :placeholder="item.label" style="width: 100px !important;">
              <el-option :value="item+''" v-for="(item,index) in item.data" :key="index">{{item}}</el-option>
            </el-select>
        </el-form-item>
    </el-form>
    <h3>奖励</h3>
    <el-form ref="handleSubmitSeat" :model="formInline[1]" label-position="left" :rules="ruleInline[1]" inline @keyup.enter.native="handleSubmitSeat('handleSubmitSeat')">
        <el-form-item :prop="item.user" :label="item.label" class="FormItem" v-for="(item,index) in formData[1]" :key="index">
            <el-input :type="item.type" v-if="item.type != 'Select'" v-model="formInline[1][item.user]" :value="item.user" style="width: 100px !important;" :placeholder="item.label"></el-input>
        </el-form-item>
        <el-form>
            <el-button type="primary" @click="handleSubmitSeat('handleSubmitSeat')">创建物品栏</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-form>
    </el-form>

    <el-table :data="this.$store.state.data.slice((currentPage-1)*sizeChange,currentPage*sizeChange)"
      border style="width: 100%">
      <el-table-column prop="id" label="日期"> </el-table-column>
      <el-table-column prop="difficulty" label="难度"> </el-table-column>
      <el-table-column prop="grade" label="等级"> </el-table-column>
      <el-table-column prop="gold" label="金币"> </el-table-column>
      <el-table-column prop="experience" label="经验"> </el-table-column>
      <el-table-column prop="diamond" label="钻石"> </el-table-column>
      <el-table-column prop="movesMin" label="最小步数"> </el-table-column>
      <el-table-column prop="movesMax" label="最大步数"> </el-table-column>
      <el-table-column fixed="right" label="操作">
        <template slot-scope="scope">
          <el-button @click="editClick(scope.row)" type="text" size="small">编辑</el-button>
          <el-button @click="removeClick(scope.row)" type="text" size="small">移除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      v-show="this.$store.state.data.length>3"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[2, 3, 4, 5]"
      :page-size="3"
      layout="total, sizes, prev, pager, next, jumper"
      :total="this.$store.state.data.length">
    </el-pagination>
    <transition name="el-zoom-in-center">
        <div v-show="show" class="transition-box">
          <el-form ref="formEidt" :model="formEidt" label-width="70px" label-position="left">
            <el-form-item label="ID" class="FormItem">
              <el-input v-model="formEidt.id" disabled style="width: 100px !important;" placeholder="请输入内容"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="难度等级" class="FormItem">
              <el-select v-model="formEidt.grade" style="width: 100px !important;">
                  <el-option :value="item" v-for="(item,index) in formData[0][1].data" :key="index">{{item}}</el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="难度" class="FormItem">
              <el-select v-model="formEidt.difficulty" disabled style="width: 100px !important;">
                  <el-option :value="item" v-for="(item,index) in ['简单','中等','困难','专家','大师']" :key="index">{{item}}</el-option>
              </el-select>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="金币" class="FormItem">
              <el-input type="number" v-model="formEidt.gold" style="width: 100px !important;" placeholder="金币"></el-input>
            </el-form-item>
            <el-form-item label="经验" class="FormItem">
              <el-input type="number" v-model="formEidt.experience" style="width: 100px !important;" placeholder="经验"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="钻石" class="FormItem">
              <el-input type="number" v-model="formEidt.diamond" style="width: 100px !important;" placeholder="钻石"></el-input>
            </el-form-item>
            <el-form-item label="最小步数" class="FormItem">
              <el-input type="number" v-model="formEidt.movesMin" style="width: 100px !important;" placeholder="最小步数"></el-input>
            </el-form-item>&nbsp;&nbsp;&nbsp;&nbsp;
            <el-form-item label="最大步数" class="FormItem">
              <el-input type="number" v-model="formEidt.movesMax" style="width: 100px !important;" placeholder="最大步数"></el-input>
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
import { AddData, removeData, EditData } from '../../api/api-pro';
export default {

  data() {
    return {
      tableData: [],
      formInline: [{
        difficulty: '',
        grade:''
      },{
        gold: '',
        experience: '',
        diamond: '',
        movesMin:"",
        movesMax:"",
      }],
      formData:[[
          {label:"难度",data:['简单','中等','困难','专家','大师'],user:'difficulty',type:'Select'},
          {label:"等级",data:['1','2','3','4','5'],user:'grade',type:'Select'},
        ],[
          {label:"金币",data:'',user:'gold',type:'number',},
          {label:"经验",data:'',user:'experience',type:'number',},
          {label:"钻石",data:'',user:'diamond',type:'number',},
          {label:"最小步数",data:'',user:'movesMin',type:'number',},
          {label:"最大步数",data:'',user:'movesMax',type:'number',},
        ]],
      ruleInline: [{
          difficulty: [
              { required: true, message: '未设置难度', trigger: 'blur' }
          ],
          grade: [
              { required: true, message: '未设置难度等级', trigger: 'blur' }
          ],
      },{
          gold: [
              { required: true, message: '未设置奖励金币', trigger: 'blur' }
          ],
          experience: [
              { required: true, message: '未设置奖励', trigger: 'blur' }
          ],
          diamond: [
              { required: true, message: '未设置奖励', trigger: 'blur' }
          ],
          movesMin: [
              { required: true, message: '未设置最小步数', trigger: 'blur' }
          ],
          movesMax: [
              { required: true, message: '未设置最大步数', trigger: 'blur' }
          ],
      }],
      formEidt:{},
      show:false,
      currentPage: 1,
      sizeChange:3
    };
  },

  mounted() {
    this.$store.dispatch('fetchData')
        .then(() => {
          // console.log(this.$store.state.data);
        })
        .catch((error) => {
          console.error(error);
        });
  },

  methods: {
    editClick(row) {
      this.show = !this.show;
      this.formEidt = row
    },
    saveEditedValue(row,index) {
      this.formEidt.children='levelDifficulty'
      EditData(this.formEidt)
            .then(res => {
              this.message(res);
              this.$store.dispatch('fetchData')
            })
      this.show = false;
    },
    removeClick(row) {
      removeData({name:'levelDifficulty',id:row.id})
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
    resetForm(formName) {
      this.$refs.formInline.resetFields();
      this.$refs.handleSubmitSeat.resetFields();
    },
    handleSubmitSeat(name) {
      const form1 = this.$refs.formInline;
      const form2 = this.$refs.handleSubmitSeat;

      Promise.all([form1.validate(), form2.validate()])
        .then(results => {
          const [form1Valid, form2Valid] = results;
          if (form1Valid && form2Valid) {
            let difficulty = this.formInline[0].difficulty
            let grade = this.formInline[1].grade
            let gold = this.formInline[1].gold
            let experience = this.formInline[1].experience
            let diamond = this.formInline[1].diamond
            let movesMin = this.formInline[1].movesMin
            let movesMax = this.formInline[1].movesMax
            AddData({difficulty,grade,gold,experience,diamond,movesMin,movesMax,children:'levelDifficulty'})
            .then(res => {
              this.$store.dispatch('fetchData')
              this.message(res);
              this.resetForm()
            })
            } else {
                // this.$Message.error('Fail!');
            }
        })
        .catch(error => {
          // console.error(error);
        });
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
.navs{
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
      height: 345px;
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