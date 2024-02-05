module.exports = {
  // 基本路径
  publicPath:"./",  // 可以设置成相对路径，这样所有的资源都会被链接为相对路径，打出来的包可以被部署在任意路径
  outputDir:"dist",  //打包时生成的生产环境构建文件的目录
  assetsDir: 'public',  // 放置生成的静态资源 (js、css、img、fonts) 的 (相对于 outputDir 的) 目录
  pages: {
    index: {
      // page 的入口
      entry: 'src/main.js',
      // 模板来源
      template: 'public/index.html',
      // 在 dist/index.html 的输出
      filename: 'index.html',
      // 当使用 title 选项时，
      // template 中的 title 标签需要是 <title><%= htmlWebpackPlugin.options.title %></title>
      title: '蜘蛛纸牌', // 改的就是网页的标题 同在HTML中的title一样
    },
  },
  devServer: {
    proxy: {
      '/x': {
        target: 'http://localhost:3005/api/data',
        ws: true,
        changeOrigin: true,
          pathRewrite:{
            '^/x':''
          }
      },
      // 菜单栏数据
      '/z': {
        target: './src/api/data.json',
        ws: true,
        changeOrigin: true,
          pathRewrite:{
            '^/z':''
          }
      },
    }
  }
}