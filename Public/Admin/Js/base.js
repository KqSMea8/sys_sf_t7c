/*
 * 初始化窗口尺寸
 */
var autoSize = function(){
    //    alert($(".commonBtnArea").length);
    var webBodyWidth=getBodySize("w");
    var webBodyHight=getBodySize("h");
    var h=(webBodyHight-150);
    h=h<300?300:h;
    $('#control').css('height',h+'px');
    $('#Left').css('height',(h+12)+'px');
    var btns=$(".commonBtnArea").length;
    var rh=btns>0?h-50:h;
    $('#Right').css({
        height:rh+'px',
        width:(webBodyWidth-230)+'px'
    });
    if(btns>0){
        $(".commonBtnArea").css({
            width:(webBodyWidth-210-40-16)+'px'
        });
    }
    var c_s=0;
    $('#control').click(function(){
        if(c_s==1){
            if(btns>0){
                $(".commonBtnArea").animate({
                    width:(webBodyWidth-210-40-16)+'px'
                }, "fast");
            }
            $("#Right").animate({
                width: (webBodyWidth-230)+'px'
            }, "fast");
            $("#Left").animate({
                marginLeft:"0px"
            }, "fast");
            $(this).removeClass("close");
            c_s=0;
        }else{
            if(btns>0){
                $(".commonBtnArea").animate({
                    width: (webBodyWidth-66)+'px'
                }, "fast");
            }
            $("#Right").animate({
                width: (webBodyWidth-26)+'px'
            }, "fast");
            $("#Left").animate({
                marginLeft:"-197px"
            }, "fast");
            $(this).addClass("close");
            c_s=1;
        }
    });
    /* 添加三级菜单 */
    $('#Left .togellernav').click(function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            if($(this).siblings().hasClass('active') == true){
            } else {
                //var width_val = parseInt($("#Right").css('width').match(/\d+/)) + 100;
                //$("#Right").css('width',width_val);
            }
        }else {
            $(this).addClass('active');
            if($(this).siblings().hasClass('active') == true){
				$(this).siblings().removeClass('active');
            } else {
               // var width_val = parseInt($("#Right").css('width').match(/\d+/)) - 100;
               // $("#Right").css('width',width_val);
            }
        }
    });
}