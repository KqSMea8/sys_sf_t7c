<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>审批列表-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='审批列表'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="/Public/Admin/Js/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="/Public/Admin/Css/WdatePicker.css">
        <script type="text/javascript" src="/Public/Admin/Js/WdatePicker.js"></script>
        
        
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
                    <div class="Item hr">
                        <div class="current">审批列表</div>
                    </div>
                    <form action="" method="get" id="quickForm">
                        审批状态:
                        <select name="status">
                            <option value="0">全部</option>
                            <option value="7">拒绝</option>
                            <option value="5">完成</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;时间：
                        <input type="text" class="Wdate input uname" onFocus="WdatePicker({lang:'zh-cn'})"  name="start_time" style="width:170px;margin-top:0" value="<?php echo ($_GET['start_time']); ?>">至
                        <input type="text" class="Wdate input uname" onFocus="WdatePicker({lang:'zh-cn'})"  name="end_time" style="width:200px;margin-top:0" value="<?php echo ($_GET['end_time']); ?>">
                        <input type="hidden" name="type" value="<?php echo ($_GET['type']); ?>">
                        区域：
                        <select name="qy">
                            <option value="">请选择</option>
                            <?php if(is_array($qy)): $i = 0; $__LIST__ = $qy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        人员：
                        <select name="jgry_id">
                            <option value="">请选择</option>
                            <?php if(is_array($jgry)): $i = 0; $__LIST__ = $jgry;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["xm"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <button class="btn">搜索</button>
                    </form>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>申请人</td>
                                <td>受理机构</td>
                                <?php if($type == 2): ?><td>投诉人员</td>
                                <?php else: ?>
                                <td>受理机构人员</td><?php endif; ?>
                                <td>状态</td>
                                <td>申请时间</td>
                                <td>完成时间</td>
                                <td>操作</td>
                            </tr>
                        </thead>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" id="<?php echo ($vo["id"]); ?>">
                                <td><?php echo ($vo["id"]); ?></td>
                                <td>
                                <?php if($vo['xm'] != ''): echo ($vo["xm"]); ?>
                                <?php else: ?>
                                <?php echo ($vo["proposer_name"]); endif; ?></td>
                                <td><?php echo ($vo["jg_name"]); ?></td>
                                <td><?php echo ($vo["jgry_name"]); ?></td>
                                <td><?php echo ($vo["status"]); ?></td>
                                <td><?php echo (date("Y-m-d H:i:s",$vo["ct_time"])); ?></td>
                                <td><?php if($vo['finish_time'] == 0): else: echo (date("Y-m-d H:i:s",$vo["finish_time"])); endif; ?></td>
                                <td>
                                    <!-- <?php if($vo['flag'] == 1): ?>-->
                                    <!-- [ <a href="/index.php/conist/Handle/set?id=<?php echo ($vo["id"]); ?>&cate=<?php echo ($cate); ?>&type=<?php echo ($type); ?>">指定人员 </a> ] -->
                                    <!--<?php endif; ?> -->
                                    [ <a href="/index.php/conist/Handle/set?id=<?php echo ($vo["id"]); ?>&cate=<?php echo ($cate); ?>&type=<?php echo ($type); ?>">指定人员 </a> ]
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <tr><td colspan="8" align="right"><?php echo ($page); ?></td></tr>
                    </table>
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
        <script type="text/javascript">
        </script>
    </body>
</html>
<script type="text/javascript">
    /*var url=window.location.search;
    var text=url.substr(url.length-1,1)
    console.log(text);*/

</script>