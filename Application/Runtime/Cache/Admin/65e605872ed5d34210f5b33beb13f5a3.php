<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>用户管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='用户列表'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


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
                        <div class="current">前台用户管理</div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
                        <thead>
                            <tr>
                                <td>id</td>
                                <td>姓名</td>
                                <td>性别</td>
                                <td>手机</td>
                                <td>类型</td>
                                <td>注册时间</td>
                                <!-- <td>状态</td> -->
                                <?php if(in_array(($user_list), explode(',',"1,2"))): ?><td width="180">操作</td><?php endif; ?>
                            </tr>
                        </thead>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" id="<?php echo ($vo["id"]); ?>">
                               <td><?php echo ($vo["id"]); ?></td>
                               <td><a href="/index.php/conist/User/detail?id=<?php echo ($vo["id"]); ?>"><?php echo ($vo["xm"]); ?></a></td>
                               <td><?php echo ($vo["xb"]); ?></td>
                               <td><?php echo ($vo["lxdh"]); ?></td>
                               <td><?php echo ($vo["yhlx"]); ?></td>
                               <td><?php echo ($vo["zcsj"]); ?></td>
                               <!-- <td><a href="javascript:void(0);" title="激活" onclick="changeAttr(<?php echo ($vo["id"]); ?>,this)"><img src="/Public/Img/action_<?php echo ($vo["state"]); ?>.png" border="0"></a></td> --><!-- 
                                <td>[ <a href="/index.php/conist/User/wd_edit?id=<?php echo ($vo["id"]); ?>">编辑 </a> ] [ <a link="<?php echo U('News/wd_del/',array('id'=>$vo['id']));?>" href="javascript:void(0)" name="<?php echo ($vo["name"]); ?>" class="del">删除 </a> ]</td> -->
                                <?php if(in_array(($user_list), explode(',',"1,2"))): ?><td style="display: flex;padding: 0 0 6px 0;margin: 0;justify-content: space-around;">
                                        <?php if($vo["zt"] == 1): ?><button class="stop-use" type="button" style="color:#fff;background-color: #3bb4f2;border-color: #3bb4f2;" onclick="changeAttr(<?php echo ($vo["id"]); ?>,this);" data-status="0">停用</button>
                                            <?php else: ?>
                                            <button class="can-use" type="button" style="color:#fff;background-color: #5eb95e;border-color: #5eb95e;" onclick="changeAttr(<?php echo ($vo["id"]); ?>,this);" data-status="1">启用</button><?php endif; ?>
                                        <button class="edit-user" type="button" style="background-color: #7D9EC0;border-color: #7D9EC0;" onclick="">
                                            <a href="/index.php/conist/User/detail?id=<?php echo ($vo["id"]); ?>" style="color:#fff;">修改</a>
                                        </button>
                                        <?php if($vo["zt"] != 2): ?><button class="del" type="button" style="color:#fff;background-color: #dd514c;border-color: #dd514c;" data-name="<?php echo ($vo["xm"]); ?>" data-status="2" onclick="changeAttr(<?php echo ($vo["id"]); ?>,this)">删除</button><?php endif; ?>
                                    </td><?php endif; ?>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <tr><td><td colspan="10" align="right"><?php echo ($page); ?></td></td></tr>


                        <!-- <tr><td colspan="8" align="left">
                            <form action="/index.php/conist/User/worker" method="get">
                                用户名：<input type="text" name="name" value="<?php echo $_GET['name']; ?>" size="30" style="margin-right: 20px;">
                                ID：<input type="text" name="id" value="<?php echo $_GET['id']; ?>" size="30" style="margin-right: 20px;">
                                手机号：<input type="text" name="tel" value="<?php echo $_GET['tel']; ?>" size="30" style="margin-right: 20px;">
                                <input type="submit" value="搜索">
                            </form></td></tr> -->



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
            // $(function(){
            //     $(".del").click(function(){
            //         alert(123);
            //         // var delLink=$(this).attr("data-url");
            //         $this = $(this);
            //         popup.confirm('你真的打算删除【<b>'+$(this).attr("data-name")+'</b>】吗?','温馨提示',function(action){
            //             if(action == 'ok'){
            //                 // delByLink(delLink,$this);
            //                 var id = $(this).attr("data-id");
            //                 changeAttr(id,$(this));
            //             }
            //         });
            //         return false;
            //     });
            // });
            //修改用户状态 0停用 1启用 2删除
            function changeAttr(id,obj){
                var change_status = $(obj).attr('data-status');

                if(change_status == 2){
                    popup.confirm('你真的打算删除【<b>'+$(obj).attr("data-name")+'</b>】吗?','温馨提示',function(action){
                        if(action == 'ok'){
                            $.ajax({
                                type: "POST",
                                url: "https://sys.t7c.net/index.php/conist/User/change_user_status",
                                data: {
                                    id:id,
                                    status:change_status
                                },
                                success: function(data){
                                    alert(data.msg);
                                    $(obj).parent().parent().remove();
                                }
                            });
                        }
                    });
                    return false;
                } else {
                    $.ajax({
                    type: "POST",
                    url: "https://sys.t7c.net/index.php/conist/User/change_user_status",
                    data: {
                        id:id,
                        status:change_status
                    },
                    success: function(data){
                        alert(data.msg);
                        window.location.reload();
                    }
                    });
                }
            }

            // //修改用户信息
            // function editUser(){

            // }
        </script>
    </body>
</html>