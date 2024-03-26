import axios from "axios";
import $ from 'jquery';

// 关卡包全部数据
const getData = (url)=>{
  return new Promise((resolve,reject) => {
    axios.get(url)
        .then(res=>{
          resolve(res);
        })
        .catch(err=>{ 
          reject(err);
        })
  })
}

// 关卡包全部数据
const Get = (url,datas)=>{
  return new Promise((resolve,reject) => {
    $.ajax({
      url,
      type: 'POST',
      data:datas,
      success: response => {
        resolve(response)
      },
      error: error => {
        reject(error);
      }
    })
  })
}

// 新增关卡包
const postData = (url)=>{
    return new Promise((resolve,reject) => {
      axios.get(url)
        .then(res=>{
          resolve(res);
        })
        .catch(err=>{ 
          reject(err);
        })
  })
}

// 新增关卡
const postDatas = (url,id)=>{
  return new Promise((resolve,reject) => {
      $.ajax({
        url,
        type: 'POST',
        data:id,
        success: response => {
          resolve(response)
        },
        error: error => {
          reject(error);
        }
      })
    })
}

// 删除关卡包
const Delet = (url,ids)=>{
  return new Promise((resolve,reject) => {
    $.ajax({
      url,
      type: 'POST',
      data:ids,
      success: response => {
        resolve(response)
      },
      error: error => {
        reject(error);
      }
    });
  })
}

// 编辑关卡名
const Put = (url,id)=>{
  return new Promise((resolve,reject) => {
    $.ajax({
      url,
      type: 'POST',
      data:id,
      success: response => {
        resolve(response)
      },
      error: error => {
        reject(error);
      }
    });
  })
}


export {
  getData,
  Get,
  postData,
  postDatas,
  Delet,
  Put
}