<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>机构/人员-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='机构/人员'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


        <style type="text/css">
            #Right{
                float:left;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <!-- <div id="Top">
    <div class="logo"><a target="_blank" href="<?php echo ($site["WEB_ROOT"]); ?>"><img src="/Public/Admin/Img/logo.png" /></a></div>
    
    <div class="menu">
        <ul> <?php echo ($menu); ?> </ul>
    </div>
</div>
<div id="Tags">
    <div class="userPhoto"><img src="/Uploads/<?php echo ($my_info["loc"]); ?>" /> </div>
    <div class="navArea">
        <div class="userInfo"><div><a href="<?php echo U('Webinfo/index');?>" class="sysSet"><span>&nbsp;</span>系统设置</a> <a href="<?php echo U("Public/loginOut");?>" class="loginOut"><span>&nbsp;</span>退出系统</a></div>欢迎您，<?php echo ($my_info["email"]); ?></div>
        <div class="nav"><font id="today"><?php echo date("Y-m-d H:i:s"); ?></font>您的位置：<?php echo ($currentNav); ?></div>
    </div>
</div>
<div class="clear"></div> -->

<div id="Top">
    <div class="admincp-header">
	  <div class="bgSelector"></div>
	 

	  <div class="nc-module-menu">
	    <ul class="nc-row">
	      <?php echo ($menu); ?>
	      <!-- <li><a href="<?php echo U('Webinfo/index');?>" class="sysSet"><span>&nbsp;</span>系统设置</a></li> -->
      	  <li><a href="<?php echo U("Public/loginOut");?>" class="loginOut"><span>&nbsp;</span>退出系统</a></li>
	    </ul>
	  </div>
	<div class="clear"></div>
</div>


            <div class="mainBody">
                <div id="Left">
    <div id="control" class=""></div>
    <div class="subMenuList">
        <div class="itemTitle"><!-- <?php if(CONTROLLER_NAME == 'Index'): ?>常用操作<?php else: ?>子菜单<?php endif; ?> --> </div>
        <!-- <ul>
            <?php if(is_array($sub_menu)): foreach($sub_menu as $key=>$sv): ?><li><a href="<?php echo ($sv["url"]); ?>"><?php echo ($sv["title"]); ?></a></li><?php endforeach; endif; ?>
        </ul> -->
    </div>
</div>
<style type="text/css">
    #aat a{
        cursor: pointer;
    }
</style>
<script type="text/javascript">

    //所有机构类型
    function add_all_jigou(id){
        var all_jigou = new Array('公证处','律师事务所','法律援助','人民调解机构','法律服务所','司法鉴定所','司法所','司法行政机关');
        var jigou_type = '<ul style="margin-left:10px">';
        var jigou_len = all_jigou.length;
        for(var k=0;k<jigou_len;k++){
            jigou_type += '<li><a href="javascript:;" style="background:url(/Public/Admin/Img/ico-x.gif) no-repeat 15px 12px"><span class="spanClick" type="true" index="'+ id +'">'+all_jigou[k]+'</span></a></li>';
        }
        jigou_type += '</ul>';
        return jigou_type;
    }
    var a = add_all_jigou('--');
    // console.log(a);
    //
   function arrtohtml(html,arr){
        var html="";
        html += '<ul style="margin-left:10px">';
        for(var i in arr){
            if(arr[i]['son'].length > 0){
                html += '<li><a href="javascript:;"><span class="spanClick" type="true" index="'+arr[i]['id']+'">'+arr[i]['name']+'</span></a>';
                html += arrtohtml(html,arr[i]['son']);
            }else{
                html += '<li><a href="javascript:;" style="background:url(/Public/Admin/Img/ico-x.gif) no-repeat 15px 12px"><span class="spanClick" type="true" index="'+arr[i]['id']+'">'+arr[i]['name']+'</span>' ;
                html+='<ul style="margin-left:10px">';
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="0" index="'+arr[i]['id']+'">全部</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="1" index="'+arr[i]['id']+'">公证处</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="2" index="'+arr[i]['id']+'">律师事务所</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="3" index="'+arr[i]['id']+'">法律援助</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="4" index="'+arr[i]['id']+'">人民调解机构</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="5" index="'+arr[i]['id']+'">法律服务所</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="6" index="'+arr[i]['id']+'">司法鉴定所</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="7" index="'+arr[i]['id']+'">司法所</san></a></li>'
                html+='<li><a href="javascript:;"><san class="spanClick" type="true" jg="8" index="'+arr[i]['id']+'">司法行政机关</san></a></li>'
                html+='</ul>'
            }
            html += '</a></li>';
        }
        html += '</ul>';
        return html;
    }
    $.ajax({
        url:'https://sys.t7c.net/index.php/home/Data/tree',
        type:'post',
        async:false,
        data:{},
        dataType:'json',
        success:function(data){
            console.log(data)

            var html = arrtohtml('',data.data);
            $('.subMenuList').html('<div class="itemTitle"><!-- <?php if(CONTROLLER_NAME == 'Index'): ?>常用操作<?php else: ?>子菜单<?php endif; ?> --> </div>'+html);
            $('.subMenuList>ul').attr("id",'aat');
            $('.subMenuList>ul').css("margin",'0');
            $('#aat ul').css("display",'none');
            $('.subMenuList>ul>li').attr("class",'nav-item');
        }
    })
    var index;
    $('.spanClick').on('click',function(event){
        index=$(this).attr("index");
        $("#jg").val($(this).attr('jg'));
        $(this).css('color','#fff').parent().parent().siblings().children('a').children('.spanClick').css('color','#b9ddff');
        event.stopPropagation()
    })
    $('#aat').on('click','a',function(){
        /*if (!$('.nav').hasClass('nav-mini')) {*/
        if($(this).next().length!=0){
            if ($(this).next().css('display') == "none") {
              	$(this).parent().siblings('li').children('ul').slideUp(200);
                $(this).next('ul').slideDown(300);
                $(this).css("background","url(/Public/Admin/Img/ico-x.gif) no-repeat 15px 12px");
            }else{
                //收缩已展开
                $(this).next('ul').slideUp(300);
                $('.nav-item.nav-show').removeClass('nav-show');
                $(this).next('ul').children().children().children('span').css('color','#b9ddff');
                $(this).css("background","url(/Public/Admin/Img/ico-y.gif) no-repeat 15px 12px");
            }
        }
        //}
    });
</script>

                <div class="Item hr mainBodyRight" style="width: 1690px;">
                    <!-- <select id="jg" class="jg">
                        <option value="0">全部</option>
                        <option value="1">公证处</option>
                        <option value="2">律师事务所</option>
                        <option value="3">法律援助</option>
                        <option value="4">人民调解机构</option>
                        <option value="5">法律服务所</option>
                        <option value="6">司法鉴定所</option>
                        <option value="7">司法所</option>
                        <option value="8">司法行政机关</option>
                    </select> -->
                    <input type="hidden" id="jg" value="">
                    <!-- <select id="type" class="type">
                        <option value="0">机构</option>
                        <option value="1">人员</option>
                    </select>
                    <button class="btn btns" id="btns">确定</button> -->
                    <div style="width: 150px;float: right;"><button type="button" id="btnAdd" class="btn btnAdd">添加机构</button></div>
                </div>
                <div id="iframe" style="float:right;width:1650px;height:800px;border:0;"></div>

            </div>
        </div>
        <div class="clear"></div>
        <script type="text/javascript">
    $(window).resize(autoSize);
    $(function(){
        autoSize();
        $(".loginOut").click(function(){
            var url=$(this).attr("href");
            popup.confirm('你确定要退出吗？','你确定要退出吗',function(action){
                if(action == 'ok'){ window.location=url; }
            });
            return false;
        });

        var time=self.setInterval(function(){$("#today").html(date("Y-m-d H:i:s"));},1000);


    });

</script>
        <script type="text/javascript">
            function showFirme(test){
                $.ajax({
                    url:test,
                    cache : true,
                    async : false,
                    success : function(html) {
                        $("#iframe").html(html);
                    }
                })
            }
            $(function(){
              //右侧内容宽度高度
              	//body宽度document.body.offsetWidth
              	var body_width = document.body.offsetWidth;
              	console.log('---' + body_width);
              	var mainBodyRight = body_width - 250;
              	var iframeWidth = mainBodyRight - 20;
              	$(".mainBodyRight").css('width',mainBodyRight + "px");
              	$("#iframe").css('width',iframeWidth + "px");
              	//$("#Right").css('width',iframeWidth + "px");
              //
                $(".del").click(function(){
                    // alert('123456');
                    return false;
                    var delLink=$(this).attr("link");
                    $this = $(this);
                    popup.confirm('你真的打算删除2【<b>'+$(this).attr("name")+'</b>】吗?','温馨提示',function(action){
                        if(action == 'ok'){
                            delByLink(delLink,$this);
                            //top.window.location.href=delLink;
                        }
                    });
                    return false;
                });
                var addHref;
                $('#jg').change(function(){
                    if($(this).val()!=0){
                        var type=$('#type').val();
                        // var typeHTML="";
                        if(type==0){
                            if($(this).val()==1){
                                addHref='<?php echo U('Institution/gzc_add');?>?qy='+index;
                            }else if($(this).val()==2){
                                addHref='<?php echo U('Institution/ls_add');?>?qy='+index;
                            }else if($(this).val()==3){
                                addHref='<?php echo U('Institution/yz_add');?>?qy='+index;
                            }else if($(this).val()==4){
                                addHref='<?php echo U('Institution/tj_add');?>?qy='+index;
                            }else if($(this).val()==5){
                                addHref='<?php echo U('Institution/fw_add');?>?qy='+index;
                            }else if($(this).val()==6){
                                addHref='<?php echo U('Institution/jd_add');?>?qy='+index;
                            }else if($(this).val()==7){
                                addHref='<?php echo U('Institution/sfs_add');?>?qy='+index;
                            }else if($(this).val()==8){
                                addHref='<?php echo U('Institution/xzjg_add');?>?qy='+index;
                            }
                            $('.btnAdd').html('添加'+$(this).children("option:selected").text());
                            $('.btnAdd').attr('onclick','showFirme("'+ addHref +'")');
                            $('.btnAdd').parent().show();
                        }else{
                            $('.btnAdd').parent().hide();
                        }
                    }else{
                        $('.btnAdd').parent().hide();
                    }
                })
                $('#type').change(function(){
                    var type=$('#jg').val();
                    if($(this).val()!=0){
                        $('.btnAdd').parent().hide();
                    }else{
                        if(type!=0){
                            if(type==1){
                                addHref='<?php echo U('Institution/gzc_add');?>?qy='+index;
                            }else if(type==2){
                                addHref='<?php echo U('Institution/ls_add');?>?qy='+index;
                            }else if(type==3){
                                addHref='<?php echo U('Institution/yz_add');?>?qy='+index;
                            }else if(type==4){
                                addHref='<?php echo U('Institution/tj_add');?>?qy='+index;
                            }else if(type==5){
                                addHref='<?php echo U('Institution/fw_add');?>?qy='+index;
                            }else if(type==6){
                                addHref='<?php echo U('Institution/jd_add');?>?qy='+index;
                            }else if(type==7){
                                addHref='<?php echo U('Institution/sfs_add');?>?qy='+index;
                            }
                            $('.btnAdd').html('添加'+$('#jg').children("option:selected").text());
                            $('.btnAdd').attr('onclick','showFirme("'+ addHref +'")');
                            $('.btnAdd').parent().show();
                        }else{
                            $('.btnAdd').parent().hide();
                        }
                    }
                })
                $('body').on('click','.btns',function(){
                    jg=$('#jg').val();
                    type=$('#type').val();
                    if(type!=0){
                        $('.btnAdd').parent().hide();
                    }
                    $.ajax({
                        type:'get',
                        url:'<?php echo U('Institution/jg');?>',
                        data:{
                            qy:index,
                            type:jg,
                            cate:type
                        },
                        dataType:'',
                        success:function(data){
                            // console.log(data);
                            $('#iframe').html(data);
                            var html;
                            if(jg==1){
                                addHref='<?php echo U('Institution/gzc_add');?>?qy='+index;
                                html='<?php echo U('Institution/right');?>';
                            }else if(jg==2){
                                addHref='<?php echo U('Institution/ls_add');?>?qy='+index;
                                html='<?php echo U('Institution/ls');?>';
                            }else if(jg==3){
                                addHref='<?php echo U('Institution/yz_add');?>?qy='+index;
                                html='<?php echo U('Institution/yz');?>';
                            }else if(jg==4){
                                addHref='<?php echo U('Institution/tj_add');?>?qy='+index;
                                html='<?php echo U('Institution/tj');?>';
                            }else if(jg==5){
                                addHref='<?php echo U('Institution/fw_add');?>?qy='+index;
                                html='<?php echo U('Institution/fw');?>';
                            }else if(jg==6){
                                addHref='<?php echo U('Institution/jd_add');?>?qy='+index;
                                html='<?php echo U('Institution/jd');?>';
                            }else if(jg==7){
                                addHref='<?php echo U('Institution/sfs_add');?>?qy='+index;
                                html='<?php echo U('Institution/sfs');?>';
                            }
                            $('.btnAdd').html('添加'+$('#jg').children("option:selected").text());
                            $('.btnAdd').attr('onclick','showFirme("'+ addHref +'")');
                            // showFirme(html);
                            // if(data.state==1){
                                // $('#iframe').attr('src','right.html');
                                // self.location.reload();
                                // document.getElementById('iframe').window.location.reload();
                            // }
                        }
                    })
                })
            });
            function changeAttr(id,v){
                $.get('<?php echo U("News/changeAttr");?>?id='+id,function(data){
                    if(data.status==1){
                        $(v).html(data.info);
                    }
                });
            }
            function changeStatus(id,v){
                $.get('<?php echo U("News/changeStatus");?>?id='+id,function(data){
                    if(data.status==1){
                        $(v).html(data.info);
                    }
                });
            }
                   $(".spanClick").on('click',function(){
                    jg=$('#jg').val();
                    type=$('#type').val();
                  	var jg_name = $(this).text();
                   // if(type!=0){
                        //$('.btnAdd').parent().hide();
                   // }
                    $.ajax({
                        type:'get',
                        url:'<?php echo U('Institution/jg');?>',
                        data:{
                            qy:index,
                            type:jg,
                            cate:type
                        },
                        dataType:'',
                        success:function(data){
                            console.log(data);
                            $('#iframe').html(data);

                            var html;
                            if(jg==1){
                                addHref='<?php echo U('Institution/gzc_add');?>?qy='+index;
                                html='<?php echo U('Institution/right');?>';
                            }else if(jg==2){
                                addHref='<?php echo U('Institution/ls_add');?>?qy='+index;
                                html='<?php echo U('Institution/ls');?>';
                            }else if(jg==3){
                                addHref='<?php echo U('Institution/yz_add');?>?qy='+index;
                                html='<?php echo U('Institution/yz');?>';
                            }else if(jg==4){
                                addHref='<?php echo U('Institution/tj_add');?>?qy='+index;
                                html='<?php echo U('Institution/tj');?>';
                            }else if(jg==5){
                                addHref='<?php echo U('Institution/fw_add');?>?qy='+index;
                                html='<?php echo U('Institution/fw');?>';
                            }else if(jg==6){
                                addHref='<?php echo U('Institution/jd_add');?>?qy='+index;
                                html='<?php echo U('Institution/jd');?>';
                            }else if(jg==7){
                                addHref='<?php echo U('Institution/sfs_add');?>?qy='+index;
                                html='<?php echo U('Institution/sfs');?>';
                            }
                            $('.btnAdd').html('添加' + jg_name);
                            $('.btnAdd').attr('onclick','showFirme("'+ addHref +'")');

                        }
                    })
            });
        </script>
    </body>
</html>