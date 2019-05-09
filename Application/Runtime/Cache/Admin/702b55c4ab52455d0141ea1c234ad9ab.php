<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
    <?php $addCss=""; $addJs=""; $currentNav ='编辑司法鉴定所'; ?>
    <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">


</head>
<body>
        <div id="Right" style="float:none;width:100%;height: 750px;">
            <div class="contentArea">
                <div class="Item hr">
                    <div class="current">编辑司法鉴定所</div>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1" style="margin-bottom: 50px;">
                        <tr>
                            <th width="120">机构编号:</th>
                            <td>
                                <input type="text" name="jgbh" class="input uname" size="40" value="<?php echo ($list["jgbh"]); ?>"/>
                            </td>
                            <th width="120">机构名称:</th>
                            <td>
                                <input type="text" name="jgmc" class="input uname" size="40" value="<?php echo ($list["jgmc"]); ?>"/>
                            </td>
                            <th width="120">机构许可证号:</th>
                            <td>
                                <input type="text" name="jgxkzh" class="input uname" size="40" value="<?php echo ($list["jgxkzh"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构首次获准登记日期:</th>
                            <td>
                                <input type="date" name="jgschzdjrq" class="input uname" size="40" value="<?php echo ($list["jgschzdjrq"]); ?>"/>
                            </td>
                            <th width="120">机构有效期开始时间:</th>
                            <td>
                                <input type="date" name="jgyxqkssj" class="input uname" size="40" value="<?php echo ($list["jgyxqkssj"]); ?>"/>
                            </td>
                            <th width="120">机构有效期结束时间:</th>
                            <td>
                                <input type="date" name="jgyxqjssj" class="input uname" size="40" value="<?php echo ($list["jgyxqjssj"]); ?>"/>
                            </td>
                        </tr>
                      <tr>
                            <th width="120">机构业务范围:</th>
                            <td>
                                <input type="text" name="jgywfw" class="input uname" size="40" value="<?php echo ($list["jgywfw"]); ?>"/>
                            </td>
                            <th width="120">机构颁证机关:</th>
                            <td>
                                <input type="text" name="jgbzjg" class="input uname" size="40" value="<?php echo ($list["jgbzjg"]); ?>"/>
                            </td>
                            <th width="120">颁证日期:</th>
                            <td>
                                <input type="date" name="bzrq" class="input uname" size="40" value="<?php echo ($list["bzrq"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构所在行政区:</th>
                            <td>
                                <input type="text" name="jgszxzq" class="input uname" size="40" value="<?php echo ($list["jgszxzq"]); ?>" />
                            </td>
                            <th width="120">机构住所:</th>
                            <td>
                                <input type="text" name="dz" class="input uname" size="40" value="<?php echo ($list["dz"]); ?>"/>
                            </td>
                            <th width="120">联系人:</th>
                            <td>
                                <input type="text" name="lxr" class="input uname" size="40" value="<?php echo ($list["lxr"]); ?>"/>
                            </td>
                            <!-- <th width="120">上级行政机关:</th>
                            <td>
                                <input type="text" name="sjxzjg" class="input uname" size="40" value="<?php echo ($list["sjxzjg"]); ?>"/>
                            </td> -->
                        </tr>
                        <tr>
                            <th width="120">住所电子邮箱:</th>
                            <td>
                                <input type="text" name="zsdzyx" class="input uname" size="40" value="<?php echo ($list["zsdzyx"]); ?>"/>
                            </td>
                            <th width="120">住所传真:</th>
                            <td>
                                <input type="text" name="zscz" class="input uname" size="40" value="<?php echo ($list["zscz"]); ?>"/>
                            </td>
                            <th width="120">住所电话:</th>
                            <td>
                                <input type="text" name="dh" class="input uname" size="40" value="<?php echo ($list["dh"]); ?>"/>
                            </td>
                        </tr>
                         <tr>
                            <th width="120">机构性质:</th>
                            <td>
                                <input type="text" name="jgxz" class="input uname" size="40" value="<?php echo ($list["jgxz"]); ?>"/>
                            </td>
                            <th width="120">法定代表人姓名:</th>
                            <td>
                                <input type="text" name="fddbrxm" class="input uname" size="40" value="<?php echo ($list["fddbrxm"]); ?>"/>
                            </td>
                            <th width="120">法定代表人性别:</th>
                            <td>
                                <input type="text" name="fddbrxb" class="input uname" size="40" value="<?php echo ($list["fddbrxb"]); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">机构负责人姓名:</th>
                            <td>
                                <input type="text" name="jgfzrxm" class="input uname" size="40" value="<?php echo ($list["jgfzrxm"]); ?>"/>
                            </td>
                            <th width="120">机构负责人性别:</th>
                            <td>
                                <input type="text" name="jgfzrxb" class="input uname" size="40" value="<?php echo ($list["jgfzrxb"]); ?>"/>
                            </td>
                            <th width="120">机构员工数量:</th>
                            <td>
                                <input type="text" name="jgygsl" class="input uname" size="40" value="<?php echo ($list["jgygsl"]); ?>"/>
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
                            <th width="120">机构简介:</th>
                            <td>
                                <textarea name="jgjj" cols="30" rows="10"><?php echo ($list["jgjj"]); ?></textarea>
                            </td>
                            <th width="120">状态:</th>
                            <td>
                                <input type="radio" name="zt" value="1" <?php if($list['zt'] == 1): ?>checked<?php endif; ?>>有效
                                <input type="radio" name="zt" value="0" <?php if($list['zt'] == 0): ?>checked<?php endif; ?>>无效
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
                        </tr>
                        <tr >
                            <th>鉴定类别：</th>
                            <td>
                                <?php if(is_array($jd_type)): $i = 0; $__LIST__ = $jd_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="checkbox" name="jd_type[]" value="<?php echo ($vo["id"]); ?>" <?php if(in_array($vo['id'],$jd_type_checked)): ?>checked<?php endif; ?>/><?php echo ($vo["title"]); endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>"/>
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
             var url='/index.php/conist/Institution/jd_add';
             commonAjaxSubmits(url);
             return false;
            });
            });
</script>
</body>
</html>