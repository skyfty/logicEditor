import {getData,Get,postData,Delet,Put,postDatas} from './api'
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

// 创建关卡包
export const addklondike = (id)=>Get('http://8.141.89.131/KlondikeEditor/group/addklondike.php',id)
// 生成关卡包卡牌
export const deckGame = (id)=>Get('http://8.141.89.131/KlondikeEditor/group/deckGame.php',id)
// 重置关卡包
export const deckGamePost = (id)=>Get('http://8.141.89.131/KlondikeEditor/group/deckGamePost.php',id)
// 分包
export const subpackage = (id)=>Get('http://8.141.89.131/KlondikeEditor/group/subpackage.php',id)