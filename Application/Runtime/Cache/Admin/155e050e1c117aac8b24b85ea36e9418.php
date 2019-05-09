<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>司法行政机关列表-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='司法行政机关列表'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


    </head>
    <body>
        <div id="Right" style="float:none;width:100%">
            <div class="Item hr">
                <div class="current">司法行政机关列表</div>
                <!-- <div style="width: 150px;float: right;"><button type="button" class="btn" onclick="window.location.href='<?php echo U('Institution/xjk_add');?>'">添加宣教科</button><button type="button" class="btn" onclick="window.location.href='<?php echo U('Institution/xjk_member_add');?>'">添加机构人员</button></div> -->
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
                <thead>
                    <tr>
                        <td>机构编码</td>
                        <td>机构名称</td>
                        <td>联系电话</td>
                        <td>行政区划</td>
                        <td>负责人</td>
                        <td>执业区域</td>
                        <td>添加时间</td>
                        <td>人员信息</td>
                        <td width="180">操作</td>
                    </tr>
                </thead>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" id="<?php echo ($vo["id"]); ?>">
                        <td><?php echo ($vo["jgbm"]); ?></td>
                        <td><?php echo ($vo["jgmc"]); ?></td>
                        <td><?php echo ($vo["lxdh"]); ?></td>
                        <td><?php echo ($vo["xzqh"]); ?></td>
                        <td><?php echo ($vo["fzr"]); ?></td>
                        <td><?php echo ($vo["qy"]); ?></td>
                        <td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>
                        <td><a id="<?php echo ($vo["id"]); ?>" class="memberList_xzjg" href="javascript:;">人员列表 </a></td>
                        <td>
                        <!-- <?php if($cate == 1): ?>[ <a id="<?php echo ($vo["id"]); ?>" class="memberList_xzjg" href="javascript:;">人员列表 </a> ]<?php endif; ?> -->
                        [ <a id="<?php echo ($vo["id"]); ?>" class="editList_xzjg" href="javascript:;">编辑 </a> ][ <a link="<?php echo U('Institution/xjk_del/',array('id'=>$vo['id']));?>" href="javascript:void(0)" name="<?php echo ($vo["name"]); ?>" class="del">删除 </a> ]</td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr><td colspan="6" align="right" id="page"><?php echo ($page); ?></td></tr>
            </table>
        </div>
        <div class="clear"></div>
        <!-- <script type="text/javascript">
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

</script> -->
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
                $('body').on('click','.memberList_xzjg',function(){
                    var id=$(this).attr('id');
                    var url='/index.php/conist/Institution/xzjg_member_add?gid='+id;
                    $("#btnAdd",window.parent.document).attr('onclick','showFirme("'+ url +'")');
                    $("#btnAdd",window.parent.document).html('添加行政机关人员');
                    $("#btnAdd",window.parent.document).parent().show();
                    var urls='/index.php/conist/Institution/xzjg_member?id='+id;
                    showFirme(urls);
                })
            });
            $('body').on('click','.editList_xzjg',function(){
                var id=$(this).attr('id');
                var url='/index.php/conist/Institution/xzjg_add?id='+id;
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
            $('#page a').click(function(e){
                 e.preventDefault();

                 var str = $(this).attr('href');
                 var str_arr = str.split('/');
                 var qy = str_arr[str_arr.indexOf('qy')+1];//区域
                 var type = str_arr[str_arr.indexOf('type')+1];//type
                 var cate = str_arr[str_arr.indexOf('cate')+1];//cate
                 var p = str_arr[str_arr.indexOf('p')+1];//p
                    $.ajax({
                        type:'get',
                        url:'<?php echo U('Institution/jg');?>',
                        data:{
                            qy:qy,
                            type:type,
                            cate:cate,
                            p:p
                        },
                        dataType:'',
                        success:function(data){
                            $('#iframe').html(data);
                        }
                    })
            });
        </script>
    </body>
</html>