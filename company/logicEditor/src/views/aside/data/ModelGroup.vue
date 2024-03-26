<template>
  <div class="parts">
    <!-- 模型组 -->
    <el-divider content-position="left">{{ ModelGroupDivider.label }}</el-divider>  
    <el-form label-width="35px" :rules="ModelGroupRules" :ref="ModelGroupDivider.ref" :model="ModelGroupFormInline" class="demoFormInline">  
      <el-row class="row-bg" :type="updateSize?'flex':''" :justify="updateSize?'space-around':''" >  
        <el-col v-for="(item,index) in ModelGroupForm" :key="index">
          <el-form-item :label="item.label" :prop="item.rules" size="small"> 
            <el-tooltip class="item" effect="light" size="mini" :disabled="item.tooltip == ''" :content="item.tooltip" placement="bottom"> 
              <el-input type="number" v-model="ModelGroupFormInline[item.model]" :placeholder="item.label" v-if="item.type == 'number'"></el-input>  
              <el-select v-model="ModelGroupFormInline[item.model]" :placeholder="item.label" @click.native="pictureData()" v-else-if="item.type == 'select'">  
                <el-option v-for="(option, optionIndex) in item.data" :key="optionIndex" :value="option.filepath">{{ option.name }}</el-option>  
              </el-select> 
            </el-tooltip> 
          </el-form-item>  
        </el-col>  
        <el-form-item>  
          <el-button type="primary" @click="SubmitModelGroupForm" :loading="ModelGroupLoading">{{  ModelGroupLoading ? '正在创建 ': '创建'  }}</el-button>  
        </el-form-item>  
      </el-row>  
    </el-form>
    <el-divider content-position="left">卡组列表</el-divider> 
    <div class="introdIds" ref="introdId" style="width: 100%;">
      <div style="display: flex;overflow: auto hidden;">
        <el-badge
          :value="items.amount" style="width: auto;text-align: center;margin: 10px 30px 0px 0; " 
          class="item, tags"  v-for="(items,index) in ModelGroupData" :key="index">
          <el-tooltip class="item" effect="light" size="mini" :content="'模型数量：'+items.amount" placement="bottom">
            <el-tag class="" type="" effect="dark" closable @close="handleClose(items)"> 
              {{ items.picture.split('/')[items.picture.split('/').length - 1].split('.')[0] }} 
            </el-tag>
          </el-tooltip>
        </el-badge>  
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'
import {removeData, GetData, GetModelGroup, ModelGroup} from '../../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd','navigation','screenWidth','screenHeight'],
  data() {
    return {
      // 设置卡组
      ModelGroupFormInline:{ picture: '',amount: '',},
      ModelGroupDivider:{label:'添加模型卡组', ref:'ModelGroupFormInline'},
      ModelGroupForm:[
        {label: 'picture', model:'picture', rules:'picture',data:'',type:'select',tooltip:'卡组内模型'},
        {label: 'amount', model:'amount', rules:'amount',data:'',type:'number',tooltip:'模型数量'},
      ],
      ModelGroupRules:{ 
        picture: [ { required: true, message: 'picture'} ],
        amount: [ { required: true, message: 'amount'} ] 
      },
      ModelGroupLoading: false,
      updateSize: true, 
      ModelGroupData:[],
      ModelGroupKey:['id', 'picture', 'amount'],
    };
  },

  watch: {
    introd(newValue){
      this.ModelGroup()
    },
    ModelGroupData(newValue){
      this.$emit('handleModelGroupDataChange', newValue);  
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
    this.ModelGroup()
    if(this.screenWidth > 1400){
        this.updateSize = true
      }else{
        this.updateSize = false
      }
  },

  methods: {
    pictureData(){
      GetData({name:'parts'})
        .then(res => {
          this.ModelGroupForm[0].data = res;
        })
        .catch(err => {
          console.log('Error fetching images:', err);
        })
    },
    ModelGroup(){
      GetModelGroup({ children:'modelgroup', poke_id: this.introd[3].id })
        .then(res => {
          this.ModelGroupData = res;
        })
        .catch(err => {
          console.log('Error fetching images:', err);
        })
    },
    SubmitModelGroupForm(formName) {
      const form1 = this.$refs.ModelGroupFormInline;

      Promise.all([form1.validate()])
        .then(results => {
          const [form1Valid] = results;
          if (form1Valid) {
            const ModelGroupFormInline = this.ModelGroupFormInline
            ModelGroup({
              picture:ModelGroupFormInline.picture,  
              poke_id:this.introd[3].id, 
              amount:ModelGroupFormInline.amount,
            })
              .then(res => {
                this.ModelGroup()
                this.succe(res.success);
              })
              .catch(err => {
                console.log('Error fetching images:', err);
              })
            this.ModelGroupLoading = false;  
        } else {
          this.error('error submit!!');
        }
      });
    },
    handleClose(data) {
        this.$confirm('此操作将永久删除卡组模型, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
            removeData({id:data.id,children:'modelgroup'})
              .then(res => { 
                this.ModelGroup()
                this.$emit('updateData',)
                this.succe(res.message);
              })
              .catch(err => {
                this.ModelGroup()
                this.succe('错误'+err.responseText);
              })
        }).catch(() => {
            this.error( '已取消删除' );          
        });
    },
    // 成功提示
    succe(name) {
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
  },
};
</script>

<style lang="scss" scoped>
.parts{
  width: 100%;
  box-sizing: border-box; 
  overflow: auto; 
}
</style>