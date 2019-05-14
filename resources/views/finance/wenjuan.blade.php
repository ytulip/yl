<!DOCTYPE HTML>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="/css/mui.css?v={{env('VERSION')}}"/>
<link rel="stylesheet" href="/css/style.css?v={{env('VERSION')}}"/>
<link rel="stylesheet" href="/admin/css/font-awesome.css?v={{env('VERSION')}}"/>
<style>

    body{padding: 0 24px;}

    .cus-row > [class*='cus-row-col-']
    {
        display: inline-block;
    }

    h2{font-size:18px;line-height: 28px; padding: 8px;}
    h3{font-size: 16px;line-height: 24px;padding: 6px;}

    .chosen-item{display: inline-block;font-size: 14px;margin-right: 24px;min-width: 120px;line-height: 21px;}
</style>
<body>
        <h1 class="t-al-c" style="padding: 30px;">个人理财客户风险评估问卷</h1>

        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-3 t-al-c"><span>证件姓名：{{$user->real_name}}</span></div>
            <div class="cus-row-col-3 t-al-c"><span>联系方式：{{$user->phone}}</span></div>
            <div class="cus-row-col-3 t-al-c"><span>证件类型：身份证</span></div>
            <div class="cus-row-col-3 t-al-c"><span>证件号码：{{$user->id_card}}</span></div>
        </div>


        <div style="font-size: 14px;font-weight: bold;margin-top: 20px;">
            重要提示：<br/>
            1、	首次购买本行任何理财产品前，请填写本问卷，并每年进行重新评估，问卷有效期1年。当发生可能影响您自身风险承受能力的情形时，请您在再次购买我行理财产品时主动要求重新进行评估。<br/>
            2、	本问卷旨在了解您的财务状况、投资经验、投资风格、风险偏好和风险承受能力等，借此协助您选择合适的理财产品类别，以达到您的投资目标。<br/>
            3、	投资理财产品需要承担各类风险，如本金兑付风险、市场风险、流动性风险、汇率风险、信用风险、利率风险、赋税风险、产品复杂度风险等，可能遭受本金损失。<br/>
            4、	以下12个问题请选择唯一选项，不可多选。本风险评估问卷的准确性视乎您所填写的答案而定，  请您客观仔细填写，感谢您的配合！<br/>
        </div>

        <form id="data_form">

        <h2>一、财务状况</h2>

        <h3>1.您的年龄是？</h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box1" value="A"/> A.18-25 </div>      <div class="chosen-item"> <input type="radio"  name="box1" value="B"/> B. 25-50 </div>     <div class="chosen-item">  <input type="radio"  name="box1" value="C"/>  C. 51-60</div> <br/>
            <div class="chosen-item"><input type="radio"  name="box1" name="box1" value="D"/> D.61-65 </div>       <div class="chosen-item"><input type="radio"  name="box1"/> E.高于65岁</div>
        </div>


        <h3>2.您的家庭总资产净值为（折合人民币）？（不包括自用住宅和私营企业等实业投资，包括储蓄、保险、金融投资，并需扣除未结清贷款、信用卡账单等债务）</h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box2" value="A"value="A"/> A.15万元以下 </div>    <div class="chosen-item"> <input type="radio"  name="box2" value="B"/> B.15万元（不含）-50万元（含）</div> <br/>
            <div class="chosen-item"><input type="radio"  name="box2" value="C"/> C.50万元（不含）-100万元（含）</div>     <div class="chosen-item"> <input type="radio"  name="box2" value="D"/> D. 100万元（不含）-1000万元（含）</div> <br/>
            <div class="chosen-item"><input type="radio"  name="box2" value="E"/> E.1000万元（不含）以上</div> <br/>
        </div>


        <h3>3.在您家庭总资产净值中，可用于金融投资（储蓄存款除外）的比例为？ </h3>
        <div>
            <div class="chosen-item"><input type="radio"  name="box3" value="A"/> A.小于10%</div>        <div class="chosen-item"><input type="radio"  name="box3" value="B"/> B.10%至25%</div> <br/>
            <div class="chosen-item"><input type="radio"  name="box3" value="C"/> C.25%至50%</div>       <div class="chosen-item"><input type="radio"  name="box3" value="D"/> D.大于50%</div> <br/>
        </div>


        <h2>二、投资经验（任一项选A的客户均视为无投资经验客户）</h2>

        <h3>4.以下哪项最能说明您的投资经验？  </h3>
        <div>
           <div class="chosen-item"> <input type="radio"  name="box4" value="A"/> A.除存款、国债外，我几乎不投资其他金融产品 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box4" value="B"/> B.大部分投资于存款、国债等，较少投资于股票、基金等风险产品</div> <br/>
                <div class="chosen-item"><input type="radio"  name="box4" value="C"/> C.资产均衡地分布于存款、国债、银行理财产品、信托产品、股票、基金等</div> <br/>
                    <div class="chosen-item"><input type="radio"  name="box4" value="D"/> D.大部分投资于股票、基金、外汇等高风险产品，较少投资于存款、国债</div> <br/>
        </div>


        <h3>5.您有多少年投资股票、基金、外汇、金融衍生产品等风险投资品的经验？  </h3>
        <div>
            <div class="chosen-item">  <input type="radio"  name="box5" value="A"/> A.没有经验 </div>    <div class="chosen-item"> <input type="radio"  name="box5" value="B"/> B. 有经验，但少于2年 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box5" value="C"/> C.2至5年 </div>     <div class="chosen-item"> <input type="radio"  name="box5" value="D"/> D.5至8年 </div><br/>
            <div class="chosen-item"> <input type="radio"  name="box5" value="E"/> E.8年以上 </div> <br/>
        </div>

        <h3>6．以下哪项描述最符合您的投资态度？  </h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box6" value="A"/> A.厌恶风险，不希望本金损失，希望获得稳定回报 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box6" value="B"/> B.保守投资，不希望本金损失，愿意承担一定幅度的收益波动 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box6" value="C"/> C.寻求资金的较高收益和成长性，愿意为此承担有限本金损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box6" value="D"/> D.希望赚取高回报，愿意为此承担较大本金损失 </div> <br/>
        </div>

        <h2>三、投资风格</h2>

        <h3>7. 本金100万元，不提供保本承诺的情况下，您会选择哪一种投资机会？  </h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box7" value="A"/> A.有100%的机会赢取1000元的现金，并保证归还 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box7" value="B"/> B.有50%的机会赢取5万元现金，并有较高可能性归回本金 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box7" value="C"/> C.有25%的机会赢取50万元现金，并有一定的可能性损失本金 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box7" value="D"/> D.有10%的机会赢取100万元现金，并有较高可能性损失本金 </div> <br/>
        </div>


        <h3>8. 投资于理财、股票、基金等金融投资品（不含存款和国债）时，您可接受的最长投资期限是多久？  </h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box8" value="A"/> A.1年以下 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box8" value="B"/> B.1－3年 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box8" value="C"/> C.3—5年 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box8" value="D"/> D.5年以上 </div> <br/>
        </div>

        <h3>9、您的投资目的是 ？</h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box9" value="A"/> A.资产保值 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box9" value="B"/> B.资产稳健增值 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box9" value="C"/> C.资产迅速增值 </div> <br/>
        </div>

        <h2>四、风险承受能力</h2>

        <h3>10.1年内（短期），您的投资出现何种程度的波动时，您会呈现明显的焦虑？  </h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box10" value="A"/> A.本金无损失，但收益未达预期 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box10" value="B"/> B.出现轻微本金损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box10" value="C"/> C.本金10％以内的损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box10" value="D"/> D.本金20-50％的损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box10" value="E"/> E.本金50％以上损失 </div> <br/>
        </div>


        <h3>11.1年以上（长期），您的投资出现何种程度的波动时，您会呈现明显的焦虑？</h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box11" value="A"/> A.本金无损失，但收益未达预期 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box11" value="B"/> B.出现轻微本金损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box11" value="C"/> C.本金10％以内的损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box11" value="D"/> D.本金20-50％的损失 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box11" value="E"/> E.本金50％以上损失 </div> <br/>
        </div>

        <h3>12.对您而言，保本比高收益更重要 </h3>
        <div>
            <div class="chosen-item"> <input type="radio"  name="box12" value="A"/> A.非常同意 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box12" value="B"/> B.同意 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box12" value="C"/> C.无所谓 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box12" value="D"/> D.不同意 </div> <br/>
            <div class="chosen-item"> <input type="radio"  name="box12" value="E"/> E.非常不同意 </div> <br/>
        </div>
        </form>

        <div style="margin: 24px 0;">
        ********************************************* <br/>
        分值区间	     投资者风险类型 <br/>
        81-100分	     激进型 <br/>
        61-80分	     进取型 <br/>
        36-60分	     平衡型 <br/>
        16-35分	     稳健型 <br/>
        -9-15分	     谨慎型 <br/>
        ********************************************* <br/>
        </div>




        <div style="font-size: 14px;margin-top: 24px;"> <span class="in-bl">评估结果：</span>      <input placeholder="请输入分数" width="120px" style="text-align: center;" name="score_type"/>      <span class="in-bl">   （客户风险等级）</span></div>


           <div class="t-al-c"><a id="next_step" class="mui-btn mui-btn-danger" style="margin-bottom: 80px;">提交</a></div>

</body>
<script src="/js/jquery.min.js?v={{env('VERSION')}}"></script>
<script src="/js/jquery.serializejson.min.js?v={{env('VERSION')}}"></script>
<script src="/js/mui.min.js?v={{env('VERSION')}}"></script>
<script src="/js/plugin/layer_mobile/layer.js?v={{env('VERSION')}}"></script>
<script src="/js/common.js?v={{env('VERSION')}}"></script>
<script>


    var pageConfig =
        {
            id:'{{\Illuminate\Support\Facades\Request::input('id')}}'
        }

    var answer = [];

    $(function () {
        new SubmitButton({
            selectorStr:"#next_step",
            url:'/finance-class/save',
            prepositionJudge:function(){
                var str = $('#data_form').serialize();
                console.log(str);
                answer = [];
                for(var i= 1; i < 13; i++)
                {
                    if ( str.indexOf('box' + i) === -1)
                    {
                        parent.mAlert('第'+i+'题未选择');
                        return false;
                    }
                    answer.push($('input[name="box'+i+'"]:checked').val());
                }

                if ( !$('input[name="score_type"]').val() )
                {
                    parent.mAlert('请填写评估结果');
                    return false;
                }

                return true;
            },
            data:function()
            {
                console.log(answer);
                return {answer:answer,score_type:$('input[name="score_type"]').val(),id:pageConfig.id};
            },
            success:function(obj,data)
            {
                if( data.status )
                {
                    parent.mAlert('保存成功');
                }
            }
        });
    });
</script>
</html>