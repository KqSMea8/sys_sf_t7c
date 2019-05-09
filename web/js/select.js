// JavaScript Document



$(function() {
	
    $(document).ready(function() {
         objBlurFun("select");
    //如果不是当前触摸点不在input上,那么都失去焦点
    function objBlurFun(sDom,time){
        var time = time||300;
        //判断是否为苹果
        var isIPHONE = navigator.userAgent.toUpperCase().indexOf("IPHONE")!= -1;
        if(isIPHONE){
           var obj = document.querySelectorAll(sDom);
           for(var i=0;i<obj.length;i++){
               objBlur(obj[i],time);
           }
        }
    }
    })
})