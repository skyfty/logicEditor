<template>
  <div>
    <el-menu unique-opened style="box-sizing: border-box;height: calc(100vh - 45px) !important;width: 100%;"
              :default-active="'parts'+$store.state.partsActiveID" @open="handleOpen" @select="handleSelect">
      <div  class="customs">
        <span style="float: left;">关卡</span>
        <span style="float: right;">
          <i class="el-icon-circle-plus" @click="fn( )" title="新增"></i>&nbsp;
          <i class="el-icon-d-arrow-left" title="关闭"></i></span>
      </div>
      <el-skeleton style="height: calc(100vh - 93px) !important;" v-if="loading" animated :count="3"/>
      <div class="box" v-else v-loading="loading" ref="scrollContainer">
        <div class="box1">
          <el-submenu :index="data.id + 'levelGroup'" class="lv" v-for="(data, index1) in $store.state.data" 
              @contextmenu.prevent.native="openMenu($event, '2' + data.id, index1, data)" :key="index1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <!-- 右键 -->
              <span slot="title">{{ isCollapse == false ? data.name : '' }}
              </span>
            </template>
            <draggable v-model="data.levelPack" group="menu1" @end="onDragEnds($event,data.levelPack, data)">
              <el-submenu :index="item.id" v-for="(item, index) in data.levelPack"
                @contextmenu.prevent.stop.native="openMenu($event, '0' + item.id, index, item,index1,data)" :key="index">
                <template slot="title"> 
                  <i class="el-icon-location"></i>
                  <span slot="title"> {{item.name}} </span>
                </template>
                <draggable v-model="item.levels" group="menu1" @end="onDragEnd($event,item.levels, item)">
                  <el-submenu :index="'room'+items.id"
                   @click.native="klondikeList( 'poke', data, item, items)"
                    @contextmenu.prevent.stop.native="openMenu($event, '1' + items.id, indexs, items, index, item)"
                    v-for="(items, indexs) in item.levels" :key="indexs">
                      <template slot="title">
                        <i class="el-icon-location"></i>
                        <span slot="title"> {{items.name}} </span>
                      </template>
                      <el-menu-item :index="'parts'+items1.id" 
                      @click.native.stop="klondikeList('pokeParts', data, item, items, items1)"
                      @contextmenu.prevent.stop.native="openMenu($event, '3' + items.id, indexs, items, index, item)"
                       v-for="(items1, indexs1) in items.parts" :key="indexs1">
                        <template slot="title">
                          <i class="el-icon-location"></i>
                          <span slot="title">
                                {{items1.name}}
                          </span>
                        </template>
                      </el-menu-item>
                  </el-submenu>
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
  </div>
</template>

<script>
import _ from 'lodash';
import $ from 'jquery'; 
import axios from 'axios';
import draggable from 'vuedraggable'
import { getdata, postdata, delet, put,Drag, cutGroup, deckGame, addklondike, addPoke, subpackage } from '../../api/api-pro'
import loginVue from '../../components/popups/login.vue';
export default {
  name: 'GameIndex',
  props:['navigation'],
  components: {
    draggable
  },
  data() {
    return {
      loading:false,
      //向牌桌传入的关卡
      introduction: '', 
      // 导航栏的展开收起
      isCollapse: false,
      // box:'展开',
      // navigation: [],
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
        ['新关卡','编辑', '删除','分包','复制卡包'],
        ['编辑','删除', '名切换','发送到'],
        ['新关卡包', '编辑', '删除','粘贴'],
      ],
      cutName: '',
      dialogFormVisible: false,
      state1: '',
      state2: {id:"",name:""},
      box: '',
      copyData:[], 
    };
  },

  mounted() { 
    this.getData() 
  },

  computed: {
    filteredNavigation() {
      if(this.$store.state.data){
        let data;
        this.$store.state.data.filter(e => {
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
    }
  },

  methods: {
    handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
    getData() {
      // this.$emit('updateData',)
      this.$store.dispatch('fetchData')  
        .then(data => {
          // console.log(data,this.$store.state.data);  
        })  
        .catch(error => {  
          // 处理错误  
          console.error(error);  
        });
    },

    klondikeList(item, items, data, parts, tableName){
      if(item == 'poke'){
        this.$store.dispatch('pokeData', this.introduction = [item, items, data, parts])
        this.$emit('introduction', this.introduction = [item, items, data, parts])
      }else{
        this.$store.dispatch('partsData', this.introduction = [item, items, data, parts, tableName])
        this.$emit('introduction', this.introduction = [item, items, data, parts, tableName])
      }
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
          this.getData()
          this.loading = false
          this.succe(err.responseText)
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
            this.getData()
            this.loading = false
            this.succe(err.responseText)
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
          this.introduction = item
          this.show = !this.show;
          break;
        case 1:
          this.value = e.currentTarget.children[0].children[1].innerHTML
          this.input = e.currentTarget.children[0].children[1] 
          this.show = !this.show;
          this.loadAll(item)
          break;
        case 2:
          this.value = e.currentTarget.children[0].children[1].innerHTML
          this.input = e.currentTarget.children[0].children[1] 
          this.show = !this.show;
          break;
        default:

          break;
      }
      this.state1 = c
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
          this.loading = true
      postdata()
        .then(res => {
          this.succe('关卡包：新增成功')
          this.getData()
          this.loading = false
        })
        .catch(err => {
          this.error('关卡包：新增失败')
        })
    },
    // 右键关卡包
    right(a) {
      const actions = [
        [this.addPoke, this.comp, this.addKlondike],
        [this.comp, this.dele, this.comp],
        [this.dele, this.cut, this.dele],
        [this.subpackages, () => { this.draggable = true;console.log(this.filteredNavigation); }, this.stickup],
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
          this.loading = true
        delet({children, id:this.add2})
          .then(res => {
            this.getData() 
          this.loading = false
            this.succe( res.message );
          })
          .catch(err => {
          this.loading = false
            this.getData()
            this.succe( err.responseText );
          })
        this.$emit('introduction', this.introduction = '')
      }).catch(() => {
        this.error('已取消删除');
      });
    },
    addKlondike(a, b) {
      this.loading = true;
      addklondike({levelGroup_id:this.add2})
        .then((res) => {
          console.log(res);
          this.getData()
          this.loading = false
        })
        .catch((err) => {
          console.log(err.responseText);
          this.loading = false
          this.getData()
        })
    },
    addPoke(a, b) { 
          this.loading = true;
      addPoke({klondike_id:this.add2,levelGroup_id:this.introduction.id})
        .then((res) => {
          this.getData()
          this.loading = false
        })
        .catch((err) => { 
          this.getData()
          this.loading = false
        })
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
          this.loading = false
              this.getData()
            })
            .catch(err => {
              this.succe(err.responseText);
          this.loading = false
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
          this.loading = false
            this.succe(res);
          })
          .catch(e => {
            this.getData()
          this.loading = false
            this.error(e.responseText);
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
            this.loading = false;
            this.succe(res.message)
          })
          .catch(err => {
            this.getData()
            this.error(err)
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
          this.loading = false
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
  height: calc(100% - 50px);
  // overflow-y: auto;
  overflow: auto;
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