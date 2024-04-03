<template>
  <div class="parts">
    <!-- 零件属性 -->
    <template v-for="(dividerLabel,dividerIndex) in form">
      <div :key="dividerIndex">
        <el-divider content-position="left" v-if="divider[dividerIndex].label != ''">{{ divider[dividerIndex].label }}</el-divider>
        <el-form label-width="50px" :rules="rules[dividerIndex]" :ref="divider[dividerIndex].ref" :model="formInline[dividerIndex]" class="demoFormInline">
          <el-row class="row-bg" :type="updateSize?'flex':''" :justify="updateSize?'space-around':''" >
            <el-col v-for="(item,index) in form[dividerIndex]" :key="index">
              <el-form-item :label="item.label" :prop="item.rules" size="small">
                <el-tooltip class="item" effect="light" size="mini" :disabled="item.tooltip == ''" :content="item.tooltip" placement="bottom">
                  <el-input type="number" :min="dividerIndex == 0 ? 0:1" v-model="formInline[dividerIndex][item.model]" :placeholder="item.label" v-if="item.type == 'number'"></el-input>
                  <el-select v-model="formInline[dividerIndex][item.model]" :placeholder="item.label" @click.native="pictureData()" v-else-if="item.type == 'select'">
                    <el-option v-for="(items,indexs) in item.data" :key="indexs" :value="items.filepath">{{ items.name }}</el-option>
                  </el-select>
                </el-tooltip>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
    </template>
    <!-- 创建添加模型 -->
    <el-divider content-position="left">{{ addDivider.label }}</el-divider>  
    <el-form label-width="35px" :rules="addRules" :ref="addDivider.ref" :model="addFormInline" class="demo-form-inline">  
      <el-row class="row-bg" :type="updateSize?'flex':''" :justify="updateSize?'space-around':''" >  
        <el-col>  
          <el-form-item :label="addForm.label" :prop="addForm.rules" size="small"> 
            <el-tooltip class="item" effect="light" size="mini" :content="ModelGroupData.length == 0 ? '需要先场景卡组' : '添加场景中模型'" placement="bottom"> 
              <el-input type="number" min="1" v-model="addFormInline[addForm.model]" :placeholder="addForm.label" v-if="addForm.type == 'number'"></el-input>  
              <el-select v-model="addFormInline[addForm.model]" :disabled="ModelGroupData.length == 0" :placeholder="addForm.label" @click.native="pictureData()" v-else-if="addForm.type == 'select'">  
                <el-option 
                  v-for="(option, optionIndex) in addForm.data" :key="optionIndex" 
                  :value="option.picture" v-show="option.amount > 0">{{ option.picture.split('/')[option.picture.split('/').length - 1].split('.')[0] }}</el-option>  
              </el-select> 
            </el-tooltip> 
          </el-form-item>  
        </el-col>  
        <el-form-item>  
          <el-button type="primary" @click="onSubmit" :loading="loading">{{  loading ? '正在创建 ': '创建'  }}</el-button>  
        </el-form-item>  
      </el-row>  
    </el-form>
    <!-- 模型组 -->
    <ModelGroup ref="ModelGroup" :introd="introd" :navigation="navigation"
                @handleModelGroupDataChange="handleModelGroupDataChange"
                :screenHeight="screenHeight" :screenWidth="screenWidth"></ModelGroup>
  </div>
</template>

<script>
import $ from 'jquery'
import ModelGroup from './ModelGroup.vue'
import {EditData,GetData,ModelGroupParts,addParts} from '../../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd','pokeData','navigation','screenWidth','screenHeight'],
  data() {
    return {
      // 关卡属性表单
      formInline: [
        { timeLimit:this.introd[3].timeLimit, gold:this.introd[3].gold, experience:this.introd[3].experience, },
        { diamond: this.introd[3].diamond, prop:this.introd[3].prop, stamina:this.introd[3].stamina }, 
      ],
      divider:[
        {label:'关卡奖励', ref:'formInline'},
        {label:'', ref:'formInlines'},
      ], 
      form:[
        [
          {label: 'timeLimit', model:'timeLimit', rules:'timeLimit',data:'',type:'number',tooltip:'时限、单位为：S'},
          {label: 'gold', model:'gold', rules:'gold',data:'',type:'number',tooltip:'金币'},
          {label: 'experience', model:'experience', rules:'experience',data:'',type:'number',tooltip:'经验'},
        ],
        [
          {label: 'diamond', model:'diamond', rules:'diamond',data:'',type:'number',tooltip:'钻石'},
          {label: 'prop', model:'prop', rules:'prop',data:'',type:'number',tooltip:'道具'},
          {label: 'stamina', model:'stamina', rules:'stamina',data:'',type:'number',tooltip:'体力'},
        ]
      ],
      rules: [
        {
          timeLimit: [
            { required: true, message: 'timeLimit'}
          ],
          gold: [
            { required: true, message: 'gold'}
          ],
          experience: [
            { required: true, message: 'experience'}
          ]
        },
        {
          diamond: [
            { required: true, message: 'diamond'}
          ],
          prop: [
            { required: true, message: 'prop'}
          ],
          stamina: [
            { required: true, message: 'stamina'}
          ]
        }
      ],
      // 添加模型表单
      addFormInline:{ picture: '',},
      addDivider:{label:'添加模型零件', ref:'addFormInline'},
      addForm:{label: 'picture', model:'picture', rules:'picture',data:'',type:'select',},
      addRules: { picture: [ { required: true, message: 'picture'} ] },
      introdOldValue:'',
      ModelGroupData:[],
      updateSize: true, 
      loading: false,
    };
  },

  components: {
    ModelGroup,
  },

  watch: { 
    '$store.state.pokeData'(newValue, oldValue){
      this.formCopy(newValue)
    },
    // introd(newValue,oldValue){
    //   this.introdOldValue = oldValue;
    //   this.formCopy(newValue,oldValue)
    // },
    ModelGroupData(newValue) {
      this.$emit('ModelGroupDataChange', newValue);
      // 在这里处理变化  
    },  
    navigation(newValue){
      let levelGroup = newValue.filter(e => e.id == this.introd[1].id)[0]
      let klondike = levelGroup.levelPack.filter(e => e.id == this.introd[2].id)[0]
      let poke = klondike.levels.filter(e => e.id == this.introd[3].id)[0]
      this.$emit('introduction',[ 'poke', levelGroup, klondike, poke])
    },
    formInline: {   
      handler(newArray, oldArray) {
        const form3 = this.$refs.formInline[0];
        const form4 = this.$refs.formInlines[0];

        Promise.all([form3.validate(), form4.validate()])
          .then(results => {
            const [form3Valid, form4Valid] = results;
            if (form3Valid && form4Valid) {
              EditData({children:'poke',id:this.introd[3].id,data:JSON.stringify(newArray)})
              .then(res => {
                // this.$emit('updateData',)
                this.$store.dispatch('fetchData')
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
    this.formCopy(this.$store.state.pokeData)
  },

  methods: {
    pictureData(){
      this.addForm.data = this.ModelGroupData;
    },
    onSubmit(formName) {
      const form1 = this.$refs.addFormInline;

      Promise.all([form1.validate()])
        .then(results => {
          const [form1Valid] = results;
          if (form1Valid) {
            this.loading = true;  
          addParts({poke_id:this.introd[3].id,picture:this.addFormInline.picture})
              .then(res => {
                this.loading = false; 
                this.$store.dispatch('fetchData')
                  .then(data => {
                    this.addFormInline.picture = ''
                    this.$store.dispatch('updataPoke') 
                  })  
                  .catch(error => {
                    console.error(error);  
                  });
                this.succe(res.message);
              })
              .catch(err => {
                this.loading = false;  
                console.log(err.responseText);
              })
        } else {
          this.error('error submit!!');
        }
      });
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
      this.formInline[0].timeLimit = newValue[3].timeLimit;
      this.formInline[0].gold = newValue[3].gold;
      this.formInline[0].experience = newValue[3].experience;
      this.formInline[1].diamond = newValue[3].diamond;
      this.formInline[1].prop = newValue[3].prop;
      this.formInline[1].stamina = newValue[3].stamina;
    },
    handleModelGroupDataChange(newModelGroupData) {
      this.ModelGroupData = newModelGroupData  
      // 在这里处理变化  
    }  
  },
};
</script>

<style lang="scss" scoped>
.parts{
  width: 100%;
  box-sizing: border-box; 
  overflow: auto; 

  div{
    .poke-demo-form-inline{
      .el-form-item{
      margin: 0 !important;
      }}   } 
}
</style>