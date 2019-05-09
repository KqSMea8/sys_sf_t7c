<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>鉴定人列表-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='鉴定人列表'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


    </head>
    <body>
                <div id="Right" style="float:none;width:100%">
                    <div class="Item hr">
                        <div class="current">鉴定人列表</div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
                        <thead>
                            <tr>
                                <td>人员标识</td>
                                <td>姓名</td>
                                <td>工作单位</td>
                                <td>执业证号</td>
                                <td>性别</td>
                                <td>执业行政区域</td>
                                <td>添加时间</td>
                                <td width="150">操作</td>
                            </tr>
                        </thead>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" id="<?php echo ($vo["id"]); ?>">
                                <td><?php echo ($vo["id"]); ?></td>
                                <td><?php echo ($vo["xm"]); ?></td>
                                <td><?php echo ($vo["szjgbh"]); ?></td>
                                <td><?php echo ($vo["zyzh"]); ?></td>
                                <td><?php echo ($vo["xb"]); ?></td>
                                <td><?php echo ($vo["zyxzqy"]); ?></td>
                                <td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>
                                <td>[ <a id="<?php echo ($vo["id"]); ?>" gid="<?php echo ($gid); ?>" class="edit_List_jd" href="javascript:;">编辑 </a> ] [ <a link="<?php echo U('Institution/jd_member_del/',array('id'=>$vo['id'],'gid'=>$gid));?>" href="javascript:void(0)" name="<?php echo ($vo["xm"]); ?>" class="del">删除 </a> ]</td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <tr><td colspan="6" align="right"><?php echo ($page); ?></td></tr>
                    </table>
                </div>
        <script type="text/javascript">
            $(function(){
                $(".del").click(function(){
                    var delLink=$(this).attr("link");
                    $this = $(this);
                    popup.confirm('你真的打算删除【<b>'+$(this).attr("name")+'</b>】吗?','温馨提示',function(action){
                        if(action == 'ok'){
                            delByLink(delLink,$this);
                            //top.window.location.href=delLink;
                        }
                    });
                    return false;
                });
            });
            $('body').on('click','.edit_List_jd',function(){
                var id=$(this).attr('id');
                var gid=$(this).attr('gid');
                var url='/index.php/conist/Institution/jd_member_add?id='+id+'&gid='+gid;
                showFirme(url);
            })
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
        </script>
    </body>
</html>