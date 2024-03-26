<template>
  <div class="nav">
    <div class="introdId" ref="introdId">
      <el-tooltip class="item" effect="light" :content="'ID：'+introd[1].id" placement="bottom" v-for="(item,index) in introd.slice(1)" :key="index">
        <el-tag class="tag" :type="index == 0 ? '' : index == 1 ? 'success' : 'danger'" effect="dark" v-if="index != 3"> {{item.name+'-ID：'+item.id}} </el-tag>
      </el-tooltip>
    </div>
    <el-divider content-position="left">零件数量</el-divider>
    <div class="introdIds" ref="introdId" style="width: 100%;">
      <div style="display: flex;overflow: auto hidden;">
        <el-badge
          :value="items.amount" style="width: auto;text-align: center;margin: 10px 10px -10px 0; " 
          class="item, tags"  v-for="(items,index) in ModelGroupData" :key="index">
          <el-tooltip class="item" effect="light" size="mini" :content="'零件数量：'+items.amount" placement="bottom">
            <el-tag class="" type="" effect="dark"> 
              {{ items.name }} 
            </el-tag>
          </el-tooltip>&nbsp;&nbsp;&nbsp; 
        </el-badge>
      </div>
    </div>
    <pokeForm 
        @updateData="updateData" @introduction="introduction"  :introd="introd"
        @updateParts="updateParts" @ModelGroupDataChange="ModelGroupDataChange"
        :screenWidth="screenWidth" :screenHeight="screenHeight" 
        :pokeData="pokeData" @updataPoke="updataPoke" :navigation="navigation" v-if="introd[0] == 'poke'">
    </pokeForm>
    <partsForm 
        @updateData="updateData" @introduction="introduction" @updateParts="updateParts"
        :screenWidth="screenWidth" :screenHeight="screenHeight" :ModelGroup="ModelGroup"
        :introd="introd" :partsData="partsData" :navigation="navigation" v-if="introd[0] == 'pokeParts'">
    </partsForm>
    <el-divider style="bottom: 0;">到达底部</el-divider>
  </div>
</template>

<script>
import $ from 'jquery'
import partsForm from './data/parts.vue'
import pokeForm from './data/poke.vue'
import {EditData} from '../../api/api-pro'
export default {
  name: 'KlondikeEditorAside',
  props:['introd','navigation','partsData','pokeData','partsShow','screenWidth','screenHeight'],
  data() {
    return {
      ModelGroupData:['',''],
      ModelGroup:[]
    };
  },

  components:{
    partsForm,pokeForm,
  },  

  watch: {
    introd(newValue){
      // console.log(newValue);
    },
    screenHeight(newValue){
      // console.log(newValue);
    },
    screenWidth(newValue){
      // console.log(newValue);
      this.reactiveTag(newValue);
    }
  },

  mounted() {
    this.reactiveTag(this.screenWidth);
  },
 
  methods: { 
    reactiveTag(newValue){
      let csa = (width,textAlign,display,justifyContent,flexWrap) => {
        $('.tag').each(function() {
          $(this).css({ width, textAlign});  
        }); 
        $('.introdId').css({ display, justifyContent, flexWrap, cursor: 'pointer' })
      }
      if(newValue > 1400){
        csa('auto','center','flex','space-around', 'wrap')
      }else if(newValue <= 1400 && newValue > 700){
        csa('100%','center','','')
      }else if(newValue <= 700){
        csa('100%','center','','')
      }
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
    
    updateData(){
        this.$emit('updateData',)
    },
    updataPoke(index){
        this.$emit('updataPoke',index)
    },
    updateParts(index){
        this.$emit('updateParts',index)
    },
    introduction(index){ 
        this.$emit('introduction',index)
    },
    ModelGroupDataChange(newValue){
      this.ModelGroupData = []
      this.ModelGroup = newValue
      newValue.forEach(element => {
        let name = element.picture.split('/')[element.picture.split('/').length - 1].split('.')[0]
        if(this.introd[3].parts){
          this.ModelGroupData.push({name: name,amount: this.introd[3].parts.filter(e => e.picture == element.picture).length})
        }else{
          this.ModelGroupData.push({name: name,amount: 0})
        }
      }); 
    }
  },
};
</script>

<style lang="scss" scoped>
.nav{
  width: 100%;
  box-sizing: border-box;
  border-left: 1px solid #ccc;height: 100%;
  padding: 10px 10px;
  overflow: auto;
  .introdId{
    display: flex;
    justify-content: space-around;
    cursor: pointer;
    .tag{
        width: auto;
        text-align: center;
    }
  }
}
</style>