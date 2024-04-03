<template>
  <div>
    <button @click="processImage">处理图像：</button>&nbsp;<span>QQB_DefaultMaterial_AlbedoTransparency.png</span><br><br>
    <img id="images" src="../../../../logicEditor/resource/parts/QQB_DefaultMaterial_AlbedoTransparency.png" alt="Image" width="100" /><br>
    <h3>处理结果：</h3><br>
    <canvas id="output"></canvas>
  </div>
</template>

<script>
import cv from 'opencv.js';

export default {
  name: 'LogicEditorImageDispose',

  mounted() {
    cv.onRuntimeInitialized = () => {
      this.processImage();
    };
  },

  methods: {
    processImage() {
      const imgElement = document.getElementById('images');
      const src = cv.imread(imgElement);
      const gray = new cv.Mat();
      const edges = new cv.Mat();
      const threshold = new cv.Mat();
      const contours = new cv.MatVector();
      const hierarchy = new cv.Mat();

      cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
      cv.Canny(gray, edges, 50, 150);
      cv.threshold(edges, threshold, 128, 255, cv.THRESH_BINARY);

      cv.findContours(
        threshold,
        contours,
        hierarchy,
        cv.RETR_EXTERNAL,
        cv.CHAIN_APPROX_SIMPLE
      );

      const canvas = document.getElementById('output');
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      for (let i = 0; i < contours.size(); i++) {
        const color = new cv.Scalar(0, 255, 0); // 绿色

        cv.drawContours(src, contours, i, color, 2, cv.LINE_8, hierarchy, 0);
      } 
      cv.imshow('output', src);

      src.delete();
      gray.delete();
      edges.delete();
      threshold.delete();
      contours.delete();
      hierarchy.delete();
    }
  }
};
</script>

<style lang="scss" scoped>
img {  
    all: unset; /* 清除所有继承和非继承的样式 */  
    /* 或者手动重置每一个你想要清除的样式属性 */  
}
#images{
  width: 150px;
  height: 150px;
}
</style>