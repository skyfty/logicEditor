<template>
  <el-dialog
    :title="title"
    :visible.sync="dialogVisible"
    :close-on-click-modal="false"
  >
    <slot></slot>
    <span slot="footer" class="dialog-footer">
      <el-button @click="dialogVisible = false">关闭</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: 'GlobalDialog',
  props: {
    title: String,
  },
  data() {
    return {
      dialogVisible: false,
    };
  },
};
</script>

<style scoped>
.dialog-footer {
  text-align: right;
}
</style>