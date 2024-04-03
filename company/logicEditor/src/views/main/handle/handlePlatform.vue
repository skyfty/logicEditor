<template>
  <div class="handlePlatform">
    <el-collapse v-model="activeName" accordion>
      <el-collapse-item :title="divider[dividerIndex].label" :name="dividerIndex" v-for="(dividerLabel, dividerIndex) in form" :key="dividerIndex">
        <div v-for="(item, index) in dividerLabel" :key="index">
          <el-row class="row-bg" v-if="formInline[1][0].Projection != 'OrthographicCamera' ? ( item.label != 'Size' && item.label != 'Zoom' ) : item.label != 'Field of View'">
            <el-col :span="7">
              <div style="margin-top: 4px;">{{ item.label }}</div>
            </el-col>
            <el-col :span="17" style="display: flex; justify-content: space-between; flex-direction: row-reverse;position: relative;">
              <el-form label-width="20px" label-position="left" :ref="divider[dividerIndex].ref" :model="formInline[dividerIndex][index]" class="demoFormInline">
                <el-row class="row-bg" :type="item.data.length <= 3 ? 'flex' : ''" :justify="item.data.length <= 3 ? 'space-around' : ''">
                  <el-col v-for="(items,indexs) in item.data" :key="indexs">
                    <el-form-item :label="items.type == 's'? '': items.label" size="small">
                      <el-tooltip class="item" effect="light" size="mini" :disabled="items.tooltip == ''" :content="items.tooltip" placement="bottom">
                        <el-input type="number" style="float: right !important;" v-model="formInline[dividerIndex][index][items.model]" :placeholder="items.label" v-if="items.type == 'number'"></el-input>
                        <el-switch v-model="formInline[dividerIndex][index][items.model]" style="float: right !important;margin-top: 6px;" :placeholder="items.label" v-else-if="items.type == 'switch'"></el-switch> 
                        <el-radio v-model="formInline[dividerIndex][index][items.model]" v-else-if="items.type == 's'" border :label="items.label"></el-radio>
                        <el-slider v-model="formInline[dividerIndex][index][items.model]" :min="1" v-else-if="items.type == 'progress'" style="width: 260px !important;" show-input input-size="mini"></el-slider>
                        <el-select v-model="formInline[dividerIndex][index][items.model]" style="float: right !important;" :placeholder="items.label" v-else-if="items.type == 'select'">
                          <el-option class="options" v-for="(items1,indexs1) in items.data" :key="indexs1" :value="items1.filepath">{{ items1.name }}</el-option>
                        </el-select>
                      </el-tooltip>
                    </el-form-item>
                  </el-col>
                </el-row>
              </el-form>
            </el-col>
          </el-row>
        </div>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { EditData } from '../../../api/api-pro'
export default {
  name: 'LogicEditorHandlePlatform',
  props:['introd', 'pokeData', 'navigation'],
  data() {
    return {
      activeName: 1,
      // 关卡属性表单
      formInline: [
        [
          { x: this.$store.state.pokeData[3].PositionX, y: this.$store.state.pokeData[3].PositionY, z: this.$store.state.pokeData[3].PositionZ },
          { x: this.$store.state.pokeData[3].RotationX, y: this.$store.state.pokeData[3].RotationY, z: this.$store.state.pokeData[3].RotationZ },
          { x: this.$store.state.pokeData[3].ScaleX, y: this.$store.state.pokeData[3].ScaleY, z: this.$store.state.pokeData[3].ScaleZ },
        ],
        [
          { Projection: this.$store.state.pokeData[3].Projection, },
          { Size: this.$store.state.pokeData[3].Size },
          { FieldOfView: this.$store.state.pokeData[3].FieldOfView - 0 },
          { Zoom: this.$store.state.pokeData[3].zoom - 0 },
          { Near: this.$store.state.pokeData[3].Near, Far: this.$store.state.pokeData[3].Far },
        ],
        [
          { MeshRenderer: true },
          { PlaneGeometry: true },
          { translate: 'translate' },
        ]
      ],
      divider: [
        { label: 'Transform', ref: 'Transform', tooltip: '相机位置' },
        { label: 'Camera', ref: 'Camera', tooltip: '相机' },
        { label: 'Renderer', ref: 'Renderer', tooltip: '网格渲染器' },
      ], 
      form: [
        [
          {
            label: 'Position',
            data: [
              { label: 'X', model: 'x', rules: 'x', data: '', type: 'number', tooltip: 'X坐标' },
              { label: 'Y', model: 'y', rules: 'y', data: '', type: 'number', tooltip: 'Y坐标' },
              { label: 'Z', model: 'z', rules: 'z', data: '', type: 'number', tooltip: 'Z坐标' },
            ]
          },
          {
            label: 'Rotation',
            data: [
              { label: 'X', model: 'x', rules: 'x', data: '', type: 'number', tooltip: 'X坐标' },
              { label: 'Y', model: 'y', rules: 'y', data: '', type: 'number', tooltip: 'Y坐标' },
              { label: 'Z', model: 'z', rules: 'z', data: '', type: 'number', tooltip: 'Z坐标' },
            ]
          },
          {
           label: 'Scale',
            data: [
              { label: 'X', model: 'x', rules: 'x', data: '', type: 'number', tooltip: 'X轴' },
              { label: 'Y', model: 'y', rules: 'y', data: '', type: 'number', tooltip: 'Y轴' },
              { label: 'Z', model: 'z', rules: 'z', data: '', type: 'number', tooltip: 'Z轴' },
            ]
          },
        ],
        [
          {
            label: 'Projection',
            data: [
              { label: '', model: 'Projection', rules: 'Projection', data: [
                                        { filepath:'OrthographicCamera' ,name:'OrthographicCamera' },
                                        { filepath:'PerspectiveCamera' ,name:'PerspectiveCamera' },
                                      ], type: 'select', tooltip: '相机模式' 
              },
            ]
          },
          {
            label: 'Size',
            data: [
              { label: '', model: 'Size', rules: 'Size', data: '', type: 'number', tooltip: '相机尺寸' },
            ]
          },
          {
            label: 'Field of View',
            data: [
              { label: '', model: 'FieldOfView', rules: 'FieldOfView', data: '', type: 'progress', tooltip: '视野' },
            ]
          },
          {
            label: 'Zoom',
            data: [
              { label: '', model: 'Zoom', rules: 'Zoom', data: '', type: 'progress', tooltip: '缩放' },
            ]
          },
          {
            label: 'ClippingPlanes',
            data: [
              { label: 'Near', model: 'Near', rules: 'Near', data: '', type: 'number', tooltip: '近截面' },
              { label: 'Far', model: 'Far', rules: 'Far', data: '', type: 'number', tooltip: '远截面' },
            ]
          },
        ],
        [
          {
            label: 'MeshRenderer',
            data: [
              { label: '', model: 'MeshRenderer', rules: 'MeshRenderer', data: '', type: 'switch', tooltip: '网格渲染器' },
            ]
          },
          {
            label: 'PlaneGeometry',
            data: [
              { label: '', model: 'PlaneGeometry', rules: 'PlaneGeometry', data: '', type: 'switch', tooltip: '网格渲染器' },
            ]
          },
          {
            label: 'translate',
            data: [
              { label: 'translate', model: 'translate', rules: 'translate', data: '', type: 's', tooltip: '平移' },
              { label: 'rotate', model: 'translate', rules: 'translate', data: '', type: 's', tooltip: '旋转' },
              { label: 'scale', model: 'translate', rules: 'translate', data: '', type: 's', tooltip: '缩放' },
            ]
          },
        ],
      ],
    };
  },

  watch:{ 
    '$store.state.pokeData'(newValue, oldValue){
      this.formCopy(newValue)
    },
    formInline: {  
      handler(newValue) {
          const form1 = this.$refs.Camera[0];
          const form2 = this.$refs.Transform[0];
          const form3 = this.$refs.Renderer[0];

          Promise.all([form1.validate(), form2.validate(), form3.validate()])
            .then(results => {
              const [form1Valid, form2Valid, form3Valid] = results;
              if (form1Valid && form2Valid && form3Valid) {
                EditData({children:'pokesArguments',id:this.$store.state.pokeData[3].id,data:JSON.stringify(newValue)})
                  .then(res => {
                    this.$store.dispatch('fetchData')
                    .then(data => {
                            this.succe(res.message);
                            this.$emit('cameraChange',newValue)
                            this.$emit('toggleGridPlatformVisibility',this.formInline[2][0].MeshRenderer ? true : false);
                            this.$emit('toggleWallVisibility',this.formInline[2][1].PlaneGeometry ? true : false);
                    })  
                    .catch(error => {  
                      // 处理错误  
                      console.error(error);  
                    });
                  })
            } else {
              this.error('error submit!!');
            }
          });
      },  
      deep: true, // 深度监听数组内部对象的变化    
    },  
  },

  mounted() {
    // this.formCopy(this.introd)
  },

  methods: {
    formCopy(newValue){
      this.formInline = [
        [
          { x: newValue[3].PositionX, y: newValue[3].PositionY, z: newValue[3].PositionZ },
          { x: newValue[3].RotationX, y: newValue[3].RotationY, z: newValue[3].RotationZ },
          { x: newValue[3].ScaleX, y: newValue[3].ScaleY, z: newValue[3].ScaleZ },
        ],
        [
          { Projection: newValue[3].Projection, },
          { Size: newValue[3].Size },
          { FieldOfView: newValue[3].FieldOfView - 0 },
          { Zoom: newValue[3].zoom - 0 },
          { Near: newValue[3].Near, Far: newValue[3].Far },
        ],
        [
          { MeshRenderer: false },
          { PlaneGeometry: false },
          { translate: 'translate' },
        ]
      ]
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
  },
};
</script>

<style scoped>
.handlePlatform{
  width: 370px;
  height: 200px;
  position: absolute;
  top: 0;
  right: 0;
  margin: 10px 20px 0 0;
}
</style>