<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑文书'; ?>
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
                    <div class="current">编辑文书</div>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">文书标题：</th>
                            <td>
                                <input type="text" name="title" class="input uname" size="40" value="<?php echo ($list["title"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">文书文件：</th>
                            <td>
                                <input type="file" name="path">
                            </td>
                        </tr>
                        <tr>
                            <th width="120"></th>
                            <td>
                                <?php if($list['path'] != ''): echo ($list['path']); endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">案卷号：</th>
                            <td>
                                <input type="text" name="files_id" class="input uname" size="40" value="<?php echo ($list["files_id"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">关键词：</th>
                            <td>
                                <textarea name="keyword" style="width: 400px; height: 150px;"><?php echo ($list["keyword"]); ?></textarea>
                            </td>
                        </tr>
                    </table>
                <div class="Item hr">
                    <div class="current">甲方</div>
                </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">姓名：</th>
                            <td>
                                <input type="text" name="name" class="input uname" size="40" value="<?php echo ($list["name"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">身份证号：</th>
                            <td>
                                <input type="text" name="id_card" class="input uname" size="40" value="<?php echo ($list["id_card"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">手机号：</th>
                            <td>
                                <input type="text" name="mobile" class="input uname" size="40" value="<?php echo ($list["mobile"]); ?>"/>
                            </td>
                        </tr>
                    </table>
                <div class="Item hr">
                    <div class="current">乙方</div>
                </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">姓名：</th>
                            <td>
                                <input type="text" name="name2" class="input uname" size="40" value="<?php echo ($list["name2"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">身份证号：</th>
                            <td>
                                <input type="text" name="id_card2" class="input uname" size="40" value="<?php echo ($list["id_card2"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">手机号：</th>
                            <td>
                                <input type="text" name="mobile2" class="input uname" size="40" value="<?php echo ($list["mobile2"]); ?>"/>
                            </td>
                        </tr>
                    </table>
                <div class="Item hr">
                    <div class="current">第三方</div>
                </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">姓名：</th>
                            <td>
                                <input type="text" name="name3" class="input uname" size="40" value="<?php echo ($list["name3"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">身份证号：</th>
                            <td>
                                <input type="text" name="id_card3" class="input uname" size="40" value="<?php echo ($list["id_card3"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">手机号：</th>
                            <td>
                                <input type="text" name="mobile3" class="input uname" size="40" value="<?php echo ($list["mobile3"]); ?>"/>
                            </td>
                        </tr>
                    </table>
                    <?php if($list['type'] != ''): ?><input type="hidden" name="type" value="<?php echo ($list["type"]); ?>"/>
                    <?php else: ?>
                    <input type="hidden" name="type" value="<?php echo ($_GET['type']); ?>"/><?php endif; ?>

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
        $(function(){
            $(".submit").click(function(){
                
             if($('#title').val()==''){
                 popup.error('标题不能为空！');
                 return false;
             }
             commonAjaxSubmit();
             return false;
            });
            });
</script>
</body>
</html>