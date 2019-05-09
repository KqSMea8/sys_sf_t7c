<?php
$DB_PREFIX = C('DB_PREFIX');
return array(
        'admin_big_menu' => array(
            'user'=>'用户管理',
            'company'=>'企业管理',
            'type'=>'分类管理',
            'news' => '问答管理',
            
        ),

        'admin_sub_menu' => array(
        
            'user'=>array(
                'index'=>'用户列表',
                'per_collect'=>'个人账户查询',
                'hc_record'=>'会员创建记录',
                'jx_record'=>'绩效记录',
                'tf_record'=>'推广员返利记录',
                'br_record'=>'购赎记录',
                'gz_record'=>'估值变动记录',
            ),

            'company'=>array(
                'index' => '公司列表',
                'category' => '基金分类',
                'currency' => '货币分类',
                'huilv' => '货币汇率列表',
                'qd_list' => '渠道费用账户列表',
                'jx_list' => '绩效账户列表',
                'vip_list' => '会员等级列表',
            ),
       
            'type' => array(
                'index'=>'分类列表',
                'ransom'=>'赎回申请',
                'recharge'=>'充值申请',
                'withdraw'=>'提现申请',

            ),

            'news' => array(
                'index' => '问答列表',

            ),   

            
        ),

    /*
     * 以下是RBAC认证配置信息
     */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
//    'ADMIN_AUTH_KEY' => '422857458@qq.com',
    'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY' => '/admin/Public/index', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
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