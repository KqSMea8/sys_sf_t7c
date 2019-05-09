<?php
return array (
  'Subject' => 
  array (
    'name' => '课程管理',
    'target' => 'Subject/index',
    'sub_menu' => 
    array (
      0 => 
      array (
        'item' => 
        array (
          'Subject/index' => '课程列表',
        ),
      ),
      1 => 
      array (
        'item' => 
        array (
          'Subject/add' => '添加课程',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Subject/edit' => '编辑课程',
        ),
        'hidden' => true,
      ),
      3 => 
      array (
        'item' => 
        array (
          'Subject/category' => '课程类别',
        ),
      ),
    ),
  ),
  'Personal' => 
  array (
    'name' => '会员管理',
    'target' => 'Personal/index',
    'sub_menu' => 
    array (
      1 => 
      array (
        'item' => 
        array (
          'Personal/orderIndex' => '订单管理',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Personal/add' => '添加用户',
        ),
      ),
      3 => 
      array (
        'item' => 
        array (
          'Personal/applyteacher' => '申请列表',
        ),
      ),
      4 => 
      array (
        'item' => 
        array (
          'Personal/ptrz' => '平台入驻问题',
        ),
      ),
      5 => 
      array (
        'item' => 
        array (
        ),
      ),
      6 => 
      array (
        'item' => 
        array (
          'Personal/report' => '举报中心',
        ),
      ),
      7 => 
      array (
        'item' => 
        array (
        ),
      ),
      8 => 
      array (
        'item' => 
        array (
          'Personal/about' => '关于我们',
        ),
      ),
      9 => 
      array (
        'item' => 
        array (
          'Personal/addlr' => '法律条款',
        ),
      ),
    ),
  ),
  'Activity' => 
  array (
    'name' => '资金管理',
    'target' => 'Activity/index',
    'sub_menu' => 
    array (
      0 => 
      array (
        'item' => 
        array (
          'Activity/index' => '基金出入金操作记录',
        ),
      ),
      1 => 
      array (
        'item' => 
        array (
          'Activity/add' => '基金出入金操作',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Activity/update' => '更新基金估值',
        ),
      ),
      3 => 
      array (
        'item' => 
        array (
          'Activity/listjj' => '按照基金产品统计',
        ),
      ),
      4 => 
      array (
        'item' =>
        array (
          'Activity/line_stack' => '统计图',
        ),
      ),
    ),
  ),
  'Institution' => 
  array (
    'name' => '机构管理',
    'target' => 'Institution/index',
    'sub_menu' => 
    array (
      0 => 
      array (
        'item' => 
        array (
          'Institution/index' => '机构管理',
        ),
      ),
      1 => 
      array (
        'item' => 
        array (
          'Institution/inst_add' => '添加机构',
        ),
      ),
      3 => 
      array (
        'item' => 
        array (
          'Institution/class_add' => '添加课程',
        ),
      ),
      4 => 
      array (
        'item' => 
        array (
          'Institution/teacher_add' => '添加老师',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Institution/inst_edit' => '编辑机构',
        ),
        'hidden' => true,
      ),
    ),
  ),
  'live' => 
  array (
    'name' => '直播管理',
    'target' => 'Live/index',
    'sub_menu' => 
    array (
      0 => 
      array (
        'item' => 
        array (
          'Live/index' => '直播列表',
        ),
      ),
      1 => 
      array (
        'item' => 
        array (
          'Live/giftIndex' => '直播礼物',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Live/giftRate' => '礼物比率',
        ),
      ),
    ),
  ),
  'Answer' => 
  array (
    'name' => '问答管理',
    'target' => 'Answer/index',
    'sub_menu' => 
    array (
      0 => 
      array (
        'item' => 
        array (
          'Answer/index' => '问答列表',
        ),
      ),
      1 => 
      array (
        'item' => 
        array (
          'Answer/add' => '添加问答',
        ),
      ),
      2 => 
      array (
        'item' => 
        array (
          'Answer/edit' => '编辑问答',
        ),
        'hidden' => true,
      ),
    ),
  ),
);
