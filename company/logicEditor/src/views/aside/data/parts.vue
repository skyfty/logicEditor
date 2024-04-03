<template>
  <div class="parts"> 
    <!-- 零件属性 -->
    <template v-for="(dividerLabel,dividerIndex) in form">
      <div :key="dividerIndex">
        <el-divider content-position="left">{{ divider[dividerIndex].label }}</el-divider>
        <el-form label-width="35px" hide-required-asterisk :rules="rules[dividerIndex]" :ref="divider[dividerIndex].ref" :model="formInline[dividerIndex]" class="demoFormInline">
          <el-row class="row-bg" :type="updateSize?'flex':''" :justify="updateSize?'space-around':''" >
            <el-col v-for="(item,index) in form[dividerIndex]" :key="index">
              <el-form-item :label="item.label" :prop="item.rules" size="small">
                <el-tooltip class="item" effect="light" size="mini" :disabled="item.tooltip == ''" :content="item.tooltip" placement="bottom">
                  <el-input type="number" v-model="formInline[dividerIndex][item.model]" :placeholder="item.label" v-if="item.type == 'number'"></el-input>
                  <el-switch v-model="formInline[dividerIndex][item.model]" :placeholder="item.label" v-else-if="item.type == 'switch'"></el-switch> 
                  <el-select v-model="formInline[dividerIndex][item.model]" :placeholder="item.label" @click.native="pictureData()" v-else-if="item.type == 'select'">
                      <el-option
                          v-for="(items,indexs) in item.data" :key="indexs"
                          @mouseenter.native="handleMouseEnter($event,items.picture)"
                          @mouseleave.native="handleMouseLeave($event,items.picture)"
                          :value="items.picture" v-show="items.amount > 0">{{ items.picture }}
                      </el-option>
                  </el-select>
                </el-tooltip>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
    </template>
    <!-- 配套模型 -->
    <global-dialog :title="value" ref="globalDialog">
      <matchingModel :introd="$store.state.partsData"></matchingModel>
    </global-dialog>
    <el-button type="primary" class="delet" @click="value = '配套模型';$refs.globalDialog.dialogVisible = true; " :loading="loading"> 配套模型 </el-button>
    <!-- 删除零件 --> 
    <el-button type="primary" class="delet" @click="onSubmit()" :loading="loading">{{ loading ? '正在移除 '+$store.state.partsData[4].name+' ...' : '移除该模型 ‘ '+$store.state.partsData[4].name+' ’' }}</el-button>
    
    <div ref="previewFbx">
      <el-divider content-position="left">模型预览</el-divider>
      <previewFbx :previewFbxData="previewFbxData"></previewFbx>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'
import previewFbx from '../../main/previewFbx.vue'
import matchingModel from '../../aside/data/matchingModel.vue'
import {EditData,GetData,removeData } from '../../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd','navigation','ModelGroup','screenWidth','screenHeight'],
  data() {
    return {
      formInline: [
        { picture: '',},
        { x:'',y:'', z: '' },
        { rotationX: '',rotationY: '',rotationZ: ''},
        { scaleX: '',scaleY: '',scaleZ: ''},
        { antle: '', type: ''},
      ],
      divider:[
        {label:'模型', ref:'formInline'},
        {label:'位置 ', ref:'formInlines'},
        {label:'朝向 ', ref:'formInlineRotation'},
        {label:'大小 ', ref:'modelScale'},
        {label:'属性 ', ref:'type'},
      ],
      form:[
        [
          {label: 'picture', model:'picture', rules:'picture',data:'',type:'select',tooltip:'更换模型'}
        ],
        [
          {label: 'X', model:'x', rules:'x',data:'',type:'number',tooltip:'X坐标'},
          {label: 'Y', model:'y', rules:'y',data:'',type:'number',tooltip:'Y坐标'},
          {label: 'Z', model:'z', rules:'z',data:'',type:'number',tooltip:'Z坐标'},
        ],
        [
          {label: 'X', model:'rotationX', rules:'rotationX',data:'',type:'number',tooltip:'X轴旋转'},
          {label: 'Y', model:'rotationY', rules:'rotationY',data:'',type:'number',tooltip:'Y轴旋转'},
          {label: 'Z', model:'rotationZ', rules:'rotationZ',data:'',type:'number',tooltip:'Z轴旋转'},
        ],
        [
          {label: 'X', model:'scaleX', rules:'scaleX',data:'',type:'number',tooltip:'X轴旋转'},
          {label: 'Y', model:'scaleY', rules:'scaleY',data:'',type:'number',tooltip:'Y轴旋转'},
          {label: 'Z', model:'scaleZ', rules:'scaleZ',data:'',type:'number',tooltip:'Z轴旋转'},
        ],
        [
          {label: 'antle', model:'antle', rules:'antle',data:'',type:'switch',tooltip:'是否可拆解'},
          {label: 'type', model:'type', rules:'type',data:[{filepath:'可拆解',name:"可拆解"},{filepath:'不可拆解',name:"不可拆解"}],type:'select',tooltip:'类型'},
        ],
      ],
      rules: [
        {
          picture: [
            { required: true, message: 'picture'}
          ],
          isDismantle: [
            { required: true, message: 'isDismantle'}
          ],
        },
        {
          x: [
            { required: true, message: 'X'}
          ],
          y: [
            { required: true, message: 'Y'}
          ],
          z: [
            { required: true, message: 'Z'}
          ]
        },
        {
          rotationX: [
            { required: true, message: 'rotationX'}
          ],
          rotationY: [
            { required: true, message: 'rotationY'}
          ],
          rotationZ: [
            { required: true, message: 'rotationZ'}
          ]
        },
        {
          scaleX: [
            { required: true, message: 'scaleX'}
          ],
          scaleY: [
            { required: true, message: 'scaleY'}
          ],
          scaleZ: [
            { required: true, message: 'scaleZ'}
          ]
        },
        {
          antle: [
            { required: true, message: 'antle'}
          ],
          type: [
            { required: true, message: 'type'}
          ],
        },
      ],
      previewFbxData: this.$store.state.partsData[4].picture,
      updateSize: true,
      loading: false,
      globalDialogIndex:null,
      value:null
    };
  },

  components:{
    previewFbx, matchingModel
  },

  watch: {
    '$store.state.partsData'(newValue, oldValue){
      this.previewFbxData = newValue[4].picture;
      this.formCopy(newValue)
    },
    formInline: {  
      handler(newArray, oldArray) {
          const form1 = this.$refs.formInline[0];
          const form2 = this.$refs.formInlines[0];
          const form3 = this.$refs.formInlineRotation[0];
          const form4 = this.$refs.modelScale[0];
          const form5 = this.$refs.type[0];

          Promise.all([form1.validate(), form2.validate(), form3.validate(), form4.validate(), form5.validate()])
            .then(results => {
              const [form1Valid, form2Valid, form3Valid, form4Valid, form5Valid] = results;
              if (form1Valid && form2Valid && form3Valid && form4Valid && form5Valid) {
                EditData({children:'pokeparts',id:this.$store.state.partsData[4].id,data:JSON.stringify(newArray)})
                .then(res => { 
                  this.$store.dispatch('fetchData')
                  .then(data => {
                    this.$store.dispatch('updata') 
                  })  
                  .catch(error => {  
                    // 处理错误  
                    console.error(error);  
                  });
                  this.succe(res.message);
                })
                .catch(err => {
                  console.log(err.responseText);
                })
            } else {
              this.error('error submit!!');
            }
          });
      },  
      deep: true, // 深度监听数组内部对象的变化    
    },  
    screenHeight(newValue){
      // console.log(newValue);
    },
    screenWidth(newValue){
      // console.log(newValue);
      if(newValue > 1400){
        this.updateSize = true
      }else{
        this.updateSize = false
      }
    }
  },

  mounted() {
    if(this.screenWidth > 1400){
        this.updateSize = true
      }else{
        this.updateSize = false
      }
      this.formCopy(this.$store.state.partsData)
  },

  methods: {
    pictureData(){
      this.form[0][0].data = this.ModelGroup 
    },
    onSubmit(formName) {
      this.loading = true
      removeData({id:this.$store.state.partsData[4].id,children:'pokeparts'})
        .then(res => { 
          this.succe(res.message);
          this.loading = false
          this.$store.dispatch('fetchData')
            .then(data => {
              this.$store.dispatch('updataPoke') 
            })  
            .catch(error => {
              console.error(error);  
            });
        })
        .catch(err => {
          this.loading = false
          this.succe(err.responseText);
        })
    },
    // 成功提示
    succe(name) {
      // layer.msg(name, {icon: 1})
      this.$notify.success({
          title: name,
          type: 'success',duration:1000,
          customClass:'notify-success'
        });
    },
    // 失败提示
    error(name) {
      this.$notify.error({
          title: name,
          type: 'error',duration:1000,
          customClass:'notify-error'
        });
    },
    formCopy(newValue){
      this.formInline[0].picture = newValue[4].picture;
      this.formInline[1].x = newValue[4].x;
      this.formInline[1].y = newValue[4].y;
      this.formInline[1].z = newValue[4].z;
      this.formInline[2].rotationX = newValue[4].rotationX;
      this.formInline[2].rotationY = newValue[4].rotationY;
      this.formInline[2].rotationZ = newValue[4].rotationZ;
      this.formInline[3].scaleX = newValue[4].scaleX;
      this.formInline[3].scaleY = newValue[4].scaleY;
      this.formInline[3].scaleZ = newValue[4].scaleZ;
      this.formInline[4].antle =  Boolean(newValue[4].antle);
      this.formInline[4].type = newValue[4].type;
    },
    handleMouseEnter(event,data) {
      this.previewFbxData = data;
      // console.log('鼠标进入',data); 
      this.$refs.previewFbx.style.position = 'fixed'
      this.$refs.previewFbx.style.top = event.target.getBoundingClientRect().top - 20 + 'px'
      this.$refs.previewFbx.style.left = event.target.getBoundingClientRect().left - this.$refs.previewFbx.clientWidth - 20 + 'px'
    },  
    handleMouseLeave(event,data) {
      this.previewFbxData = this.$store.state.partsData[4].picture;
      this.$refs.previewFbx.style.position = ''
    }  
  },
};
</script>

<style lang="scss" scoped>
.parts{
  width: 100%;
  // box-sizing: border-box; 
  // overflow: auto; 
  .delet{
      width: 100%; font-size: 18px;
      margin: 10px 0 !important;
      border-radius: 6px;
      $back: linear-gradient(to bottom, rgb(146, 186, 190), rgba(36, 132, 132, 0.911));
      background:$back;
      &:hover{
        background:$back;
        color: rgb(140, 233, 233);
      }
    }
}
</style>