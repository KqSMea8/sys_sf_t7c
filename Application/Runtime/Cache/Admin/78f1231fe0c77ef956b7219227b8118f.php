<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑司法所人员'; ?>
    <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


</head>
<body>
        <div id="Right" style="float:none;width:100%">
            <div class="contentArea">
                <div class="Item hr">
                    <div class="current">编辑司法所人员</div>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">姓名:</th>
                            <td>
                                <input type="text" name="xm" class="input uname" size="40" value="<?php echo ($list["xm"]); ?>"/>
                            </td>
                            <th width="120">性别:</th>
                            <td>
                                <input type="text" name="xb" class="input uname" size="40" value="<?php echo ($list["xb"]); ?>"/>
                            </td>
                            <th width="120">政治面貌:</th>
                            <td>
                                <input type="text" name="zzmm" class="input uname" size="40" value="<?php echo ($list["zzmm"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">司法所编码:</th>
                            <td>
                                <?php if($list['gzdw'] != ''): ?><input type="text" name="sfsbm" class="input uname" size="40" value="<?php echo ($list["sfsbm"]); ?>" readonly="readonly" />
                                <?php else: ?>
                                <input type="text" name="sfsbm" class="input uname" size="40" value="<?php echo ($sfsbm); ?>" readonly="readonly" /><?php endif; ?>
                            </td>
                            <th width="120">固定电话:</th>
                            <td>
                                <input type="text" name="lxdh" class="input uname" size="40" value="<?php echo ($list["lxdh"]); ?>"/>
                            </td>
                            <th width="120">人员编码:</th>
                            <td>
                                <input type="text" name="rybm" class="input uname" size="40" value="<?php echo ($list["rybm"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">照片:</th>
                            <td>
                                <input type="file" name="zp" class="input uname" size="40" value="<?php echo ($list["zp"]); ?>"/>
                            </td>
                            <th width="120">身份证号:</th>
                            <td>
                                <input type="text" name="sfzhm" class="input uname" size="40" value="<?php echo ($list["sfzhm"]); ?>"/>
                            </td>
                            <th width="120">出生年月:</th>
                            <td>
                                <input type="date" name="csny" class="input uname" size="40" value="<?php echo ($list["csny"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">人员类型:</th>
                            <td>
                                <input type="text" name="rylx" class="input uname" size="40" value="<?php echo ($list["rylx"]); ?>"/>
                            </td>
                            <th width="120">学历:</th>
                            <td>
                                <input type="text" name="xl" class="input uname" size="40" value="<?php echo ($list["xl"]); ?>"/>
                            </td>
                            <th width="120">民族:</th>
                            <td>
                                <input type="text" name="mz" class="input uname" size="40" value="<?php echo ($list["mz"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">专业:</th>
                            <td>
                                <input type="text" name="sxzy" class="input uname" size="40" value="<?php echo ($list["sxzy"]); ?>"/>
                            </td>
                            <th width="120">毕业院校:</th>
                            <td>
                                <input type="text" name="byyx" class="input uname" size="40" value="<?php echo ($list["byyx"]); ?>"/>
                            </td>
                            <th width="120">电子邮箱</th>
                            <td>
                                <input type="text" name="dzyx" class="input uname" size="40" value="<?php echo ($list["dzyx"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">手机:</th>
                            <td>
                                <input type="text" name="sjhm" class="input uname" size="40" value="<?php echo ($list["sjhm"]); ?>"/>
                            </td>
                            <th width="120">参加工作时间:</th>
                            <td>
                                <input type="date" name="cjgzsj" class="input uname" size="40" value="<?php echo ($list["cjgzsj"]); ?>"/>
                            </td>
                            <th width="120">参加司法所工作时间:</th>
                            <td>
                                <input type="date" name="cjsfsgzsj" class="input uname" size="40" value="<?php echo ($list["cjsfsgzsj"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">职务:</th>
                            <td>
                                <input type="text" name="zw" class="input uname" size="40" value="<?php echo ($list["zw"]); ?>"/>
                            </td>
                            <th width="120">业务专长</th>
                            <td>
                                <input type="text" name="ywzc" class="input uname" size="40" value="<?php echo ($list["ywzc"]); ?>"/>
                            </td>
                            <th width="120">用户密码:</th>
                            <td>
                                <input type="password" name="pwd" class="input uname" size="40" value="<?php echo ($list["pwd"]); ?>"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>"/>
                    <input type="hidden" name="gid" value="<?php echo ($gid); ?>"/>
                    <div class="commonBtnArea" style="width:100%;right:auto;">
                        <span class="btn submit">提交</span>
                        <span class="btn back">返回</span>
                    </div>
                </form>
            </div>
        </div>
<script type="text/javascript" src="/Public/kindeditor/kindeditor.js"></script><script type="text/javascript" src="/Public/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
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
             $(".back").click(function(){
                var id = $("input[name='gid']").val()
                var url='/index.php/conist/Institution/sfs_member?id='+id;
                showFirme(url)
             })
            $(".submit").click(function(){
             if($('#title').val()==''){
                 popup.error('标题不能为空！');
                 return false;
             }
             var url='/index.php/conist/Institution/sfs_member_add';
             commonAjaxSubmits(url);
             return false;
            });
            });
</script>
</body>
</html>