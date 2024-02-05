<template>
  <div>
    <el-menu style="box-sizing: border-box;height: 100%;" default-active="" class="el-menu-vertical-demo"
      :collapse="isCollapse">
      <div :style="{ width: isCollapse == true ? '63px' : '220px' }" class="customs">
        <span style="float: left;" v-if="!isCollapse">关卡</span>
        <span style="float: right;" v-if="!isCollapse">
          <i class="el-icon-circle-plus" @click="fn(navigation, box)" title="新增"></i>&nbsp;
          <i class="el-icon-d-arrow-left" @click="isCollapse = !isCollapse" title="关闭"></i></span>
          <i class="el-icon-d-arrow-right" v-if="isCollapse" title="打开" @click="isCollapse = !isCollapse"></i>
      </div>
      <div class="box" v-loading="loading" ref="scrollContainer" :style="{ width: isCollapse == true ? '63px' : '220px' }"
        style="height:calc(100% - 50px);overflow: auto;">
        <div class="box1">
          <el-submenu :index="data.id + 'levelGroup'" class="lv" v-for="(data, index1) in navigation" 
              @contextmenu.prevent.native="openMenu($event, '2' + data.id, index1, data)" :key="index1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <!-- 右键 -->
              <span slot="title">{{ isCollapse == false ? data.name : '' }}
              </span>
            </template>
            <draggable v-model="data.levelPack" group="menu1" @end="onDragEnds($event,data.levelPack, data)">
              <el-submenu :index="item.id" v-for="(item, index) in data.levelPack"
                @contextmenu.prevent.stop.native="openMenu($event, '0' + item.id, index, item)" :key="index">
                <template slot="title"> 
                  <i class="el-icon-location"></i>
                  <span slot="title">{{item.name}}
                  </span>
                </template>
                <draggable v-model="item.levels" group="menu1" @end="onDragEnd($event,item.levels, item)">
                  <el-menu-item @click.native="klondikeList(item, items, data)" :index="'room'+items.id"
                    @contextmenu.prevent.stop.native="openMenu($event, '1' + items.id, indexs, items, index, item)"
                    v-for="(items, indexs) in item.levels" :key="indexs">
                      <template slot="title">
                        <i class="el-icon-location"></i>
                        <span slot="title">
                              {{items.name}}
                        </span>
                      </template>
                  </el-menu-item>
                </draggable>
              </el-submenu>
            </draggable>
          </el-submenu>
        </div>
      </div>
    </el-menu>

    <el-dialog title="互换Name" :visible.sync="dialogFormVisible">
      <el-row class="demo-autocomplete">
        <el-col :span="12">
          <div class="sub-title">切换：Name</div>
          <el-autocomplete class="inline-input" v-model="state1.name" :fetch-suggestions="querySearch" placeholder="请输入内容"
            @select="handleSelect"></el-autocomplete>
        </el-col>
        <el-col :span="12">
          <div class="sub-title">切换：Name</div>
          <el-autocomplete class="inline-input" v-model="state2.name" :fetch-suggestions="querySearch" placeholder="请输入内容"
            @select="handleSelect1"></el-autocomplete>
        </el-col>
      </el-row>
      <el-button type="primary" @click="submitForm()">立即创建</el-button>
      <el-button @click="resetForm()">重置</el-button>
    </el-dialog>

    <!-- 新增弹出框 -->
    <ul id="add" ref="add" v-show="show" @mouseleave="show = !show">
      <li v-for="(add, index) in added[add1]" @click="right(index)" class="el-icon-location"
        :key="index">{{ add }}
      </li>
    </ul>
    <el-dialog :title="'将 '+state1.name+' 发送到：'" :visible.sync="draggable" width="400px">
      <el-form :model="form" ref="form">
        <el-form-item label="选择关卡组" label-width="120"  prop="region" :rules="[
              { required: true, message: '选择关卡组', trigger: 'change' }
            ]">
          <el-select v-model="form.region" placeholder="选择关卡组">
            <el-option v-for="(item, index) in filteredNavigation" :key="index" :label="item.name" :value="item.id"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="draggable = false">取 消</el-button>
        <el-button type="primary" @click="draggables('form')">确 定</el-button>
      </div>
    </el-dialog>
    <input style='width:60px' autofocus v-show="value1" ref="input" @blur.prevent="txtinput()" v-model.trim="value">
    <global-dialog :title="globalDialog.name" ref="globalDialog">
      <!-- 弹出框内容 -->
      <level_difficulty ref="difficulty" v-if="globalDialog.id == 0" :add2="add2" @getData="getData"></level_difficulty>
      <resetGroup v-else-if="globalDialog.id == 1" :add2="add2" :state1="state1" @getData="getData"></resetGroup>
      <subpackageView v-else-if="globalDialog.id == 2" :add2="add2" :state1="state1" @getData="getData"></subpackageView>
    </global-dialog>
  </div>
</template>

<script>
import _ from 'lodash';
import $ from 'jquery';
import level_difficulty from '../../components/level/levelGroup.vue';
import resetGroup from '../../components/level/resetGroup.vue';
import subpackageView from '../../components/subpackage/index.vue';
import axios from 'axios';
import draggable from 'vuedraggable'
import { getdata, postdata, delet, put,Drag, cutGroup, deckGame, addklondike, subpackage } from '../../api/api-pro'
export default {
  name: 'GameIndex',
  components: {
    draggable,level_difficulty,resetGroup,subpackageView
  },
  data() {
    return {
      loading:false,
      //向牌桌传入的关卡
      introduction: '',
      // 导航栏的展开收起
      isCollapse: false,
      // box:'展开',
      navigation: [],
      // 获取的编辑元素
      input: '',
      value1: false,
      value: '',
      add1: '1',
      add2: '',
      // 显示隐藏添加
      show: false,
      // difficulty: false,
      draggable:false,
      form: {
        region: ''
      },
      added:[
        ['重置','编辑', '删除','分包','复制卡包'],
        ['编辑', '名切换','发送到'],
        ['新关卡', '编辑', '删除','粘贴'],
      ],
      cutName: '',
      dialogFormVisible: false,
      state1: '',
      state2: {id:"",name:""},
      box: '',
      copyData:[],
      globalDialog:{id:"",name:""},
    };
  },

  mounted() {
    this.$store.dispatch('fetchData')
    this.getData()
    // this.$refs.globalDialog.dialogVisible = true;
  },

  computed: {
    filteredNavigation() {
      let data;
      this.navigation.filter(e => {
        if (e.levelPack) {
          e.levelPack.filter(a => {
            if (a.id === this.state1.KlondikeId) {
              data = e.levelPack;
            }
          });
        }
      })
      return data;
    }
  },

  methods: {
    getData() {
      this.loading = true
      getdata()
        .then(res => {
          this.navigation = res.data
          this.loading = false
          this.$refs.globalDialog.dialogVisible = false;
        })
        .catch(err => {
          console.log(err);
        })
    },

    klondikeList(item, items, data){
      this.$emit('introduction', this.introduction = [item, items, data])
    },

    onDragEnd(event,levels, item) {
      let newIndex = event['newIndex']
      if(event.from == event.to){
        this.loading = true
        Drag({id: levels[newIndex].id,children:'poke',list: newIndex,oldList: levels[newIndex].list,klondikeId:item.id})
        .then(res =>{
          this.getData()
          this.loading = false
          this.succe(res)
        })
        .catch(err =>{
          console.log(err);
        })
      }else{
          this.getData()
        this.error('不能进行跨包移动！！！')
      }
    },

    onDragEnds(event,levels, item) {
      let newIndex = event['newIndex']
      let oldIndex = event['oldIndex']
      if(oldIndex+1 == newIndex+1){
        this.error('拖动位置没有变化')
      }else{
        this.loading = true
          Drag({id: levels[newIndex].id,children:'klondike',list: newIndex + 1,oldList: levels[newIndex].sequenceId - 0,klondikeId:item.id})
          .then(res =>{
            this.getData()
            this.loading = false
            this.succe(res)
          })
          .catch(err =>{
            console.log(err);
          })
      }
    },
    // 右键
    openMenu(e, a, b, c, d,item) {
      this.add1 = a.slice(0, 1)-0
      this.add2 = a.slice(1)
      switch(this.add1){
        case 0:
          this.value = e.currentTarget.children[0].children[1].innerHTML
          this.input = e.currentTarget.children[0].children[1]
          break;
        case 1:
          this.value = e.currentTarget.children[1].innerHTML
          this.input = e.currentTarget.children[1]
          this.globalDialog.id = false
          this.loadAll(item)
          break;
        case 2:
          this.value = e.currentTarget.children[0].children[1].innerHTML
          this.input = e.currentTarget.children[0].children[1]
          this.globalDialog.id = false
          break;
      }
      this.state1 = c
      this.show = !this.show;
      // 右键菜单定位
      this.$refs.add.style.top = e.clientY - 10 + 'px'
      this.$refs.add.style.left = e.clientX - 5 + 'px'
      if (this.$refs.scrollContainer.offsetHeight - e.clientY < 100) {
        this.$refs.add.style.top = e.clientY - 125 + 'px'
        this.$refs.add.style.left = e.clientX - 5 + 'px'
      }
    },

    // 新增
    fn(a, b) {
      postdata()
        .then(res => {
          this.succe('关卡包：新增成功')
          this.getData()
        })
        .catch(err => {
          this.error('关卡包：新增失败')
        })
    },
    // 右键关卡包
    right(a) {
      const actions = [
        [() => {
          this.globalDialog.id = 1,
          this.globalDialog.name = '重置关卡包', 
          this.$refs.globalDialog.dialogVisible = true; 
        }, 
        this.comp, 
        () => {
          this.globalDialog={id:0, name: '新建关卡包'}, 
          this.$refs.globalDialog.dialogVisible = true; }],
        [this.comp, this.cut, this.comp],
        [this.dele, () => { this.draggable = true;console.log(this.filteredNavigation); }, this.dele],
        [this.subpackages, null, this.stickup],
        [this.copy, null, null],
      ];

      if (a >= 0 && a < actions.length && this.add1 >= 0 && this.add1 < actions[a].length) {
        const action = actions[a][this.add1];
        if (action) {
          action();
        }
      }

      this.$refs.add.style.display = 'none';
    },
    // 删除
    dele(b, a) {
      this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        let children
        switch(this.add1){
          case 0:
          children = 'klondike';
            break;
          case 1:
          children = 'poke';
            break;
          case 2:
          children = 'levelGroup';
            break;
        }
        delet({children, id:this.add2})
          .then(res => {
            this.getData()
            console.log(res);
            this.succe( res );
          })
        this.$emit('introduction', this.introduction = '0')
      }).catch(() => {
        this.error('已取消删除');
      });
    },
    // 增加关卡.length
    add(a, b) {
      this.$refs.globalDialog.dialogVisible = true;
    },
    // 分包
    subpackages(){
      function splitArray(arr, groups) {
        let result = [];
        let temp = [];

        let each = Math.ceil(arr.length / groups);

        for(let i = 0; i < arr.length; i++) {
          temp.push(arr[i].id);
          if(temp.length === each) {
            result.push(temp);
            temp = [];
          }
        }

        if(temp.length > 0) {
          result.push(temp); 
        }

        return result;
      }
        this.$prompt('请输入分包数量,需 <= '+this.state1.levels.length, {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
        }).then(({ value }) => {
          if(this.state1.levels.length >= value){
            this.loading = true;
            subpackage({
              levelGroup_id:this.state1.levelGroupId,
              ids:JSON.stringify(splitArray(this.state1.levels, value)),
              deleteId:this.state1.id,
              name:this.state1.name,
            })
            .then(res => {
              this.succe(res);
              this.getData()
            })
            .catch(err => {
              this.succe(err.responseText);
              this.getData()
            })
            }else{
            console.log('不足分包数量');
            }
        }).catch(() => {
          this.error( '取消输入' );       
        });
    },
    // 编辑
    comp() {
      this.value1 = true
      this.input.innerHTML = ''
      this.input.appendChild(this.$refs.input)
      setTimeout(() => {
        this.$refs.input.focus()
      }, 10)
    },
    draggables(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true
          cutGroup({id:this.add2,klondikeId:this.form.region})
          .then(res => {
            this.getData()
            this.succe(res);
          })
          .catch(e => {
            console.log(e);
          })
        } else {
          this.error('error submit!!');
          return false;
        }
      }); 
      this.$refs[formName].resetFields();
      this.draggable = false
    },
    // 编辑失去焦点
    txtinput() {
      if (this.value.length <= 6 && this.value != 0) {
        this.value1 = false,
        this.loading = true
        this.input.innerHTML = this.value
        let children
        switch(this.add1){
          case 0:
          children = 'klondike';
            break;
          case 1:
          children = 'poke';
            break;
          case 2:
          children = 'levelGroup';
            break;
        }
        put({children, id:this.add2,name:this.value})
          .then(res => {
            this.getData()
            this.succe(res)
          })
          .catch(err => {
            this.error('修改失败')
          })
      } else {
        alert('命名长度为大于等于6字符')
        setTimeout(() => {
          this.$refs.input.focus()
        }, 10)
      }
    },
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
    // 切换关卡名
    cut() {
      this.dialogFormVisible = true
      this.state2.name = ''
      this.handleSelect()
      if (this.state1.name == '') {
        this.error('要修改的名字为空，清先编辑命名')
        this.dialogFormVisible = false
      }
    },
    stickup(){
      if(this.copyData.length > 0){
      this.$confirm('是否粘贴已复制的关卡包?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
      this.loading = true
        deckGame({ids:JSON.stringify(this.copyData)})
          .then((res) =>{
            res.forEach((es,index) => {
              this.copyData[index].deckGame = es
              if(index+1 == res.length){
              addklondike({levelGroup_id:this.add2,ids:JSON.stringify(this.copyData),levelAmount:this.copyData.length})
                .then((ress) =>{
                  this.message(ress);
                })
                .catch((errs) =>{
                  this.getData();
                  this.succe(errs.responseText);
                })
              }
            });
          })
          .catch((err) =>{console.log(err);})
      }).catch(() => {
        this.error('已取消粘贴');
      });
    }else{
      this.error('请先复制关卡包');
    }
    },
    copy(){
      let data = this.state1
      this.copyData = []
      for (const [index, res] of data.levels.entries()) {
        let c = this.$store.state.data.filter(e => e.id == res.difficultyLevel)[0]
        this.copyData.push({id:index+1,deckGame:'',
                      difficultyLevel:res.difficultyLevel,
                      prop:res.prop,
                      stamina:res.stamina,
                      gold: res.gold,experience: res.experience,diamond: res.diamond,
                      difficultyScope:{min:c.movesMin,max:c.movesMax}});
        if(index+1 == data.levels.length){
          this.succe('复制成功！！！')
          console.log(this.copyData);
        }
      }
    },
    submitForm() {
          this.loading = true
      put({children:'poke', id:this.state2.id,ids:this.state1.id})
          .then(res => {
            this.getData()
            this.succe(res)
            this.dialogFormVisible = false
          })
          .catch(err => {
            this.error('修改失败')
          })
    },
    // 重置
    resetForm() {
      this.state1.name = this.state1.name
      this.state2.name = ''
    },
    querySearch(queryString, cb) {
      var restaurants = this.box;
      var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants;
      cb(results);
    },
    createFilter(queryString) {
      return (restaurant) => {
        return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    // 提示的数据
    loadAll(data) {
      getdata()
        .then(res => {
          let ae = []
          let obj = { value: '', address: ""}
          this.box = ae
            data.levels.filter(res => {
                  obj.value = res.name
                  obj.address = res.id
                  ae.push(_.cloneDeep(obj))
            })
        })
    },
    handleSelect(item) {
      this.cutName = this.state1.name
    },
    handleSelect1(item) {
      if (this.state1.name == this.state2.name) {
        this.error( '关卡中已存在此命名！！！' )
        this.state2.name = ''
      }else{
        this.state2.id = item.address
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.lv{
  color: #409EFF !important;
}
#add,
#add1 {
  z-index: 2;
  width: 110px;
  padding: 5px;
  background: white;
  display: block;
  position: fixed;
  box-shadow: 3px 3px 5px 3px #aaa;
  border-radius: 5px;

  li {
    width: 100%;
    line-height: 30px;
    .difficulty{
      position: absolute;
      top: 10px;right: -101px;
      width: 100px;padding: 5px;
      box-shadow: 3px 3px 5px 3px #aaa;
      background: #f5f3f3;border-radius: 5px;
    }
  }

  li:hover {
    background: rgb(4, 253, 249);
    border-radius: 5px;
    cursor: pointer;
  }
}

.customs {
  box-sizing: border-box;
  font-size: 18px;
  padding: 0 10px;
  text-align: center;
  height: 48px;
  line-height: 48px;
  background-color: rgb(226, 226, 226);

  i {
    cursor: pointer;
    color: rgb(30, 159, 255)
  }
}

.box {
  height: calc(100vh - 50px);
  // overflow-y: auto;
  overflow-x: hidden;
  box-sizing: border-box !important;
  position: relative;

  .box1 {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
  }

  .box2 {
    position: absolute;
    top: 0;
    width: 100%;
  }
}

</style>