<template>
  <div>
    <div class="shelving-container" ref="shelvingContainer">
      <!-- 层架元素 -->
      <el-tooltip class="item" effect="light" content="想要操作该模型，需要满足一下模型" placement="top">
        <div
          class="shelf"
          :style="{ left: containerX + 'px', top: containerY + 'px', background: 'lightgrey'}"> {{ $store.state.partsData[4].name }} </div>
        </el-tooltip>
      <div
        v-for="(shelf, index) in shelves"
        :key="index"
        class="shelf"
        :style="{ left: shelf.x + 'px', top: shelf.y + 'px' }"
      >
      <el-tag closable :disable-transitions="false" 
            @mouseenter.native="handleMouseEnter($event, shelf)"
            @mouseleave.native="handleMouseLeave($event, shelf)"
            @close="handleClose(index,shelf.id)" size="medium"> {{'ID：' + shelf.id }}</el-tag>
      </div>
      <!-- 模型框与连线 -->
      <svg :width="containerWidth" :height="containerHeight">
        <line
          v-for="(connection, index) in connections"
          :key="index"
          :x1="connection.start.x"
          :y1="connection.start.y"
          :x2="connection.end.x"
          :y2="connection.end.y"
          stroke="black"
          stroke-width="2"
        />
        <marker
          id="arrow"
          viewBox="0 -5 10 10"
          refX="8"
          refY="0"
          markerWidth="6"
          markerHeight="6"
          orient="auto"
        >
          <path d="M0,-5L10,0L0,5" />
        </marker>
      </svg>
    </div>
      <!-- 添加模型表单 -->
      <el-form label-width="auto" :rules="addRules" :ref="addDivider.ref" :model="addFormInline" class="demo-form-inline" style="display: flex;" >
        <el-form-item :label="addForm.label" :prop="addForm.rules">
          <el-tooltip class="item" effect="light" content="添加场景中模型" placement="bottom">
            <el-select v-model="addFormInline[addForm.model]" :placeholder="addForm.label" @click.native="pictureData">
              <el-option 
              v-show="shelves.findIndex(res =>  res.id == option.id) == -1" 
              v-for="(option, optionIndex) in addForm.data" :key="optionIndex" :value="option.id">
                {{ option.picture }}
              </el-option>
            </el-select>
          </el-tooltip>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit(addFormInline.picture)" style="margin: 0 0 0 10px;">创建</el-button>
        </el-form-item>
      </el-form>
      
    <div ref="previewFbx" style="width: 43%;">
      <el-divider content-position="left">{{ previewFbxData == '' ? '模型预览' : '预览模型：'+previewFbxData.name}}</el-divider>
      <previewFbx :previewFbxData="previewFbxData.picture"></previewFbx>
    </div>
  </div>
</template>

<script>
import { GetData, matchingModel } from '../../../api/api-pro'
export default {
  props:['introd' ],
  data() {
    return {
      containerWidth: 0,
      containerHeight: 250,
      containerX: 0,
      containerY: 0,
      lineFeed:[],
      pokeParts_id:0,
      previewFbxData:{ filepath:'' },
      shelves: [
        // { x: 0, y: 200 },
        // { x: 0, y: 200 },
      ],
      connections: [
        // { start: { x: 450, y: 25 }, end: { x: 50, y: 225 } },
      ],
      // 添加模型表单
      addFormInline: { picture: '' },
      addDivider: { label: 'model', ref: 'addFormInline' },
      addForm: { label: 'model', model: 'picture', rules: 'picture', data: [{ picture: '123', name: '123' }], type: 'select', },
      addRules: { picture: [{ required: true, message: 'picture' }] },
    };
  },

  watch: {
    '$store.state.partsData'(newValue, oldValue){
      this.previewFbxData.picture = newValue[4].picture 
      this.addForm.data = newValue[3].parts;
      this.GetData(newValue[4].id) 
      this.pokeParts_id = newValue[4].id
    },
    // introd(newValue){
    //   this.previewFbxData.picture = newValue[4].picture 
    //   this.addForm.data = this.introd[3].parts;
    //   this.GetData(newValue[4].id) 
    //   this.pokeParts_id = newValue[4].id
    // },
    // partsData(newValue){
    //   this.previewFbxData.picture = newValue[4].picture 
    //   this.addForm.data = this.introd[3].parts;
    //   this.GetData(newValue[4].id) 
    //   this.pokeParts_id = newValue[4].id
    // },
  },

  mounted(){
    this.previewFbxData.picture = this.$store.state.partsData[4].picture
    this.addForm.data = this.$store.state.partsData[3].parts;
    this.GetData(this.$store.state.partsData[4].id)
    this.pokeParts_id = this.$store.state.partsData[4].id
  },

  methods: {
    GetData(pokeParts_id){ 
      matchingModel({pokeParts_id:pokeParts_id, type:'get'})
      .then(res => {
          this.shelves = [];
          this.connections = [];
        if(res.data){
          res.data.filter(res =>{
            this.addShelves(res.parts_id)
          })
        }
      })
      .catch(err => {
        console.log(err);
      })
    },
    pictureData(){
      this.addForm.data = this.addForm.data.filter(res =>  res.id != this.$store.state.partsData[4].id) 
    },
    addShelves(id){
      const newShelfX = 100 * (this.shelves.length); // 新增框的横坐标
      const newShelfY = 200; // 新增框的纵坐标
      const newConnectionStartX = newShelfX + 50; // 连线起点的横坐标
      const newConnectionStartY = newShelfY + 25; // 连线起点的纵坐标
      const newConnectionEndX = newShelfX / 2 + 50; // 连线终点的横坐标
      const newConnectionEndY = 25; // 连线终点的纵坐标
      this.containerWidth = newShelfX + 50
      this.containerX = newShelfX / 2 
      // 添加新的框和连线
      this.shelves.push({ x: newShelfX, y: newShelfY ,id});
      this.connections.push({
        start: { x: newConnectionEndX, y: newConnectionEndY },
        end: { x: newConnectionStartX , y: newConnectionStartY },
      });
      this.connections.forEach(connection => {
        connection.start.x = newShelfX / 2 + 50;  
      })
    },
    onSubmit(id) {
      const form1 = this.$refs.addFormInline;
        Promise.all([form1.validate()])
          .then(results => {
            const [form1Valid] = results;
            if (form1Valid) {
              matchingModel({ type:'post', pokeParts_id: this.pokeParts_id, parts_id:id})
                .then(res => {
                  this.succe(res.message);
                  this.addShelves(id)
                  this.$refs.addFormInline.resetFields();
                })
                .catch(err => {
                  console.log(err);
                })
          } else {
            this.error('error submit!!');
          }
        });
    },
    handleClose(index,id){ 
      matchingModel({ type:'delete', pokeParts_id: this.pokeParts_id, parts_id:id})
        .then(res => {
            this.succe(res.message);
            this.shelves.splice(index, 1);
            this.connections.splice(index, 1);
            this.containerWidth = 100 * (this.shelves.length) + 50
            this.containerX = this.shelves.length == 0 ? 0 : 100 * (this.shelves.length) / 2 - 50
            this.shelves.forEach((connection, indexs) => {
              if(index <= indexs){
                connection.x = connection.x - 100  
              }
            })
            this.connections.forEach((connection, indexs) => {
                connection.start.x = 100 * (this.shelves.length) / 2; 
              if(index <= indexs){
                connection.end.x = connection.end.x - 100  
              }
            }) 
        })
        .catch(err => {
          console.log(err);
        })
    },
    handleMouseEnter(event,data) { 
      this.previewFbxData = this.addForm.data.filter(a => a.id == data.id)[0]; 
    },  
    handleMouseLeave(event,data) {
      // this.previewFbxData = '';
    },
    succe(name) {
      // layer.msg(name, {icon: 1})
      this.$notify.success({
          title: name,
          type: 'success',duration:1000,
          customClass:'notify-success'
        });
    },
    error(name) {
      this.$notify.error({
          title: name,
          type: 'error',duration:1000,
          customClass:'notify-error'
        });
    },  
  },
};
</script>

<style scoped>
.shelving-container {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0 0 10px 0;
  overflow: auto;
}

.shelf {
  position: absolute;
  width: 100px;
  height: 50px;
  cursor: pointer;
  text-align: center;
  line-height: 50px;
  overflow: auto;
} 
</style>