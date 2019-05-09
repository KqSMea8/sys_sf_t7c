var token = getCookie("access_token");
var touid = getCookie("uid");
//console.log(touid);
if (!token) {
	window.location.href = "index.html";
}

// 我的账户 --- 上半部分 数据请求
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/count",
	data:{uid:touid},
	dataType:"json",
	async:false,
	success:function(datas){
		console.log(datas);
		var tempHTML='';
		var array=['人民币','美元','韩元','BTC','ETH','LTC'];
		var Iarray=[];
		var Narray=[];
		var Jarray=[];
		datas.data.invest=$.map(datas.data.invest,function(i){
            Iarray.push(i);
            return Iarray;
		})
		datas.data.incomed=$.map(datas.data.incomed,function(n){
            Narray.push(n);
            return Narray;
		})
		datas.data.sum=$.map(datas.data.sum,function(j){
			Jarray.push(j);
            return Jarray;
		})
		for(var a=0;a<array.length;a++){
			tempHTML+='<div class="accountTopContent"><div class="accountCurrency">'+ array[a] +'</div><div class="accountInvest">'+ Iarray[a] +'</div><div class="accountGain">'+ Narray[a] +'</div><div class="accountRemaining">'+ Jarray[a] +'</div></div>';
		}
		$(".accountTop").append(tempHTML);
	}
})

//版权
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Index/cu_info",
	async:false,
	data:{},
	dataType:"json",
	success:function(data){
		console.log(data);
		var tempHTML="";
		tempHTML='<div class="contact"><div class="contact_our">联系我们</div><div class="contact_now"><span>电话 ：</span><span>'+ data.data.tel +'</span></div><div class="contact_now"><span>邮编 ： </span><span>'+ data.data.email +'</span></div><div class="contact_now"><span>工作时间 ：</span><span>'+ data.data.work_time +'</span></div></div><div class="address">'+ data.data.copyright +'</div><div class="codeall"><div class="code"><img src="http://jr.wokerr.com/Uploads'+ data.data.weixin +'" alt=""><p>微信公众号</p></div><div class="code"><img src="http://jr.wokerr.com/Uploads'+ data.data.qq +'" alt=""><p>官方QQ群</p></div></div>';
		$(".footer_content").html(tempHTML);
	}
});


//我的账户下半部分请求
var accountBottomTitleIndexs=1;
var sortSelectorIndexs=1;
$(".myAccount .accountBottomTitle").children("div").click(myAccount(accountBottomTitleIndexs,sortSelectorIndexs));
$(".myAccount .accountBottomTitle").children("div").click(function(){
	accountBottomTitleIndexs=$(this).index()+1;
	myAccount(accountBottomTitleIndexs,sortSelectorIndexs);
	$(this).addClass("accountBottomActive").siblings().removeClass("accountBottomActive");
})
$(".myAccount .sortSelector").children("div").click(function(){
	sortSelectorIndexs=$(this).index()+1;
	myAccount(accountBottomTitleIndexs,sortSelectorIndexs);
	$(this).addClass("active").siblings().removeClass("active");
})
function myAccount(mytt,myet){
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/home/personal/goods_list",
	data:{uid:touid,tt:mytt,et:myet},
	dataType:"json",
	async:false,
	success:function(data){
		console.log(data);
		var tempHTML="";
		for(var i=0;i<data.data.length;i++){
			tempHTML+='<div class="fundContent"><div class="fundTitle"><div class="fundTitleNum"><span>编号：</span>'+ data.data[i].pnum +'</div><div class="fundTitleName"><span>名称：</span>'+ data.data[i].pname +'</div></div><div class="fundContentDetail clearfix"><div><p class="detailNum">'+ data.data[i].invest +'</p><p>投资金额(元)</p></div><div><p class="detailNum">'+ data.data[i].rate +'%</p><p>年华收益率(历史)</p></div><div><p class="detailNum">'+ data.data[i].term +'</p><p>授权服务期限</p></div><div><p class="detailNum">'+ data.data[i].income +'</p><p>代收收益(元)</p></div><div class="fundDetailOpenBox"><div class="fundDetailOpen detailNum" onclick="myAccountShow()" index="'+ data.data[i].id +'">[展开]</div></div></div></div>';
		}
		$(".myAccount .accountBotttom").children("div").eq(2).html(tempHTML);
	}
})
}
// 资金管理 --- 充值接口
$(".rechargeMoneyBtn").click(function() {
	var rechargeMoneyInput = $("#rechargeMoneyInput").val();
	var moneySymbolId = $(".rechargeMoneyContent select").val();
    $.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/Home/Personal/recharge",
		data:{uid:touid,sum:rechargeMoneyInput,cid:moneySymbolId},
		dataType:"json",
		async:false,
		success:function(datae){
			console.log(datae);
			alert("充值订单编号："+datae.data);
   		}
	})
});

// 资金管理 --- 提现接口
$(".useMoneyBtn").click(function() {
	var useMoneyInput = $("#useMoneyInput").val();
	var moneySymbolId = $(".useMoneyContent select").val();
	$.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/Home/Personal/withdraw",
		data:{uid:touid,sum:useMoneyInput,cid:moneySymbolId},
		dataType:"json",
		async:false,
		success:function(datae){
//			console.log(datae);
			if(datae.state==1){
				alert("提现订单编号："+datae.data);
			}else{
				alert(datae.msg);
			}
   		}
	})
});

//资金管理 --- 余额 
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/remain",
	data:{uid:touid},
	dataType:"json",
	async:false,
	success:function(datas){
		console.log(datas);
		var tempHTML='';
		var array=['人民币','美元','韩元','BTC','ETH','LTC'];
		var Iarray=[];
		var Narray=[];
		datas.data.remain=$.map(datas.data.remain,function(i){
            Iarray.push(i);
            return Iarray;
		})
		datas.data.ava_remain=$.map(datas.data.ava_remain,function(n){
            Narray.push(n);
            return Narray;
		})
		for(var a=0;a<array.length;a++){
			tempHTML+='<div class="moneyInformation clearfix"><div class="accountCurrency">'+ array[a] +'</div><div class="accountBalance">'+ Iarray[a] +'</div><div class="accountUse">'+ Narray[a] +'</div></div>';
		}
		$(".rechargeMoney .accountInformation").append(tempHTML);	
		$(".useMoney .accountInformation").append(tempHTML);	
	}
})

//资金管理 --- 交易记录
var timeIndex=0;
var typeIndex=1;
var orderHTML='<div class="orderDetailTitle"><div>订单编号</div><!----><div>交易时间</div><!----><div>交易内容</div><!----><div>交易金额</div><!----><div>交易状态</div></div>';
$(".recordTime").children("div").eq(1).click(recordShow(timeIndex,typeIndex));
$(".recordType").children("div").click(function(){
	typeIndex=$(this).index();
	$(".orderDetail").html(orderHTML);
	recordShow(timeIndex,typeIndex);
	$(this).addClass("active").siblings().removeClass("active");
})
$(".recordTime").children("div").click(function(){
	timeIndex=$(this).index()-1;
	$(".orderDetail").html(orderHTML);
	recordShow(timeIndex,typeIndex);
	$(this).addClass("active").siblings().removeClass("active");
})
function recordShow(zTime,zType){
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/record",
	data:{uid:touid,time:zTime,type:zType},
	dataType:"json",
	async:false,
	success:function(data){
		console.log(data);
		var msgHTML="";
		var msgType;
		var newDate = new Date();
		for(var i=0;i<data.data.length;i++){
			timestamp=data.data[i].dtime;
			newDate.setTime(timestamp * 1000);
			if(data.data[i].type==1){
				msgType="全部";
			}else if(data.data[i].type==2){
				msgType="充值";
			}else if(data.data[i].type==3){
				msgType="提现";
			}else if(data.data[i].type==4){
				msgType="产品投资";
			}else if(data.data[i].type==5){
				msgType="产品退出";
			}
			msgHTML+='<div class="orderDetailContent"><div>'+ data.data[i].tnum +'</div><div>'+ newDate.toLocaleDateString() +'</div><div>'+ msgType +'</div><div>'+ data.data[i].sum +'</div><div>'+ data.data[i].state +'</div></div>';
		}
        $(".orderDetail").append(msgHTML);
	}
})
}

//站内信息列表
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/newsList",
	data:{},
	dataType:"json",
	async:false,
	success:function(data){
		console.log(data);
		var tempHTML="";
		for(var i=0;i<data.data.length;i++){
				tempHTML += '<div class="systemInfor"><div class="systemInforTitle clearfix"><span class="fl">系统消息</span><span class="fr" style="color: #ccc;font-weight: normal;">'+data.data[i].stime+'</span></div><div class="systemInforDetail">'+data.data[i].nt+'</div></div>';
		}
		tempHTML += '<div class="systemFooter"><div class="prevPage">上一页</div><div class="nextPage">下一页</div></div>';
		$(".webInformation").append(tempHTML);
	}
})

//基金产品下面产品接口
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Product/type",
	data:{},
	async:false,
	dataType:'json',
	success:function(data){
//		console.log(data);
		var typeHTML = "";
		var typeNum=1;
		var navNum=7;
		for(var i=0;i<data.data.length;i++){

			if(i==0){
				typeHTML+='<div data-fund="'+ typeNum +'" data-navbtn="'+ navNum +'" class="fund active" index="'+data.data[i].cid+'">'+ data.data[i].name +'</div>';
				firstClick=data.data[i].cid;
			}else{
				typeHTML+='<div data-fund="'+ typeNum +'" data-navbtn="'+ navNum +'" class="fund" index="'+data.data[i].cid+'">'+ data.data[i].name +'</div>';				
			}
			typeNum++;
			navNum++;
		}
		$("#collapseThree>.panel-body").html(typeHTML);
	}
})

//基金产品右侧投资接口
var collapseProductss=100;
$("#collapseThree>div").children("div").eq(0).click(collapseShow(collapseProductss));
$(".panel-body").children("div").eq(0).click(myAccounts(accountBottomTitleIndexs,sortSelectorIndexs,collapseProductss));
$("#collapseThree>div").children("div").click(function(){
	$(".fundProductTitle").html($(this).html());
	collapseProductss=$(this).attr('index');
	console.log(collapseProductss);
	collapseShow(collapseProductss);
	myAccounts(accountBottomTitleIndexs,sortSelectorIndexs,collapseProductss);
	$(this).addClass('active').siblings().removeClass('active');
})

function collapseShow(cid){
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/collect",
	data:{uid:touid,cid:cid},
	dataType:"json",
	async:false,
	success:function(data){
		var tempHTML="";
		tempHTML+='<div class="fundDetailContent"><div class="fundTotalDetail"><div class="productHold"><div class="fundMoneyNum">'+data.data.chiyou+'</div><div>产品持有（元）</div></div><div class="productOver"><div class="fundMoneyNum">'+data.data.jieshu+'</div><div>已结束产品（元）</div></div><div class="investmentAmount"><div class="fundMoneyNum">'+data.data.invest+'</div><div>投资额（元）</div></div><div class="productProfit"><div class="fundMoneyNum">'+data.data.income+'</div><div>累计利润（元）</div></div></div>';
		$('.fundDetailContent').html(tempHTML);
	}
})
}
    
//基金产品右侧下方列表接口
$(".fundProduct .accountBottomTitle").children("div").click(function(){
	accountBottomTitleIndexs=$(this).index()+1;
	myAccounts(accountBottomTitleIndexs,sortSelectorIndexs,collapseProductss);
	$(this).addClass("accountBottomActive").siblings().removeClass("accountBottomActive");
})
$(".fundProduct .sortSelector").children("div").click(function(){
	sortSelectorIndexs=$(this).index()+1;
	myAccounts(accountBottomTitleIndexs,sortSelectorIndexs,collapseProductss);
	$(this).addClass("active").siblings().removeClass("active");
})
var cpId;
var newData=[];
function myAccounts(mytt,myet,cid){
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/home/personal/goods_list",
	data:{uid:touid,cid:cid,tt:mytt,et:myet},
	dataType:"json",
	async:false,
	success:function(data){
		console.log(data);
		var tempHTML="";
		for(var i=0;i<data.data.length;i++){
	        tempHTML+='<div class="fundContent"><div class="fundTitle"><div class="fundTitleNum"><span>编号：</span>'+ data.data[i].pnum +'</div><div class="fundTitleName"><span>名称：</span>'+ data.data[i].pname +'</div></div><div class="fundContentDetail clearfix"><div><p class="detailNum">'+ data.data[i].invest +'</p><p>投资金额(元)</p></div><div><p class="detailNum">'+ data.data[i].rate +'%</p><p>年华收益率(历史)</p></div><div><p class="detailNum">'+ data.data[i].term +'</p><p>授权服务期限</p></div><div><p class="detailNum">'+ data.data[i].income +'</p><p>代收收益(元)</p></div><div class="fundDetailOpenBox"><div class="fundDetailOpen detailNum" onclick="fundProductShow()" index="'+ data.data[i].id +'">[展开]</div></div></div></div>';
		}
		$(".fundProduct .accountBotttom").children("div").eq(2).html(tempHTML);
	}
})
}

//已认购产品详情请求
function fundDetailShow(fundDetailId){
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Personal/productInfo",
	data:{id:fundDetailId},
	dataType:"json",
	async:false,
	success:function(data){
		console.log(data);
		var tempHTML = '<div><span style="color: #7ECEF4;">编号：</span><span class="fundNum">'+ data.data.pnum +'</span></div><div><span style="color: #7ECEF4;">名称：</span><span class="fundName">'+ data.data.pname +'</span></div><div>客户目前持有的份额数</div></div>';
		var atempHTML = '<div><p>'+ data.data.rate +'%</p><div>年化收益率（历史）</div></div><div><p>12</p><div>授权服务期限</div></div><div><p>'+ data.data.invest +'</p><div>总额（元）</div></div><div><p>'+ data.data.num +'</p><div>目前持有的份额数</div></div><div><p>'+ data.data.income +'</p><div>当前利润</div></div>';
		var btempHTML='<div><img src="images/personal/shengouchanpin.png" alt=""><div>申购产品</div><div class="applyTime">'+ data.data.stime +'</div><span class="glyphicon glyphicon-menu-right"></span></div><div><img src="images/personal/canpinyunying.png" alt=""><div>产品运行</div><div class="runTime">'+ showTime() +'</div><span class="glyphicon glyphicon-menu-right"></span></div><div><img src="images/personal/fenqihuankuan.png" alt=""><div>到期还本付息</div><div class="payBackTime">'+ data.data.dtime +'</div></div>';
		$(".sellFundTitle").html(tempHTML);
		$(".sellFundDetail").html(atempHTML);
		$(".sellFundProcess").html(btempHTML);
		var reNum=data.data.time;
		var reNumtxt="";
		var reNumHTML="";
		if(reNum==0){
			reNumtxt='已经用时：<span>'+data.data.y+'年'+data.data.m+'月'+data.data.d+'日</span>';
		}else{
			reNumtxt='满额用时：<span>'+data.data.y+'年'+data.data.m+'月'+data.data.d+'日</span>';   //返回用时时间
		}
		for(var i=0;i<data.data.join_num.length;i++){
		reNumHTML='<div class="fundHeaderImg"><img src="images/personal/fundheader.png" alt=""></div><div class="fundApplying">正在申请</div><div class="finishTime">'+ reNumtxt +'</div><div class="fundJoinPerson">加入人次：<span>'+ data.data.join_num[i].uid +'</span>次</div><div class="fundOperateBtn"><div class="fundBuyBtn">申购</div><div index="'+ data.data.state +'" class="fundSellBtn">赎回</div></div>';
		}
		if(data.data.state==0){
			$('.fundSellBtn').css('background-color','#999');
		}else{
			$('.fundSellBtn').css('background-color','#59a3fa');
		}
		$(".sellFundTopR").html(reNumHTML);
	}
})
}

function showTime(){
   var mydate = new Date();
   var str = "" + mydate.getFullYear() + "年";
   str += (mydate.getMonth()+1) + "月";
   str += mydate.getDate() + "日";
   return str;
}


// 点击展开按钮 --申请赎回
$(".fundDetailOpen").click(function(event) {
	cpId=$(this).attr('index');
	fundDetailShow(cpId);
	myChartShow(cpId);
	$('html , body').animate({scrollTop: 0}, 100);
});



//赎回申请
$(".sellFundTopR").on('click','.fundSellBtn',function(){
//	console.log(cpId);
	var index=$(this).attr('index');
	if(index==1){
		$.ajax({
			type:"post",
			url:"http://jr.wokerr.com/index.php/home/personal/ransomApply",
			data:{id:cpId},
			dataType:"json",
			async:false,
			success:function(data){
				console.log(data);
				alert(data.info);
			}
		})
	}
})
$(".sellFundTopR").on('click','.fundBuyBtn',function(){
//	console.log(cpId);
	$.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/home/personal/ransomApply",
		data:{id:cpId},
		dataType:"json",
		async:false,
		success:function(data){
			console.log(data);
		}
	})
})


// ------------------------------
var pNum = 1, money = 1, fund = 1;
var left_num = 1;



// 首次进入页面
// console.log("pNum="+pNum,"money="+money,"fund="+fund);

$(".panel-title").click(function() {
	// 当前点击的标题
	pNum = $(this).data('pnum');
	left_num = $(this).data('navbtn');
	console.log("left_num=="+left_num);

	var glyphiconBox = $(this).find('.glyphicon');
	var t_hasClassRight = glyphiconBox.hasClass('glyphicon-menu-right');
	// 点击第二个标题或点击第三个标题
	if (pNum == 2 || pNum == 3) {
		if (t_hasClassRight) {
			$(".panel-title .glyphicon").each(function(index, el) {
				$(el).removeClass('glyphicon-menu-down').addClass('glyphicon-menu-right');
			});
			glyphiconBox.removeClass('glyphicon-menu-right').addClass('glyphicon-menu-down');
		}else{
			$(".panel-title .glyphicon").each(function(index, el) {
				$(el).removeClass('glyphicon-menu-down').addClass('glyphicon-menu-right');
			});
			glyphiconBox.removeClass('glyphicon-menu-down').addClass('glyphicon-menu-right');
		}
	}else{
		// 点击其他按钮
		$(".panel-title .glyphicon").each(function(index, el) {
			$(el).removeClass('glyphicon-menu-down').addClass('glyphicon-menu-right');
		});
	}
	$(".panel-title a").each(function(index, el) {
		$(el).css('color', '#222');
	});
	$(".panel-title .glyphicon").each(function(index, el) {
		$(el).css('color', '#ccc');
	});
	$(this).find('a').css('color', '#0a85ef');
	$(this).find('.glyphicon').css('color', '#0a85ef');
	$("#collapseThree .panel-body div").each(function(index, el) {
		$(el).removeClass('active');
		if (index == 0) {
			$(el).addClass('active');
			fund = 1;
		}	
	});
	$("#collapseTwo .panel-body div").each(function(index, el) {
		$(el).removeClass('active');
		if (index == 0) {
			$(el).addClass('active');
			money = 1;
		}	
	});
	// ------------------------------------------------------------------------------------------------------------------------------
	// 点击标题回到原始数据，money=1，fund=1，并修改active
	// 点击标题输出
	// console.log("pNum="+pNum,"money="+money,"fund="+fund);
	personalRightCtrl(left_num);
});
	// -------------------------------- p_ContentLeft pNum------------------------
$(".money").click(function(e) {
	var $currTarget = $(e.target);
	$("#collapseTwo .panel-body div").each(function(index, el) {
		$(el).removeClass('active');	
	});
	$currTarget.addClass('active');
	money = $currTarget.data('money');
	left_num = $currTarget.data('navbtn');
	console.log("left_num=="+left_num);
	// ------------------------------------------------------------------------------------------------------------------------------------
	// money--2----1 2 3
	// 点击资金管理输出
	// console.log(pNum,money,fund);
	personalRightCtrl(left_num);
});
$(".fund").click(function(e) {
	var $currTarget = $(e.target);
	$("#collapseThree .panel-body div").each(function(index, el) {
		$(el).removeClass('active');	
	});

	$currTarget.addClass('active');
	fund = $currTarget.data('fund');
	left_num = $currTarget.data('navbtn');
	console.log("left_num=="+left_num);
	// -----------------------------------------------------------------------------------------------------------------------------------------
	// fund--3----1 2 3 4
	// 点击基金产品输出
	// console.log(pNum,money,fund);
	personalRightCtrl(left_num);
});

// 左边菜单控制个人中心右边显示内容
function personalRightCtrl(left_num) {
	$(".personal_r").each(function(index, el) {
		$(el).css('display', 'none');
		// 点击我的账户
		if (left_num==1&&index==0) {
			$(el).css('display', 'block');
		// 点击资金管理或充值
		}else if (left_num==2&&index==1||left_num==3&&index==1) {
			$(el).css('display', 'block');
		// 点击提现
		}else if (left_num==4&&index==2) {
			$(el).css('display', 'block');
		// 点击交易记录
		}else if (left_num==5&&index==3) {
			$(el).css('display', 'block');
		// 点击基金产品 所有基金共用一个页面
		}else if (left_num>=6&&left_num<=10&&index==4) {
			$(el).css('display', 'block');
		// 点击站内信息
		}else if (left_num==11&&index==6) {
			$(el).css('display', 'block');
		// 点击邀友有奖
		}else if (left_num==12&&index==7) {
			$(el).css('display', 'block');
		}
	});
}

// 点击 我的账户 隐藏按钮 隐藏投资额和可用余额均为0的行
$(".hideMoneyBtn").click(function() {
	var $this = $(this);
	var isHide = $this.data('hide');
	console.log(isHide);
	if(isHide==false){
		$(".accountTopContent").hide();
	}else{
		$(".accountTopContent").show();
	}
	$this.text(!isHide?'显示':'隐藏');
	$this.data('hide', !isHide);
});



// ------------------------- 绑定我的账户底部accountBottomTitle切换 ---------------------------



function myAccountShow(){
	$(".myAccount").css('display', 'none');
	$(".sellFundProduct").css('display', 'block');
}

function fundProductShow(){
	$(".fundProduct").css('display', 'none');
	$(".sellFundProduct").css('display', 'block');
}

// 点击邀友有奖
$(".titleImgFive").click(function() {
	$.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/home/personal/PromoterInfo",
		data:{uid:touid},
		dataType:"json",
		async:false,
		success:function(datas){
			console.log(datas);
			var tempHTML="";
			var stempHTML="";
			var msg=[];
			tempHTML+='<div><div class="numData friendNum">'+ datas.data.count +'</div><div class="contentData">邀请好友人数（人）</div></div><div><div class="numData">'+ datas.data.rabte +'</div><div class="contentData">邀请好友返利（元）</div></div><div><div class="numData">'+ datas.data.rate +'%</div><div class="contentData">邀请好友返利比例</div></div>';
			console.log(datas.data.data);
			if(datas.data.data!=null){
	        	datas.data.data = $.map(datas.data.data,function(i){
	        		msg.push(i);
					return msg;
	        	})
	        	for(var m=0;m<msg.length;m++){
	        		stempHTML+='<div class="detailContent"><div class="invitingTime">'+ msg[m].stime +'</div><div class="invitingPerson">'+ msg[m].name +'</div><div class="invitingMoney">'+ msg[m].rebate +'</div></div>';
	        	}
	       }
        $(".explain").html(tempHTML);
		$(".invitingDetail").append(stempHTML);
		}
	})
});



//产品折线图请求
function myChartShow(cpid){
	var myChart = echarts.init(document.getElementById('chartArea'));
	// var ItemLine = function() {
	// 	return {
	// 		itemStyle: {
	// 			normal: {
	// 				lineStyle: {
	// 					color: '#0f0' //控制折线颜色
	// 				}
	// 			}
	// 		},
	// 		name: '',
	// 		type: 'line',
	// //		symbol: 'none',   //去掉小圆点
	// 		data: []
	// 		//			itemStyle : { normal: {}}
	// 	}
	// };
	$.ajax({
		type: "post",
		url: "http://jr.wokerr.com/index.php/home/personal/worthChart",
		data: {
			id: cpid
		},
		async: false,
		dataType: "json",
		success: function(data) {
			console.log(data);
			$("#productDes").html('<p>'+ data.data.description +'</p>');
			var xTime = [];
			var aAmount = [];
			var bAmount = [];
			var newDate = new Date();
			var times;
			var Title = [];
			for(var i = 0; i < data.data.ss.length; i++) {
				times = data.data.ss[i].time;
				newDate.setTime(times * 1000);
				xTime.push(newDate.toLocaleDateString());
				aAmount.push(data.data.ss[i].amount);
				bAmount.push(data.data.ss[i].amount);
			}
			Title.push(data.data.title);
			var option = {
				title: {
					text: data.data.ss[0].productName,
					x: "center"
				},
				tooltip: {
					trigger: 'axis',
					axisPointer: { //鼠标滑过的线条样式
						type: 'line',
						lineStyle: {
							//color: 'red',//颜色
							width: 1, //宽度
							type: 'solid' //实线
						}
					}
				},
				calculable: false,
				xAxis: { //x轴的数据
					type: 'category',
					name: '月份',
					boundaryGap: false,
					data: xTime
				},
				yAxis: {
					type: 'value',
					name: "数值",
					splitArea: {
						show: true
					},
					axisLabel: { //y轴的内容格式化,很有用的属性
						formatter: '{value}'
					}
				},
				color: ["#3399ff", "#0f0"],
				legend: {
					data: Title, //要与series中的name一致
					y: "bottom"
				},
				series: [{
					itemStyle: {
						normal: {
							lineStyle: {
								color: '#3399ff' //控制折线颜色
							}
						}
					},
					name: Title,
					type: 'line',
					data: aAmount,
				}]
			};
			// 为echarts对象加载数据
			myChart.setOption(option);
		}
	})
}
























