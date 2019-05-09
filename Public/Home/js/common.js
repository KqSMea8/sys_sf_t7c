// 登录逻辑
var token = getCookie("access_token");
var headerImgURL = "/Public/Home/images/login.png";

if(token){
//	console.log(token);
	$(".login").html('<img src="'+ headerImgURL +'" />');
	$(".login").attr('href', '../Personal/personalInformation.html');
	$(".register").html("退出")
	$(".register").attr('href', '../Index/index.html');
	$(".register").click(function() {
		setCookie("access_token","",7);
	});
}else{
	$(".login").html('登录');
	$(".login").attr('href', '../Login/login1.html');
	$(".register").html("免费注册");
	$(".register").attr('href', '../Login/register1.html');
}