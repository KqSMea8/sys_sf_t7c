<?php
$DB_PREFIX = C('DB_PREFIX');
return array(
        'admin_big_menu' => array(
            'Index' => '首页',
            'User'=>'用户管理',
            'Judicial'=>'司法要闻',
            'Affair' => '政务公开',
            'Popularizelaw'=>'普法宣传',
            'Case'=>'案例管理',
            'Institution'=>'机构及人员',
            'Service'=>'咨询解答维护',
            'Video'=>'视频服务维护',
            'Writ'=>'文书查询维护',
            'News' => '参数数据设置',
            'Handle' => '在线申办项目维护',
            'Access' => '权限管理',
            'App' => '下载项维护',
            'Stat' => '统计',
        ),

        'admin_sub_menu' => array(
            'Common' => array(
                'Index/myInfo' => '修改密码',
                'Index/cache' => '缓存清理',
                // 'News/add' => '新闻发布',
            ),
            'User'=>array(
                'index'=>'前台用户管理',
                'worker'=>'工作人员管理',
                'special_personnel' => '社矫人员管理',
            ),
            'Judicial' => array(
                'index'=>'司法行政新闻列表',
                'judicial_type'=>'司法行政新闻分类',
                'image'=>'司法行政新闻封面',
            ),
            'Affair' => array(
                'index'=>'政务公开列表',
                'affair_type'=>'政务公开分类',
                'image'=>'政务公开封面',
            ),
            'Popularizelaw' => array(
                'index'=>'普法宣传列表',

                // 'index2'=>'普法宣传列表2',

                'exam_type'=>'普法考试类型',
                'text_type' => '普法新闻类型',
                'video_type' => '普法视频类型',
                'image'=>'普法宣传封面',
            ),
            'Case' => array(
                'index'=>'案例列表',
                'case_type'=>'案例分类',
                //'image'=>'封面图片',
            ),
            'Institution' => array(
                'index'=>'公证处列表',
                'ls'=>'律师事务所',
                'yz'=>'法律援助',
                'tj'=>'人民调解机构',
                'fw'=>'法律服务所',
                'jd'=>'司法鉴定所',
                'sfs'=>'司法所',
                'xzjg'=>'行政机关',
            ),
            'Writ' => array(
                'index?type=1' => '司法鉴定书',
                'index?type=2' => '人民调节书',
                'index?type=3' => '法律援助判决书',
            ),
            'Service' => array(
                'index' => '咨询区域维护',
                'serve_type' => '咨询类别维护',
                'serve_member' => '咨询人员维护',
            ),
            'Video' => array(
                'index' => '视频服务区域维护',
                'serve_video_type' => '视频服务类别维护',
                'serve_video_member' => '视频服务人员维护',
               // 'serve_video_canshu' => '视频服务参数维护',
            ),
            'News' => array(
                'index' => 'Banner管理',
                'agreement'=>'用户协议',
                'qy'=>'区域',
                'about' => '关于我们',
                'message' => '消息推送',
            ),
            'Handle' => array(
                'index' => '公证办理',
                'falvyz' => '法律援助申请',
                'tj' => '人民调解申请',
                'jd' => '司法鉴定申请',
                'ls' => '律师预约',
              	'orderType'=>'预约类别维护',
            ),
            'Access' => array(
                'index' => '后台用户',
                'nodeList' => '节点管理',
                'roleList' => '角色管理',
                'addAdmin' => '添加管理员',
                'addNode' => '添加节点',
                'addRole' => '添加角色',
            ),
            'App' => array(
                'index' => 'App版本号更新维护',
            ),
            'Stat' => array(
                'index?zcfs=1' => 'App用户',
                'index?zcfs=2' => '微信用户',
                'index?zcfs=3' => '终端用户',
                'index?zcfs=4' => '电脑端用户',
                'index?zcfs=5' => '注册用户数量统计',
            ),
        ),
        'admin_sub3_menu'=>array(
            'Handle/index'=>array(
                'business'=>'申办事项名称',
                'price'=>'申办事项要件及费用',
                'country'=>'使用地维护',
                'usee'=>'用途维护',
                'language'=>'翻译语言维护',
                'index?type=1'=>'申办统计',
                'index?type=2'=>'投诉统计',
                'index?type=3'=>'预约统计',
                'form?type=1'=>'相关例表',
                'notice?type=1'=>'公告',
            ),
            // 'Handle/falvyz'=>array(
            //     'proposer_type'=>'申请人类型',
            //     'falvyz?type=1'=>'申办统计',
            //     'falvyz?type=2'=>'投诉统计',
            //     'falvyz?type=3'=>'预约统计',
            //     'form?type=2'=>'相关例表',
            //     'notice?type=2'=>'公告',
            // ),
            // 法律援助
            'Handle/falvyz'=>array(
                'Handle/falvyz/sbxmwh'=>'申办项目维护',
                'Handle/falvyz/tongji'=>'法律援助统计',
                'Handle/falvyz/sprywh'=>'审批人员维护',
            ),
            // 'Handle/tj'=>array(
            //     'tj_type'=>'调节类别',
            //     'falvyz?type=1'=>'申办统计',
            //     'falvyz?type=2'=>'投诉统计',
            //     'falvyz?type=3'=>'预约统计',
            //     'form?type=3'=>'相关例表',
            //     'notice?type=3'=>'公告',
            // ),
            // 人民调解
            'Handle/tj' => array(
                'Handle/tj/sbxmwh' => '申办项目维护',
                'Handle/tj/tongji' => '人民调解统计',
                'Handle/tj/sprywh' => '审批人员维护',
            ),
            // 'Handle/jd'=>array(
            //     'jd_type'=>'鉴定类别',
            //     'jd?type=1'=>'申办统计',
            //     'jd?type=2'=>'投诉统计',
            //     'jd?type=3'=>'预约统计',
            //     'form?type=4'=>'相关例表',
            //     'notice?type=4'=>'公告',
            // ),
            // 司法鉴定
            'Handle/jd' => array(
                'Handle/jd/sbxmwh' => '申办项目维护',
                'Handle/jd/tongji' => '司法鉴定统计',
                'Handle/jd/sprywh' => '审批人员维护',
            ),
            'Handle/ls'=>array(
                'ls_flow'=>'律师流转',
                'ls?type=3'=>'预约统计',
                'ls?type=2'=>'投诉统计',
                'form?type=5'=>'相关例表',
                'notice?type=5'=>'公告',
            ),
          	'Handle/orderType'=>array(
            	'order_type'=>'预约类型',
            ),
            'Popularizelaw/index' => array(
                'index?cate=1' => '普法新闻列表',
                'index?cate=2' => '普法视频列表',
            ),
        ),

        //四级菜单（最后一级）
        'admin_sub4_menu' => array(
            //法律援助申请 申办项目维护
            'Handle/falvyz/sbxmwh' => array(
                'proposer_type'=>'申请人类型',
              	'yuanzhu_type'=>'援助类型',
              	'medical_institution'=>'医疗机构列表',
                'form?type=1'=>'相关例表',
                'notice?type=1'=>'公告',
            ),
            //法律援助申请 统计
            'Handle/falvyz/tongji' => array(
                'falvyz?type=1'=>'申办统计',
                'falvyz?type=2'=>'投诉统计',
                'falvyz?type=3'=>'预约统计',
            ),
            //法律援助申请 审批人员维护
            'Handle/falvyz/sprywh' => array(
                'falvyz_weihu?type=1' => '申办人员维护',
                'falvyz_weihu?type=2' => '投诉人员维护',
                'falvyz_weihu?type=3' => '预约人员维护',
            ),
            //人民调解申请 申办项目维护
            'Handle/tj/sbxmwh' => array(
                'tj_type'=>'调解类别',
                'form?type=3'=>'相关例表',
                'notice?type=3'=>'公告',
            ),
            //人民调解申请 统计
            'Handle/tj/tongji' => array(
                'tj?type=1'=>'申办统计',
                'tj?type=2'=>'投诉统计',
                'tj?type=3'=>'预约统计',
            ),
            //人民调解申请 审批人员维护
            'Handle/tj/sprywh' => array(
                'tj_weihu?type=1' => '申办人员维护',
                'tj_weihu?type=2' => '投诉人员维护',
                'tj_weihu?type=3' => '预约人员维护',
            ),
            //司法鉴定申请 申办项目维护
            'Handle/jd/sbxmwh' => array(
                'jd_type'=>'鉴定类别',
                'form?type=4'=>'相关例表',
                'notice?type=4'=>'公告',
            ),
            //司法鉴定申请 统计
            'Handle/jd/tongji' => array(
                'jd?type=1'=>'申办统计',
                'jd?type=2'=>'投诉统计',
                'jd?type=3'=>'预约统计',
            ),
            //司法鉴定申请 审批人员维护
            'Handle/jd/sprywh' => array(
                'jd_weihu?type=1' => '申办人员维护',
                'jd_weihu?type=2' => '投诉人员维护',
                'jd_weihu?type=3' => '预约人员维护',
            ),
        ),

    /*
     * 以下是RBAC认证配置信息
     */
    'USER_AUTH_ON' => false,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
//    'ADMIN_AUTH_KEY' => '422857458@qq.com',
    'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY' => '/admin/Public/index', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => 'cache', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE' => $DB_PREFIX . 'role',
    'RBAC_USER_TABLE' => $DB_PREFIX . 'role_user',
    'RBAC_ACCESS_TABLE' => $DB_PREFIX . 'access',
    'RBAC_NODE_TABLE' => $DB_PREFIX . 'node',

    'URL_HTML_SUFFIX'       => C('TOKEN.URL_HTML_SUFFIX'),  // URL伪静态后缀设置
    'URL_MODEL' =>C('TOKEN.false_static'),// URL伪静态设置/开启，关闭

    'LOAD_EXT_CONFIG'=>'model_menu',
    // 系统保留表明
    'SYSTEM_TBL_NAME' => 'model,models,filed,fileds,admin,admins',
    /*
     * 系统备份数据库时每个sql分卷大小，单位字节
     */
    'sqlFileSize' => 5242880, //该值不可太大，否则会导致内存溢出备份、恢复失败，合理大小在512K~10M间，建议5M一卷
        //10M=1024*1024*10=10485760
        //5M=5*1024*1024=5242880
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__UPLOAD__' => __ROOT__ . '/Uploads',
        '__STATIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/Admin/Img',
        '__CSS__'    => __ROOT__ . '/Public/Admin/Css',
        '__JS__'     => __ROOT__ . '/Public/Admin/Js',
        '--PUBLIC--'=>__ROOT__ . '/Public' ,
    ),

     // 表单令牌
    'TOKEN_ON' => false,
);

//return array_merge($config_arr1, $config_arr2);
?>