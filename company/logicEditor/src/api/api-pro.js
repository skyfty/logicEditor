import {getData,Get,postData,Delet,Put,postDatas} from './api'

//  let url = 'http://8.141.89.131/'
 let url = 'http://localhost:3021/' 
// 获取
export const getdata = ()=>getData(url+'logicEditor/index/data.php')
export const GetData = (id)=>Get(url+'logicEditor/GetData.php',id)
export const GetModelGroup = (id)=>Get(url+'logicEditor/getData/GetModelGroup.php',id)
export const matchingModel = (id)=>Get(url+'logicEditor/getData/matchingModel.php',id)

// 删除

export const removeData = (id)=>Get(url+'logicEditor/removeData.php',id)
export const delet = (id)=>Delet(url+'logicEditor/index/del.php',id)

// 修改 

export const EditData = (id)=>Get(url+'logicEditor/EditData.php',id)

// 添加

export const AddData = (id)=>Get(url+'logicEditor/AddData.php',id)
// 创建关卡包
export const addklondike = (id)=>Get(url+'logicEditor/group/addklondike.php',id)
export const addPoke = (id)=>Get(url+'logicEditor/group/addPoke.php',id)
export const addParts = (id)=>Get(url+'logicEditor/group/addParts.php',id)
// 模型组
export const ModelGroup = (id)=>Get(url+'logicEditor/group/ModelGroup.php',id)

// 添加新的关卡
export const postdata = ()=>postData(url+'logicEditor/index/add.php')
export const post = (id)=>postDatas(url+'logicEditor/index/levelAdd.php',id)
// 删除关卡

// 编辑名
export const put = (id)=>Put(url+'logicEditor/index/rename.php',id)
// 排序
export const Drag = (id)=>Get(url+'logicEditor/index/Drag.php',id)
export const cutGroup = (id)=>Get(url+'logicEditor/index/cutGroup.php',id)

// 分包
export const subpackage = (id)=>Get(url+'logicEditor/group/subpackage.php',id)