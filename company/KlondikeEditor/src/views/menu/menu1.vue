<template>
  <div>
    <el-menu style="box-sizing: border-box;height: 100%;" unique-opened default-active="" class="el-menu-vertical-demo"
      :collapse="isCollapse">
      <div :style="{ width: isCollapse == true ? '63px' : '200px' }" class="customs">
        <span style="float: left;" v-if="!isCollapse">关卡</span>
        <span style="float: right;" v-if="!isCollapse">
          <i class="el-icon-search" @click="inquire()" title="搜索"></i>&nbsp;
          <!-- 新增 -->
          <i class="el-icon-circle-plus" @click="fn(navigation, box)" title="新增"></i>&nbsp;
          <!-- 收起导航 -->
          <i class="el-icon-d-arrow-left" @click="isCollapse = !isCollapse" title="关闭"></i></span>
        <!-- 展开 -->
        <i class="el-icon-d-arrow-right" v-if="isCollapse" title="打开" @click="isCollapse = !isCollapse"></i>
      </div>
      <div class="box" v-loading="loading" ref="scrollContainer" :style="{ width: isCollapse == true ? '63px' : '200px' }"
        style="height:calc(100% - 50px);overflow: auto;">
        <div class="box1">
          <el-submenu :index="item.id" v-for="(item, index) in navigation.slice(currentPage, currentPage + 16)"
            @contextmenu.prevent.native="openMenu($event, '0' + item.id, index, item)" :key="index">
            <template slot="title">
              <i class="el-icon-location"></i>
              <!-- 右键 -->
              <span slot="title">{{ isCollapse == false ? item.name : '' }}
              </span>
            </template>
            <draggable v-model="item.levels" group="menu1" @end="onDragEnd($event,item.levels, item)">
              <el-menu-item @click.native="klondikeList(item, items)" :index="'room'+items.id"
                @contextmenu.prevent.stop.native="openMenu($event, '1' + items.id, indexs, items, index)"
                v-for="(items, indexs) in item.levels.slice(0,item.levels.length)" :key="indexs">
                  <template slot="title">
                    <i class="el-icon-location"></i>
                    <span slot="title">
                          {{ items.name+'-'+ items.list}}
                    </span>
                  </template>
              </el-menu-item>
            </draggable>
          </el-submenu>
        </div>
        <div class="box2" :v-if="navigation.length > 15"></div>
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
          <el-autocomplete class="inline-input" v-model="state2" :fetch-suggestions="querySearch" placeholder="请输入内容"
            @select="handleSelect1"></el-autocomplete>
        </el-col>
      </el-row>
      <el-button type="primary" @click="submitForm()">立即创建</el-button>
      <el-button @click="resetForm()">重置</el-button>
    </el-dialog>

    <!-- 新增弹出框 -->
    <ul id="add" ref="add" v-show="show" @mouseleave="show = !show">
      <li v-for="(add, index) in add1 == 1 ? added1 : added" @mouseover="rightOver(add, index)"
        @mouseleave="rightLeave(add, index)" @click="right(index)" class="el-icon-location"
        :key="index">
        {{ add }}
        <ul v-if="add == '新关卡' && difficulty == true" class="difficulty">
          <li v-for="(item, index) in added2" @click="rightData(item,index)" :key="index">{{ item.name }}</li>
        </ul>
      </li>
    </ul>
    <el-dialog title="发送到：" :visible.sync="draggable" width="400px">
      <el-form :model="form" ref="form">
        <el-form-item label="选择关卡组" label-width="120"  prop="region" :rules="[
              { required: true, message: '选择关卡组', trigger: 'change' }
            ]">
          <el-select v-model="form.region" placeholder="选择关卡组">
            <el-option v-for="(item, index) in navigation" :key="index" :label="item.name" :value="item.id"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="draggable = false">取 消</el-button>
        <el-button type="primary" @click="draggables('form')">确 定</el-button>
      </div>
    </el-dialog>
    <input style='width:60px' autofocus v-show="value1" ref="input" @blur.prevent="txtinput()" v-model="value">
  </div>
</template>

<script>
import _ from 'lodash';
import $ from 'jquery';
import axios from 'axios';
import draggable from 'vuedraggable'
import { getdata, postdata, delet, put,Drag, cutGroup, AddData,removeData , post } from '../../api/api-pro'
export default {
  name: 'GameIndex',
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
      navigation: [],
      //右键选择的索引
      box3: '',
      // 获取的编辑元素
      input: '',
      value1: false,
      value: '',
      add1: '1',
      add2: '',
      // 显示隐藏添加
      show: false,
      difficulty: false,
      draggable:false,
      form: {
        region: ''
      },
      added: ['新关卡', '编辑', '删除', '查询'],
      added1: ['编辑', '删除', '名切换','发送到'],
      added2: '', 
      currentPage: 0,
      // 关卡查询
      currentPage1: [],
      // 关卡包查询
      currentPage2: [],
      cutName: '',
      dialogFormVisible: false,
      state1: '',
      state2: '',
      box: ''
    };
  },

  mounted() {
    this.getData()
    this.levelData()
    const scrollContainer = this.$refs.scrollContainer;
    scrollContainer.addEventListener("scroll", this.handleScroll);
  },

  methods: {
    levelData(){
      this.$store.dispatch('fetchData')
        .then(() => {
          this.added2 = this.$store.state.data
        })
    },
    getData() {
      // 全部数据
      getdata()
        .then(res => {
          this.navigation = res.data
          this.currentPage1 = _.cloneDeep(this.navigation)
          this.currentPage2 = _.cloneDeep(this.navigation)
          document.querySelector('.box2').style.height = this.navigation.length * 56 + 'px'
        })
        .catch(err => {
          console.log(err);
        })
    },
    // 滚动事件
    handleScroll() {
      let box1 = document.querySelector(".box1");
      let box = this.$refs.scrollContainer.scrollTop;
      box1.style.top = box + "px";
      this.currentPage = Math.floor(box / 56)
    },

    klondikeList(item, items){
      this.$emit('introduction', this.introduction = [item, items])
    },

    onDragEnd(event,levels, item) {
      console.log(levels, item);
      this.loading = true
      let newIndex = event['newIndex']
      Drag({
        id: levels[newIndex].id,
        children:'poke',
        list: newIndex,
        oldList: levels[newIndex].list,
        klondikeId:  item.id,
      })
      .then(res =>{
        this.getData()
        this.loading = false
        this.succe(res)
      })
      .catch(err =>{
        console.log(err);
      })
    },

    rightOver(data,index){
      index == 0 ? this.difficulty = true : this.difficulty = false
    },
    rightLeave(data,index){
      index == 0 ? this.difficulty = true : this.difficulty = false
    },

    // 右键
    openMenu(e, a, b, c, d) {
      this.levelData()
      // 打开的弹窗
      this.add1 = a.slice(0, 1) == 0 ? '0' : '1'
      this.add2 = a.slice(1)
      this.state1 = c
      // this.loadAll()
      // 菜单下标
      this.box3 = b
      // 输入框内容
      this.value = this.add1 == 0 ? e.currentTarget.children[0].children[1].innerHTML : e.currentTarget.children[1].innerHTML
      this.input = this.add1 == 0 ? e.currentTarget.children[0].children[1] : e.currentTarget.children[1]
      this.show = !this.show;
      // 右键菜单定位
      this.$refs.add.style.top = e.clientY - 10 + 'px'
      this.$refs.add.style.left = e.clientX - 5 + 'px'
      if (b >= 13) {
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
    // 关卡包查询
    inquire() {
      let c = this
      layer.prompt({ title: '查询关卡包' }, function (value, index, elem) {
        if (value.length == 0) {
          c.navigation = c.currentPage2
          layer.msg('查询：内容为空')
        } else {
          // 遍历关卡是否存在改名称
          let a = c.navigation.filter(e => {
            return e.name == value
          })
          if (a.length != 0) {
            c.navigation = a
            document.querySelector('.box2').style.height = c.navigation.length * 56 + 'px'
            layer.msg('查询：成功');
          } else {
            layer.msg('查询：未找到')
          }
        }
        // 关闭 prompt
        layer.close(index);
      })
    },
    // 右键关卡包
    right(a) {
      switch (a) {
        case 0: {
          this.add1 == 0 ? this.add() : this.comp()
          this.$refs.add.style.display = 'none'
          break;
        }
        case 1: {
          this.add1 == 0 ? this.comp() : this.dele()
          this.$refs.add.style.display = 'none'
          break;
        }
        case 2: {
          this.add1 == 0 ? this.dele() : this.cut()
          this.$refs.add.style.display = 'none'
          break;
        }
        case 3: {
          this.add1 == 0 ? this.down() : this.draggable = true
          this.$refs.add.style.display = 'none'
          break;
        }
      }
    },
    rightData(data,index){
      post({
        klondikeId:this.add2,
        level:data.name})
        .then(res => {
          this.succe('关卡：增加成功')
          this.getData()
        })
        .catch(err => {
              console.log(this.add2,data.name);
          this.error('关卡：新增失败')
        })
    },
    // 删除
    dele(b, a) {
      this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        delet({name:this.add1 == 0?'klondike':'poke', id:this.add2})
          .then(res => {
            this.getData()
          })
        this.$emit('introduction', this.introduction = '0')
        this.$message({
          type: 'success',
          message: '删除成功!'
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });
      });
    },
    // 增加关卡.length
    add(a, b) {
      this.levelData()
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
    // 查询
    down() {
      let c = this
      layer.prompt({ title: '查询' }, function (value, index, elem) {
        if (value.length != 0) {
          let a = c.navigation[c.box3].levels.filter(e => {
            return e.name == value
          })
          if (a.length != 0) {
            c.navigation[c.box3].levels = a
            layer.msg('查询：成功'); // 显示 value
          } else {
            layer.msg('查询失败：未找到'); // 显示 value
          }
        } else {
          c.navigation[c.box3].levels = c.currentPage1[c.box3].levels
          layer.msg('查询失败：输入内容为空'); // 显示 value
        }
        // 关闭 prompt
        layer.close(index);
      })
    },
    draggables(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true
          cutGroup({id:this.add2,klondikeId:this.form.region})
          .then(res => {
            this.loading = false
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
          this.input.innerHTML = this.value
        put({children:this.add1 == 0?'klondike':'poke', id:this.add2,name:this.value})
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
      this.state2 = ''
      this.handleSelect()
      if (this.state1.name == '') {
        this.error('要修改的名字为空，清先编辑命名')
        this.dialogFormVisible = false
      }
    },
    submitForm() {
      let c = this.box.filter(e => {
        return e.value == this.state1.name
      })
      let d = this.box.filter(e => {
        return e.value == this.state2
      })
        console.log(c,d);
      this.state1.name = this.state2
      this.state2 = _.cloneDeep(this.cutName)
      let aaa = (id, name) => {
        return put({children:this.add1 == 0?'klondike':'poke', id,name})
          .then(res => {
            res.msg.length == 0 ?
              '' : this.error('命名已存在')
          })
          .catch(err => {
            this.error('切换失败')
          })
      }
      aaa(d[0].address, '000')
        .then(() => {
          aaa(c[0].address, this.state1.name)
            .then(() => {
              aaa(d[0].address, this.state2)
              this.getData()
              this.succe('修改成功！！！')
              this.dialogFormVisible = false
            });
        })
    },
    // 重置
    resetForm() {
      this.state1.name = this.state1.name
      this.state2 = ''
    },
    querySearch(queryString, cb) {
      var restaurants = this.box;
      var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants;
      // 调用 callback 返回建议列表的数据
      cb(results);
    },
    createFilter(queryString) {
      return (restaurant) => {
        return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    // 提示的数据
    loadAll() {
      getdata()
        .then(res => {
          let ae = []
          let obj = { value: '', address: "", data: [] }
          this.box = ae
          res.data.filter(e => {
            e.levels.filter(res => {
              if (res.name == this.state1.name) {
                e.levels.forEach(a => {
                  obj.value = a.name
                  obj.address = a.id
                  obj.data = a.levels
                  ae.push(_.cloneDeep(obj))
                })
              }
            })
          })
        })
    },
    handleSelect(item) {
      this.cutName = this.state1.name
    },

    handleSelect1(item) {
      if (this.state1.name == this.state2) {
        this.$message({
          type: 'info',
          message: '关卡中已存在此命名！！！'
        })
        this.state2 = ''
      }
    },
  },
};
</script>

<style lang="scss" scoped>
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