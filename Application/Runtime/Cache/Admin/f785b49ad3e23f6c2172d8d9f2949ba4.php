<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑援助人员'; ?>
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
                    <div class="current">编辑援助人员</div>
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
                            <th width="120">用户密码:</th>
                            <td>
                                <input type="password" name="pwd" class="input uname" size="40" value="<?php echo ($list["pwd"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">政治面貌:</th>
                            <td>
                                <input type="text" name="zzmm" class="input uname" size="40" value="<?php echo ($list["zzmm"]); ?>"/>
                            </td>
                            <th width="120">文化程度:</th>
                            <td>
                                <input type="text" name="whcd" class="input uname" size="40" value="<?php echo ($list["whcd"]); ?>"/>
                            </td>
                            <th width="120">联系电话:</th>
                            <td>
                                <input type="text" name="lxdh" class="input uname" size="40" value="<?php echo ($list["lxdh"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">照片:</th>
                            <td>
                                <input type="file" name="zp" class="input uname" size="40" value="<?php echo ($list["zp"]); ?>"/>
                            </td>
                            <th width="120">身份证号:</th>
                            <td>
                                <input type="text" name="sfzh" class="input uname" size="40" value="<?php echo ($list["sfzh"]); ?>"/>
                            </td>
                            <th width="120">证件类型:</th>
                            <td>
                                <input type="text" name="zjlx" class="input uname" size="40" value="<?php echo ($list["zjlx"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">所属机构标识:</th>
                            <td>
                                <?php if($list['gzdw'] != ''): ?><input type="text" name="ssjg" class="input uname" size="40" value="<?php echo ($list["ssjg"]); ?>" readonly="readonly" />
                                <?php else: ?>
                                <input type="text" name="ssjg" class="input uname" size="40" value="<?php echo ($gid); ?>" readonly="readonly" /><?php endif; ?>
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
                                <input type="text" name="zy" class="input uname" size="40" value="<?php echo ($list["zy"]); ?>"/>
                            </td>
                            <th width="120">手机号码:</th>
                            <td>
                                <input type="text" name="sjhm" class="input uname" size="40" value="<?php echo ($list["sjhm"]); ?>"/>
                            </td>
                            <th width="120">电子邮件</th>
                            <td>
                                <input type="text" name="dzyj" class="input uname" size="40" value="<?php echo ($list["dzyj"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">人员类别:</th>
                            <td>
                                <input type="text" name="rylb" class="input uname" size="40" value="<?php echo ($list["rylb"]); ?>"/>
                            </td>
                            <th width="120">工作单位:</th>
                            <td>
                                <input type="text" name="gzdw" class="input uname" size="40" value="<?php echo ($list["gzdw"]); ?>"/>
                            </td>
                            <th width="120">执业证号:</th>
                            <td>
                                <input type="text" name="zyzh" class="input uname" size="40" value="<?php echo ($list["zyzh"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">是否在岗:</th>
                            <td>
                                <input type="text" name="sfzg" class="input uname" size="40" value="<?php echo ($list["sfzg"]); ?>"/>
                            </td>
                            <th width="120">人员状态:</th>
                            <td>
                                <input type="text" name="ryzt" class="input uname" size="40" value="<?php echo ($list["ryzt"]); ?>"/>
                            </td>
                            <th width="120">人员编制:</th>
                            <td>
                                <input type="text" name="rybz" class="input uname" size="40" value="<?php echo ($list["rybz"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">业务专长:</th>
                            <td>
                                <input type="text" name="ywzc" class="input uname" size="40" value="<?php echo ($list["ywzc"]); ?>"/>
                            </td>
                            <th width="120">职务:</th>
                            <td>
                                <input type="text" name="zw" class="input uname" size="40" value="<?php echo ($list["zw"]); ?>"/>
                            </td>
                            <th width="120">资格证号:</th>
                            <td>
                                <input type="text" name="zgzh" class="input uname" size="40" value="<?php echo ($list["zgzh"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">职业资格</th>
                            <td>
                                <input type="text" name="zyzg" class="input uname" size="40" value="<?php echo ($list["zyzg"]); ?>"/>
                            </td>
                            <th width="120">个人简介:</th>
                            <td>
                                <textarea name="grjj"/><?php echo ($list["grjj"]); ?></textarea>
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
                var url='/index.php/conist/Institution/yz_member?id='+id;
                showFirme(url)
             })
            $(".submit").click(function(){

             if($('#title').val()==''){
                 popup.error('标题不能为空！');
                 return false;
             }
             var url='/index.php/conist/Institution/yz_member_add';
             commonAjaxSubmits(url);
             return false;
            });
            });
</script>
</body>
</html>