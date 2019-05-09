


$(function(){
	

$(".level2 li").click(function(){
	
	var index=$(this).index()
	 console.log(index)
	if(index==0){
          	$(".hide").show()
          	$(".hide1").hide();
          	$(".hide2").hide()
          	$(".hide3").hide()
          	$(".hide4").hide()
          	}else if(index==1){
          		$(".hide").hide();
          		$(".hide2").hide()
          		$(".hide3").hide()
          		$(".hide4").hide()
          		$(".hide1").show()
          	}else if(index==2){
          			$(".hide").hide();
          			$(".hide1").hide()
          			$(".hide3").hide()
          			$(".hide4").hide()
          	      $(".hide2").show()
          	}
	
	
})
})

$(function(){
	

$(".menu li").click(function(){

  
	$(".right_left").eq($(".level1").index(this)).addClass("show").siblings().removeClass("show")
	
})

})
$(function(){
	
	$(".level1>a").click(function(){
		
		$(this).addClass("current").next().slideToggle().parent().siblings().children("a")
		.removeClass("current").next().slideUp();
		
		
		
//		return false;
	})
})



  $(function(){
       	
       
       $(".hai-fs").click(function(){
       	$(".hide").hide()
       	$(".hide3").show()
       	$(".hide1").hide()
       	$(".hide2").hide()
       })
       
      }) 
      



 	$(function(){
			
			$(".title ul li").click(function(){
				$(this).addClass("bei");
				$(this).siblings().removeClass("bei");
			$("sao li").eq($(".title ul li").index(this)).addClass("logg").siblings().removeClass("logg")
			
			var index=$(this).index();
		  console.log(index)
			if(index==0){
						$(".sao").html("请打开手机微信，扫一扫完成支付")
						$(".sao").css("background-color","green "); 
						$(".scaveng-right img").attr("src","img/wx.png")
					}else if(index==1){
							$(".sao").html("请打开支付宝，扫一扫完成支付")
							$(".sao").css("background-color","#07a0f8"); 
							$(".scaveng-right img").attr("src","img/T13CpgXf8mXXXXXXXX.png")
			
					}
					})
			})
		
		
		//提现
		$(function(){
			
		
		$(".tixian").click(function(){
			$(".hide").hide()
			$(".hide1").hide()
			$(".hide2").hide()
			$(".hide3").hide()
			$(".hide4").show()
		})
       
       })
		$(function(){
			
		
       $(".ul li").click(function(){
			$(this).addClass("bei");
			$(this).siblings().removeClass("bei");
			
		$(".two-hai").eq($(".ul li").index(this)).addClass("haiove").siblings().removeClass("haiove")
		})
	
	
	})


                //人民币界面
				$(function(){
		  	
		
          $(".level2 li").click(function(){
          	var index=$(this).index();
          	console.log(index)
          	if(index==0){
          	$(".sepo").show()
          	$(".sepo1").hide();
          	$(".sepo2").hide()
          	$(".sepo3").hide()
          	$(".sepo4").hide()
          	}else if(index==1){
          		$(".sepo").hide();
          		$(".seop1").hide()
          		$(".sepo4").hide()
          		$(".sepo3").hide()
          		$(".sepo1").show()
          	}else if(index==2){
          			$(".sepo").hide();
          			$(".sepo1").hide()
          			$(".sepo3").hide()
          			$(".sepo4").hide()
          	$(".sepo2").show()
          	}else if(index==3){
          		$(".sepo").hide();
          			$(".sepo1").hide()
          			$(".sepo2").hide()
          			$(".sepo4").hide()
          	       $(".sepo3").show()
          	}else if(index==4){
          		$(".sepo").hide();
          			$(".sepo1").hide()
          			$(".sepo3").hide()
          			$(".sepo2").hide()
          	         $(".sepo4").show()
          	}
          	
          
          })
         })