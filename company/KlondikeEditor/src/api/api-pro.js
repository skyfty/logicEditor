import {getData,Get,postData,Delet,Put,postDatas} from './api'

// // 获取关卡包数据
// export const getdata = ()=>getData('http://localhost:3021/src/views/apl/data.php')
// // 添加新的关卡
// export const postdata = ()=>postData('http://localhost:3021/src/views/apl/add.php')
// export const post = (id)=>postDatas('http://localhost:3021/src/views/apl/levelAdd.php',id)
// // 删除关卡
// export const delet = (id)=>Delet('http://localhost:3021/src/views/apl/del.php',id)

// // 编辑名
// export const put = (id)=>Put('http://localhost:3021/src/views/apl/rename.php',id)
// // 排序
// export const Drag = (id)=>Get('http://localhost:3021/src/views/apl/Drag.php',id)

// export const EditData = (id)=>Get('http://localhost:3021/src/views/apl/EditData.php',id)
// 获取
export const GetData = (id)=>Get('http://8.141.89.131/KlondikeEditor/GetData.php',id)
export const removeData = (id)=>Get('http://8.141.89.131/KlondikeEditor/removeData.php',id)
export const AddData = (id)=>Get('http://8.141.89.131/KlondikeEditor/AddData.php',id)
export const EditData = (id)=>Get('http://8.141.89.131/KlondikeEditor/EditData.php',id)


export const getdata = ()=>getData('http://8.141.89.131/KlondikeEditor/index/data.php')
// 添加新的关卡
export const postdata = ()=>postData('http://8.141.89.131/KlondikeEditor/index/add.php')
export const post = (id)=>postDatas('http://8.141.89.131/KlondikeEditor/index/levelAdd.php',id)
// 删除关卡
export const delet = (id)=>Delet('http://8.141.89.131/KlondikeEditor/index/del.php',id)

// 编辑名
export const put = (id)=>Put('http://8.141.89.131/KlondikeEditor/index/rename.php',id)
// 排序
export const Drag = (id)=>Get('http://8.141.89.131/KlondikeEditor/index/Drag.php',id)
export const cutGroup = (id)=>Get('http://8.141.89.131/KlondikeEditor/index/cutGroup.php',id)