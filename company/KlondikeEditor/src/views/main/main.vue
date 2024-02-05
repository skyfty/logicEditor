<template>
  <div class="nav">
    <div>
      <el-button @click="show1()" type="primary" round>{{cards = show==false?'显示底牌':'隐藏底牌'}}</el-button>
      <h1>需要&nbsp;{{ box.moves }}&nbsp;步</h1>
      <div>
        <span style="margin-bottom: 10px;">牌组-ID：{{ box.deckGameId }}</span>
        <span>难度等级：{{ $store.state.data.filter(e => e.id == this.introd[1].difficultyLevel)[0].difficulty }}</span>
      </div>
    </div>
    <div style="padding: 0 80px;display: flex;justify-content: space-between;">
      <img v-show="!show" src="../../assets/Card/CardBackBase001.png" width="120" alt="">
      <!-- 隐藏显示 -->
      <div v-show="show" style="width: 40%;height: 166px;display: flex;flex-wrap: nowrap;">
        <span v-for="(stock1,indexs) in underpans2" :key="indexs"
         style="float: left;box-shadow:0px 3px 5px 3px #dfdcdc;"
        :style="{transform:`translate3d(${-63.5*indexs+'%'},0,0)`}"
        ><img :src= "require('../../assets/Card/CardFront'+stock1.dd+stock1.cc)" width="120px" alt=""></span>
      </div>
      <div  class="empty" v-if="cards = show == false">
        <span v-for="(item,index) in data1" :key="index">
          {{item}}
          </span> 
        </div>
    </div>
    <div style="position: absolute; top: 43%;left: 0;width: 100%;padding: 0 8%;
        display: flex;flex-direction: row-reverse;box-sizing: border-box;
        justify-content: space-around;
        height: 350px;;overflow: hidden;">
      <span class="span" v-for="(item1,index1) in underpans1" :key="index1">
        <ul 
          v-for="(item2,index2) in item1" :key="index2" 
          :style="{display: 'block',
          transform:`translate3d(0,${-140*index2+'px'},0)`
          }">
          <li><img :src= "require('../../assets/Card/CardFront'+item2.dd+item2.cc)" width="120px" alt=""></li>
        </ul>
      </span>
    </div>
    <div>
      <h2>本局额外奖励</h2>
      <span>金币:</span><i>{{ this.introd[1].gold }}</i> <br />
      <span>经验:</span><i>{{ this.introd[1].experience }}</i><br />
      <span>钻石:</span><i>{{ this.introd[1].diamond }}</i><br />
      <span>道具:</span><i>{{ this.introd[1].prop }}</i><br />
      <span>体力:</span><i>{{ this.introd[1].stamina }}</i><br />
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
export default {
  name: 'GameIndex',
  props:['introd'],
  data() {
    return {
      a:this.introd[1],
      // 隐藏牌数
      show:false,
      // 显示
      cards:'显示底牌',
      // 底部
      underpans1:[],
      underpans2:[],
      aa:{cc:'',dd:''},
      box:'',
      data1:''
    };
  },

  watch: {
    introd(newValue) {
      this.underpans1=[],
      this.underpans2=[],
      this.a = newValue[1];
      this.nameData()
    }
  },

  mounted() {
    this.nameData()
    this.$message({
          message: '恭喜你，打开 '+this.box.name+'  关卡',
          type: 'success',
          duration: 1000
        });
  },

  methods: {
    show1(){
      this.show = !this.show
    },
    sol(a){
      if(a.length == 2){
              switch(a.substr(0,1)){
                case "J":
                  this.aa.cc = '11.png'
                  break;
                case 'Q':
                this.aa.cc = '12.png'
                  break;
                case "K":
                this.aa.cc = '13.png'
                  break;
                case "A":
                this.aa.cc = '0' + '1.png'
                  break;
                default:
                this.aa.cc = '0' + a.substr(0,1) +'.png'
                break;
              }
            }else{
              this.aa.cc = a.substr(0,2) +'.png'
            }
    },
    sols(a){
      if(a.length == 2){
              switch(a.substr(1,1)){
                case "C":
                  this.aa.dd = 'Club'
                  break;
                case 'H':
                this.aa.dd = 'Heart'
                  break;
                case "D":
                this.aa.dd = 'Diamond'
                  break;
                default:
                this.aa.dd = 'Spade'
                break;
              }
            }else{
              switch(a.substr(2,1)){
                case "C":
                  this.aa.dd = 'Club'
                  break;
                case 'H':
                this.aa.dd = 'Heart'
                  break;
                case "D":
                this.aa.dd = 'Diamond'
                  break;
                default:
                this.aa.dd = 'Spade'
                break;
              }
            }
    },
    nameData(){
        this.box = this.a
        this.data1 = this.box.game.foundations
          this.box.game['tableauPiles'].forEach(e => {
          let sa = []
          e['cards'].forEach(a => {
            this.sol(a)
            this.sols(a)
            sa.push(_.cloneDeep(this.aa))
          })
          this.underpans1.push(_.cloneDeep(sa))
        });

        // 隐藏的牌
          let sas = []
          this.box.game.stock.forEach(a => {
              this.sol(a)
              this.sols(a)
              sas.push(_.cloneDeep(this.aa))
            })
          this.underpans2 = _.cloneDeep(sas)
    },
  },

};
</script>

<style lang="scss" scoped>
  .nav{
    width: 100%;
    height: 100%;
    position: relative;
    .span{
      float: left;
      text-align: center;
      box-shadow:0px 3px 5px 3px #dfdcdc;
    }
    .empty{
      span{
        display: block;
        float: right;
        margin-right: 20px;
        box-shadow:0px 0px 5px 3px #8d8d8d;
      }
    }
    
    div:nth-child(1){
      line-height: 60px;
      text-align: center;
      position: relative;
      margin: auto;
      margin-bottom: 30px;
      font-size: 22px;
      background: rgba(121, 244, 252, 0.3);
      button{
        position: absolute;
        left: 10px;
        top: 10px;
      }
      div{
        position: absolute;
        right: 10px;
        top: 12%;
        // display: flex;
        // flex-direction:row;
        span{
          display: block;
          width: 170px;
          height: 20px;
          line-height: 20px;

        }
      }
    }
    div:nth-child(2),div:nth-child(3){
      span{
        display: block;
        width: 120px;
        height: 160px;
      }
    }
    div:nth-child(4){
      position: absolute;
      bottom: 0;
      left: 0;
      display: flex;
      width: 280px;
      line-height: 35px;
      flex-wrap: wrap;
      padding:10px 20px;
      font-size: 20px;
      color: rgba($color: #ccc, $alpha: 0.5);
      h2{
        width: 300px;
      }
      span{
        width: 60px;
        font-size: 23px;
        font-weight: 600;
        color: rgba($color: #ccc, $alpha: 0.5);
      }
      i{
        width: 60px;
      }
    }
  }

</style>