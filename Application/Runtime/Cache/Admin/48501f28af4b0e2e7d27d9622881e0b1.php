<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑流转律师'; ?>
    <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="wrap"> <!-- <div id="Top">
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


    <div class="mainBody"> <div id="Left">
    <div id="control" class=""></div>
    <div class="subMenuList">
        <div class="itemTitle"><!-- <?php if(CONTROLLER_NAME == 'Index'): ?>常用操作<?php else: ?>子菜单<?php endif; ?> --> </div>
        <ul>
            <?php if(is_array($sub_menu)): foreach($sub_menu as $key=>$sv): if($sv["sub3"] != 'false'): ?><li class="togellernav"><a><?php echo ($sv["title"]); ?></a>
                        <ul style="margin-top: 0;margin-left: 40px;">
                            <?php if(is_array($sv['sub3'])): foreach($sv['sub3'] as $key=>$s): ?><!-- <li><a href="<?php echo ($s["url"]); ?>"><?php echo ($s["title"]); ?></a></li> -->
                                <!--  四级菜单开始-->
                                <?php if($s["sub4"] != 'false'): ?><li class="togellernav"><a><?php echo ($s["title"]); ?></a></li>
                                    <ul style="margin-top: 0;margin-left: 30px;">
                                        <?php if(is_array($s['sub4'])): foreach($s['sub4'] as $key=>$s4): ?><li><a href="<?php echo ($s4["url"]); ?>"><?php echo ($s4["title"]); ?></a></li><?php endforeach; endif; ?>
                                    </ul>
                                <?php else: ?>
                                    <li><a href="<?php echo ($s["url"]); ?>"><?php echo ($s["title"]); ?></a></li><?php endif; ?>
                                <!--  四级菜单结束--><?php endforeach; endif; ?>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo ($sv["url"]); ?>"><?php echo ($sv["title"]); ?></a></li><?php endif; endforeach; endif; ?>
        </ul>
    </div>
</div>
        <div id="Right">
            <div class="contentArea">
                <div class="Item hr">
                    <div class="current">编辑流转律师</div>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">流转时间：</th>
                            <td>
                                <input type="text" name="time" class="input uname" size="40" >
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构区域：</th>
                            <td>
                                <select name="area" class="jgrear">
                                <option value="0">请选择</option>
                                    <?php if(is_array($qy)): $i = 0; $__LIST__ = $qy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                 </select>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构：</th>
                            <td>
                                <select name="area" class="jgg">
                                <option value="0">请选择</option>
                                 </select>
                            </td>
                        </tr>
                        <tr class="aren" style="display: none;">
                            <th width="120">人员：</th>
                            <td>
                                <select name="id" class="ry">
                                <option value="0">请选择</option>
                                 </select>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>"/>
                    <div class="commonBtnArea" >
                        <span class="btn submit">提交</span>
                    </div>
                </form>
            </div>
        </div>
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
<script type="text/javascript" src="/Public/kindeditor/kindeditor.js"></script><script type="text/javascript" src="/Public/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
 //选择机构区域，类型
 var jgarea=0,jgtype=0;
 $(".jgrear").change(function(){
    $(".jgg").html("<option value='0'>请选择</option>");
            jgarea=$(".jgrear").find("option:selected").val();
            console.log(jgarea,jgtype)
            jgare()
 })
 function jgare(){
    $.ajax({
            type: "POST",
            url: "https://sys.t7c.net/index.php/home/Organ/choose",
            data: {
                area: jgarea,
                type: 2
            },
            dataType: 'json',
            success: function(data) {
               console.log(data)
               if(data.state==1){
                var html="";
                   for(var i=0;i<data.data.length;i++){
                      html+=` <option value="${data.data[i].id}">${data.data[i].jgmc}</option>`
                   }
                $(".jgg").append(html)
               }

            }
        })
 }
 //选择机构
 var ryid=0;
  $(".jgg").change(function(){
     $(".ry").html("<option value='0'>请选择</option>");
            ryid=$(".jgg").find("option:selected").val();
     show();
     ryy();

 })
  show();
  function show(){
    if(ryid!=0){
      $(".aren").show();
  }else{
    $(".aren").hide();
  }
  }
  //渲染人员数据
  //
  function ryy(){
     $.get('<?php echo U("Video/details");?>?id='+ryid+"&type=2",function(data){

        if(data.status==1){
           // $(v).html(data.info);
           console.log(data)
           var html="";
           for(var i=0;i<data.data.length;i++){
              html+=` <option value="${data.data[i].id}" index="${data.data[i].uid}">${data.data[i].xm}</option>`
           }
        $(".ry").append(html)
        }
    });

 }
 var mid,uid,type;
  $(".ry").change(function(){

            mid=$(".ry").find("option:selected").val();
            uid=$(".ry").find("option:selected").attr("index");

 })
 //https://sys.t7c.net/index.php/conist/Service/details
 $(".submit").click(function(){
    var time=$(".uname").val();
     $.ajax({
            type: "POST",
            url: "https://sys.t7c.net/index.php/conist/Handle/ls_flow_add",
            data: {
                mid: mid,
                uid:uid,
                jg_id:ryid,
                time:time
            },
            dataType: 'json',
            success: function(data) {
               console.log(data)
               if(data.status==1){
                 window.location.href=data.url;
               }else{
                alert(data.info)
            }

            }
        })
 })

        $(function(){
             var  content ;
             KindEditor.ready(function(K) {
                 content = K.create('.content',{
                     allowFileManager : true,
                     uploadJson:'/Public/kindeditor/php/upload_json.php?dirname=news',
                     afterBlur: function () { this.sync(); },
                 });
             });

             KindEditor.ready(function(K) {
                 K.create();
                 var editor = K.editor({
                     allowFileManager : true,
                     uploadJson:'/Public/kindeditor/php/upload_json.php?dirname=news'
                     //sdl:false
                 });
             });
            /*$(".submit").click(function(){
             if($('#title').val()==''){
                 popup.error('标题不能为空！');
                 return false;
             }
             commonAjaxSubmit();
             return false;
            });*/
        });

       
</script>
</body>
</html>