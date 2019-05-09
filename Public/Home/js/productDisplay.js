
  // 默认选择第一个基金
  var fundkind = 1;
  // 默认选择第一个选项 -->产品参数
  var detail = 1;


//版权
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Product/cu_info",
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

//左侧标题接口
var cid;
var cpId;
var cHid=[];
var cHidHTML;
var flag=true;
var firstClick;
var firstHTML;
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Product/type",
	data:{},
	async:false,
	dataType:'json',
	success:function(data){
		console.log(data);
		var typeHTML = "<ul class='fundKinds'>";
		var typeNum=1;
		for(var i=0;i<data.data.length;i++){

			if(i==0){
				typeHTML+='<li><a href="javascript:;" data-fundkind="'+typeNum+'" class="bgc" index="'+data.data[i].cid+'">'+data.data[i].name+'</a></li>';
				firstClick=data.data[i].cid;
				firstHTML=data.data[i].name;
			}else{
				typeHTML+='<li><a href="javascript:;" data-fundkind="'+typeNum+'" class="" index="'+data.data[i].cid+'">'+data.data[i].name+'</a></li>';				
			}
			typeNum++;
		}
		typeHTML+="</ul>";
		$(".list-left").html(typeHTML);
		$('.list-left').children('ul').children('li').eq(0).children('a').click(clickNav(firstClick));

	}
})
var listHeight
$('.list-left').find('a').click(function(){
	clickNav($(this).attr('index'));
	$(this).addClass("bgc").parent("li").siblings().children("a").removeClass("bgc");
  $(".list-right4").css('display', 'block');
  $(".list-right-zong").css('display', 'none');
  $(".list-right").css('display', 'none');
  $(".list-right-zong1").css('display', 'none');
	$(".list-right-top li").first().addClass('line');
   var clickTarget = event.target;
	fundkind = $(event.target).data("fundkind");
	$.each($(".fundKinds a"), function(index, val) {
    $(val).removeClass('bgc');
	});
	$(event.target).addClass('bgc');
//   如果子菜单在项目介绍就跳到产品参数里
	$.each($(".list-right-top li"), function(index, val) {
    $(val).removeClass('line');
	});
	$(".list-right-top li").first().addClass('line');
	detail = 1;
	fundkindDetail(fundkind, detail);
})

function clickNav(id){
	$.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/Home/Product/productlist",
		async:true,
		data:{cid:id},
		dataType:"json",
		success:function(data){
			console.log(data);
			var IcoHTML="<ul>";
			for(var i=0;i<data.data.length;i++){
				IcoHTML += '<li><p>'+data.data[i].title+'</p><ul class="list-right4-top"><li>'+data.data[i].rate+'%</li><li>'+data.data[i].published+'</li><li>'+data.data[i].ransom+'</li><li>'+data.data[i].explain+'</li>	</ul><ul class="list-right4-bot"><li>年化预期</li><li>产品发售期</li><li>赎回方式</li><li>费用说明</li></ul><div class="purchaseBtn1" index="'+ data.data[i].hid +'">认购基金</div></li>';
				
				
			}
			IcoHTML += "</ul>";
			$(".list-right4").html(IcoHTML);
			listHeight=$(".list-right4").height()+30;
			if(listHeight<260){
  		  $(".list").css("height","260px");
			}else{
  		 	$(".list").css("height",listHeight);
			}
			$(".purchaseBtn1").click(function(){
				var purIndex=$(this).parent().index();
				var Intype="";
				cHidHTML="";
				cHid=data.data[purIndex].hid.split(',');
				cHidshow(cHid);
				cpId=data.data[purIndex].id;
				if(data.data[purIndex].jyid==1){
					Intype="浮动交易";
				}else if(data.data[purIndex].jyid==2){
					Intype="固定交易";
				}else if(data.data[purIndex].jyid==3){
					Intype="未定交易类型";
				}
				var datapurHTML ="<li>"+Intype+"</li><li>"+data.data[purIndex].require+"</li><li>"+data.data[purIndex].term+"</li><li>"+data.data[purIndex].explain+"</li><li>"+data.data[purIndex].ransom+"</li>";
				var contract='<div class="contractTitle">'+ data.data[purIndex].title +'产品合同</div><div class="clauseText">'+ data.data[purIndex].contract +'</div>';
				var read='<label><input type="checkbox" class="isRead" value="false"> 我已经阅读《<span>'+ data.data[purIndex].title +'产品合同</span>》</label>'
				var IntroHTML = data.data[purIndex].description;
				$(".contractContentBox").html(contract);
				$(".readContract").html(read);
				$(".productIntroduce").html(IntroHTML);
				$(".data-right").html(datapurHTML);
				
			})
		}
	});
}

//contract='<div class="contractTitle">'+ data.data[i].title +'产品合同</div><div class="clauseText">'+ data.data[i].contract +'</div>';
//console.log(cHid);
function cHidshow(newHid){
			for(var z=0;z<newHid.length;z++){
								console.log(newHid[z].length);
							if(newHid[z]==100){
								cHidHTML+="<option>USD</option>";
							}else if(newHid[z]==101){
								cHidHTML+="<option>CNY</option>";
							}else if(newHid[z]==102){
								cHidHTML+="<option>KRW</option>";
							}else if(newHid[z]==103){
								cHidHTML+="<option>BTC</option>";
							}else if(newHid[z]==104){
								cHidHTML+="<option>ETH</option>";
							}else if(newHid[z]==105){
								cHidHTML+="<option>LTC</option>";
							}
					}
			
			$(".list-right-zong-hbzl").html(cHidHTML);
}							
//						



//确认认购请求
var author;
$(".handle_deposit").focus(function(){
  author=$(this).val();
})
$(".handle_client").focus(function(){
  author=$(this).val();
})
$(".buyBtn").click(function(){
	var uid=getCookie("uid");
	var rgze=$(".list-right-zong .rgze").val();
	$.ajax({
		type:"post",
		url:"http://jr.wokerr.com/index.php/home/product/buyProduct",
		data:{uid: uid,product_id: cpId,product_sum: rgze,author:author},
		dataType:"json",
		async:false,
		success:function(dataBuy){
			alert(dataBuy.msg);
			$(".list-right-zong .rgze").val("");
		}
	})
})

// 点击右侧产品参数和项目介绍按钮
$(".list-right-top li").click(function(event) {
    $.each($(".list-right-top li"), function(index, val) {
         $(val).removeClass('line');
    });
    $(event.target).parent("li").addClass('line');
    detail = $(event.target).data("detail");
    fundkindDetail(fundkind, detail);
});

 // 点击认购按钮
 var purchaseBtn;
$(".list").on("click",".purchaseBtn",function() {
    $(".list").css('display', 'none');
    $(".fundContract").css('display', 'block');
    $(".list-right").css('display', 'none');
    // 跳转到合同页面前清空阅读合同的状态
    $(".isRead").prop("checked", false);
    purchaseBtn=0;

    fundkindDetail(fundkind, detail);
});
$(".list").on("click",".purchaseBtn1",function() {
    $(".list").css('display', 'block');
    $(".fundContract").css('display', 'none');
    $(".list-right").css('display', 'block');
    $(".list-right1").css('display', 'none');
    $(".list-right2").css('display', 'none');
    $(".list-right3").css('display', 'none');
    $(".list-right4").css('display', 'none');
    $(".list").css("height","600px");
    // 跳转到合同页面前清空阅读合同的状态
    $(".isRead").prop("checked", false);
    purchaseBtn=1;

    fundkindDetail(fundkind, detail);
//  认购页面
    $.ajax({
    	
    })
});

// 阅读合同后点击下一步
$(".fundContractNextBtn").click(function() {
    var isRead = $(".isRead").prop("checked");
    if (isRead) {
    		$(".fundContract").css('display', 'none');
        $(".list").css('display', 'block');
        $(".list-right").css('display', 'none');
        $(".list-right-zong").css('display', 'block');
        $(".list-right-zong1").css('display', 'none');
    		$(".list").css("height","870px");
    }
    console.log(isRead);
});

//// 点击确认认购按钮
//$(".buyBtn").click(function() {
//  alert("确认认购成功！！");
//  
//});

// 确定是那个基金和详细参数
function fundkindDetail(fundkind, detail) {
    if (detail==1) {
      $(".list-right .data").css('display', 'block');
      $(".list-right .dataProduct").css('display', 'none');
    }else{
      $(".list-right .data").css('display', 'none');
      $(".list-right .dataProduct").css('display', 'inline-block');
    }
    // 这里可以根据选择进行数据请求
//  console.log([fundkind, detail]);



}





