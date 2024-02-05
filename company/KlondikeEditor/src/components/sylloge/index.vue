<template>
  <div class="nav">
    <div class="head">
      <div><el-button icon="el-icon-back" class="btn" @click="$router.push({path:'/'})"></el-button></div>
      <div><h1>信息总汇</h1></div>
      <div>
        <el-select v-model="value" clearable popper-class="select" placeholder="请选择" popper-append-to-body @change="handleSearch()">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </div>
    </div>
    <div class="main" ref="main">
      <el-table
        :data="filteredData"
        style="width: 100%;overflow: auto;"
        row-key="id" stripe
        border v-loading="loading"
        :span-method="objectSpanMethod"
        :height="screenHeight - 100"
        resizable
        default-expand-all
        :row-class-name="tableRowClassName"
        :tree-props="{children: 'childrens', hasChildren: 'hasChildren'}">
        <el-table-column
          v-for="(item, index) in tableIndex" :key="index"
          :label="item.label"
          align="center">
            <el-table-column
          v-for="(items, indexs) in item.children" :key="indexs"
              :prop="items.prop"
              :label="items.label">
            </el-table-column>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import { getdata, } from '../../api/api-pro'
export default {
  name: 'KlondikeEditorIndex',

  data() {
    return {
      tableData: [],
      tableIndex:[
        {label:'关卡信息',children:[
          {label:'关卡组ID',prop:"levelGroupId"},
          {label:'关卡包ID',prop:"klondikeId"},
          {label:'关卡ID',prop:"pokeId"},
          {label:'关卡序列',prop:"sequenceId"},
          {label:'难度',prop:"difficultyLevel"},
          {label:'难度描述',prop:"difficultyName"},
        ]},
        {label:'默认奖励',children:[
          {label:'金币',prop:"difficultyScope.gold"},
          {label:'经验',prop:"difficultyScope.experience"},
          {label:'钻石',prop:"difficultyScope.diamond"},
        ]},
        {label:'额外奖励',children:[
          {label:'金币',prop:"difficulty.gold"},
          {label:'经验',prop:"difficulty.experience"},
          {label:'钻石',prop:"difficulty.diamond"},
          {label:'道具',prop:"difficulty.prop"},
          {label:'体力',prop:"difficulty.stamina"},
        ]},
      ],
      options: [],
      value: '',
      screenHeight: document.body.clientHeight,  //屏幕尺寸
      loading:false,
      spanArr:[],
      spanArr1:[],
      spanArr2:[],
      pos:"",
      pos1:"",
      pos2:"",
    };
  },
  computed: {
    filteredData() {
      return this.tableData.filter(e => e.levelGroupId == this.value);
    }
  },

  mounted() {
    this.$store.dispatch('fetchData')
    this.getData()
    window.onresize = () => {   //屏幕尺寸变化就重新赋值
      return (() => {
        this.screenHeight = document.body.clientHeight
      })()
    }
  },

  methods: {
    getData(value) {
      this.loading = true
      getdata()
        .then(res => {
          this.spanArr = []
          this.spanArr1 = []
          this.spanArr2 = []
          this.options = []
          this.tableData = []
          this.pos = ''
          this.pos1 = ''
          this.pos2 = ''
          this.loading = false
          let a = this.value == ''? '':this.value
          let data = a == ''? res.data : res.data.filter(s => s.id == a );

          res.data.forEach((e,index) => {
            this.options.push({label:e.name, value:e.id})
          })
          data.forEach((e,index) => {
            if(e.levelPack != undefined){
              e.levelPack.forEach((a,indexs) => {
                if(a.levels != undefined){
                  a.levels.forEach((res,index1) => {
                  let difficultyScope = this.$store.state.data.filter(e => e.id == res.difficultyLevel)[0]
                  this.tableData.push({levelGroupId:e.id, klondikeId:a.id, pokeId:res.id, sequenceId:res.sequenceId,
                        difficultyLevel:res.difficultyLevel, 
                        difficultyName:difficultyScope.difficulty, 
                        difficultyScope:{
                          gold:difficultyScope.gold,
                          experience:difficultyScope.experience,
                          diamond:difficultyScope.diamond,
                        }, 
                        difficulty:{
                          gold:res.gold,
                          experience:res.experience,
                          diamond:res.diamond,
                          prop:res.prop,
                          stamina:res.stamina,
                        }
                      })
                  })
                }
              })
            }
            if(index + 1 === data.length){
                this.spanArrs('levelGroupId',this.spanArr,this.pos)
                this.spanArrs('klondikeId',this.spanArr1,this.pos1)
                this.spanArrs('pokeId',this.spanArr2,this.pos2)
                this.value = this.tableData[0].levelGroupId
            }
          });
        })
        .catch(err => {
          console.log(err);
        })
      },
      handleSearch(){
        this.getData()
      },
    tableRowClassName({row, rowIndex}) {
          return 'warning-row';
      },
    spanArrs(index,spanArr,pos){
      for (let i = 0; i < this.tableData.length; i++) {
        if (i === 0) {
              spanArr.push(1);
              pos = 0
          } else {
              if (this.tableData[i][index] === this.tableData[i - 1][index]) {
                  spanArr[pos] += 1;
                  spanArr.push(0);
              } else {
                  spanArr.push(1);
                  pos = i;
              }
          }
      }
      },
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
        if (columnIndex === 0) {
          const _row = this.spanArr[rowIndex];
          const _col = _row > 0 ? 1 : 0;
          return {
              rowspan: _row,
              colspan: _col
          }
        }else if (columnIndex === 1) {
          const _row = this.spanArr1[rowIndex];
          const _col = _row > 0 ? 1 : 0;
          return {
              rowspan: _row,
              colspan: _col
          }
        }else if (columnIndex === 2) {
          const _row = this.spanArr2[rowIndex];
          const _col = _row > 0 ? 1 : 0;
          return {
              rowspan: _row,
              colspan: _col
          }
        }
      }
  },
};
</script>

<style lang="scss" scoped>
  .nav{
    width: 100%;
    height: 100vh;
    margin: 0;
    padding: 0;
    .head{
      height: 50px;
      background: rgba($color: #b1b1b1, $alpha: 0.5);
      margin: 0 0 20px 0;
      padding: 0 100px;
      box-shadow: 0px 3px 3px 2px #acabab;
      display: flex;
      justify-content: space-between;
      div{
        display: flex;
        align-items: center;
      }
      .btn{
        background: rgba(238, 238, 238, 0.1);
        font-size: 30px;border: 0;
      }
      div:nth-child(3){
        // ::v-deep .el-select {
        //   width: 350px;
        // }
        ::v-deep .el-input__inner {
          // background-color: transparent;
          opacity: 0.8;
          border-radius: 10px;
        }
      }
    }
    .main{
      height: calc(100% - 100px);
      margin: 0 100px;
      margin-bottom: 20px;
      overflow: auto;
      box-shadow: 0px 3px 3px 5px #bebebe;
      .el-table .warning-row {
        background: rgb(89, 60, 6) !important;
      }

      .el-table .success-row {
        background: #f0f9eb !important;
      }
    }
  }
</style>