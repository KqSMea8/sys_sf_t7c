<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑公证处'; ?>
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
                    <div class="current">编辑公证处</div>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1">
                        <tr>
                            <th width="120">机构编码：</th>
                            <td>
                                <input type="text" name="jgbm" class="input uname" size="40" value="<?php echo ($list["jgbm"]); ?>"/>
                            </td>
                            <th width="120">机构名称：</th>
                            <td>
                                <input type="text" name="jgmc" class="input uname" size="40" value="<?php echo ($list["jgmc"]); ?>"/>
                            </td>
                            <th width="120">机构简称：</th>
                            <td>
                                <input type="text" name="jgjc" class="input uname" size="40" value="<?php echo ($list["jgjc"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构代码:</th>
                            <td>
                                <input type="text" name="jgdm" class="input uname" size="40" value="<?php echo ($list["jgdm"]); ?>"/>
                            </td>
                            <th width="120">执业证号:</th>
                            <td>
                                <input type="text" name="zyzh" class="input uname" size="40" value="<?php echo ($list["zyzh"]); ?>"/>
                            </td>
                            <th width="120">是否承办涉外公证业务:</th>
                            <td>
                                <input type="radio" name="sfcbswgzyw" value="1" <?php if($list['sfcbswgzyw'] == 1): ?>checked<?php endif; ?>>是
                                <input type="radio" name="sfcbswgzyw" value="0" <?php if($list['sfcbswgzyw'] == 0): ?>checked<?php endif; ?>>否
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构设立时间：</th>
                            <td>
                                <input type="date" name="jgslsj" class="input uname" size="40" value="<?php echo ($list["jgslsj"]); ?>" />
                            </td>
                            <th width="120">执业区域：</th>
                            <td>
                                <?php if($qy_id != ''): ?><input type="hidden" name="qy" value="<?php echo ($qy_id); ?>">
                                        <?php if(is_array($qy)): $i = 0; $__LIST__ = $qy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['id'] == $qy_id): echo ($vo["name"]); endif; endforeach; endif; else: echo "" ;endif; ?>
                                    <?php else: ?>
                                    <select name="qy">
                                    <option value="0">请选择</option>
                                        <?php if(is_array($qy)): $i = 0; $__LIST__ = $qy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $list['qy']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                     </select><?php endif; ?>
                            </td>
                            <th width="120">负责人：</th>
                            <td>
                                <input type="text" name="fzr" class="input uname" size="40" value="<?php echo ($list["fzr"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">发证机关:</th>
                            <td>
                                <input type="text" name="fzjg" class="input uname" size="40" value="<?php echo ($list["fzjg"]); ?>"/>
                            </td>
                            <th width="120">发证时间:</th>
                            <td>
                                <input type="date" name="fzsj" class="input uname" size="40" value="<?php echo ($list["fzsj"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">联系电话:</th>
                            <td>
                                <input type="text" name="dh" class="input uname" size="40" value="<?php echo ($list["dh"]); ?>"/>
                            </td>
                            <th width="120">传真号码:</th>
                            <td>
                                <input type="text" name="czhm" class="input uname" size="40" value="<?php echo ($list["czhm"]); ?>"/>
                            </td>
                            <th width="120">电子邮箱:</th>
                            <td>
                                <input type="text" name="dzyx" class="input uname" size="40" value="<?php echo ($list["dzyx"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">邮编:</th>
                            <td>
                                <input type="text" name="yb" class="input uname" size="40" value="<?php echo ($list["yb"]); ?>"/>
                            </td>
                            <th width="120">地址:</th>
                            <td>
                                <input type="text" name="dz" class="input uname" size="40" value="<?php echo ($list["dz"]); ?>"/>
                            </td>
                            <th width="120">官方网站（微博、微信公众号、网址）:</th>
                            <td>
                                <input type="text" name="gfwz" class="input uname" size="40" value="<?php echo ($list["gfwz"]); ?>"/>
                            </td>
                        </tr>
                        <tr>

                            <th width="120">形象标识:</th>
                            <td>
                                <input type="file" name="jglg"/>
                            </td>
                            <th width="120">经度:</th>
                            <td>
                                <input type="text" name="jd" class="input uname" size="40" value="<?php echo ($list["jd"]); ?>"/>
                            </td>
                            <th width="120">纬度:</th>
                            <td>
                                <input type="text" name="wd" class="input uname" size="40" value="<?php echo ($list["wd"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">简介:</th>
                            <td>
                                <textarea name="jgjj" cols="30" rows="10"><?php echo ($list["jgjj"]); ?></textarea>
                            </td>
                            <th width="120">状态:</th>
                            <td>
                                <input type="radio" name="zt" value="1" <?php if($list['zt'] == 1): ?>checked<?php endif; ?>>有效
                                <input type="radio" name="zt" value="0" <?php if($list['zt'] == 0): ?>checked<?php endif; ?>>无效
                            </td>

                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>"/>
                    <input type="hidden" name="pid" value="<?php echo ($pid); ?>"/>
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
                var url='/index.php/conist/Institution/jg?qy='+index+'&type='+jg+'&cate='+type;
                showFirme(url)
             })
            $(".submit").click(function(){
             if($('#title').val()==''){
                 popup.error('标题不能为空！');
                 return false;
             }
             var url='/index.php/conist/Institution/gzc_add';
             commonAjaxSubmits(url);
             return false;
            });
            });
</script>
</body>
</html>