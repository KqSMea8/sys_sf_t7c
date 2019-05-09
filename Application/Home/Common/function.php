<?php
/**
 * Created by PhpStorm.
 * User: cony
 * Date: 14-3-7
 * Time: 上午10:15
 */
/**
 * 获取默认图片
 * @param $str
 * @return bool|mixed
 */
function get_default_img($str){
    if(!$str)return false;
    $str_arr=explode(',',$str);
    $map['id']=$str_arr[0];
    return M('images')->where($map)->getField('savepath');
}

/**
 * 获取图片集
 * @param $str
 * @return bool|mixed
 */
function get_img_array($str){
    if(!$str)return false;
    $str_arr=@explode(',',$str);
    $map['id']=array('in',$str_arr);
    return M('images')->where($map)->field('savepath')->select();
}

/**
 * 分类面包屑导航
 * @param $cid
 * @param bool $flag
 * @return string
 */
function conist_nav($cid,$flag=false){
    $cat = new \Org\Util\Category('Category', array('cid', 'pid', 'name', 'fullname'));
    $arr=$cat->getPath($cid);
    $str='<a href='.__APP__.'>'.L('T_HOME').'</a>>';
    if(is_array($arr))
    foreach($arr as $v){
        $str.=$v['name'].'>';
    }
    if($flag)$str=substr($str,0,-1);
    return $str;
}
/**
 * 图片上传
 * @param $cid
 * @param bool $flag
 * @return string
 */
function upload($param,$param1,$param2){
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     2000000000 ;// 设置附件上传大小
    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mov');// 设置附件上传类型
    $upload->rootPath  =     '/Uploads'; // 设置附件上传根目录
    $upload->savePath  =     $param1; // 设置附件上传（子）目录
    $upload->saveName  =     time().'_'.mt_rand();
    // 上传单个文件 
    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
        return $upload->getError();
    }else{// 上传成功 获取上传文件信息
        return '/Uploads'.$info[$param2]['savepath'].$info[$param2]['savename'];
    }
}

//返回数据方法
function returnData($state,$message,$data = ' '){
    $value = array(
            "state" => $state,
            "msg" => $message,
            "data" => $data,
        );
    echo json_encode($value);die;
}

function uploadVideo ($action,$file){  //$_POST['action']   $_POST['file']
    define('MUILTI_FILE_UPLOAD', '10'); //最多10个文件同时上传
    define('MAX_SIZE_FILE_UPLOAD',  '500000' ); //文件大小不超过5MB
    define('FILE_UPLOAD_DIR', 'd:/'); //上传文件的目录
    //允许上传的文件名
    $array_extention_interdite = array( '.php' , '.php3' , '.php4' , '.exe' , '.msi' , '.htaccess' , '.gz' ); //上传文件的扩展名
     
    //显示信息的公共函数
    function func_message($message='', $ok=''){
    echo '<table width="100%" cellspacing="0" cellpadding="5">';
    if($ok == true){ 
    echo '<tr bgcolor="#99FF99" ><td width="100">&nbsp;</td><td class= "text"> '.$message.'</td></tr>' ;
    }
    if($ok == false){ 
    echo '<tr bgcolor="#FF99CC" ><td width="100">&nbsp;</td><td class="text"> '.$message.'</td></tr>';
    }
    echo '</table>';   
    }
    //处理表单提交
    $action = (isset($action)) ? $action :'' ;
    $file = (isset($file)) ? $file :''  ;
    if($file != '') {
        $file = $file.'/';
    }
    $message_true = '';
    $message_false = '';
     
    switch($action){
        case 'upload' :   
        chmod(FILE_UPLOAD_DIR,0777);   
        for($nb=1;$nb<=MUILTI_FILE_UPLOAD;$nb++){    
            if($_FILES['file_'.$nb]['size'] >= 10){  
                if ($_FILES['file_'.$nb]['size'] <= MAX_SIZE_FILE_UPLOAD){ 
                    if (!in_array(ereg_replace('^[[:alnum:]]([-_.]?[[:alnum:]])*\.','.',$_FILES['file_'.$nb]['name']),$array_extention_interdite)){ 
                            if($_POST['file_name_'.$nb] !=''){ 
                                $file_name_final = $_POST['file_name_'.$nb].$extension ;
                            }else {
                                $file_name_final = $_FILES['file_'.$nb]['name'] ;
                            }
                            //文件名的修改
                            $file_name_final = strtr($file_name_final,'aaaaaa','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
                            $file_name_final = preg_replace('/([^.a-z0-1]+)/i','_',$file_name_final ); 
                            $_FILES['file_'.$nb]['name'] = $file_name_final;       
                            move_uploaded_file( $_FILES['file_'.$nb]['tmp_name'],FILE_UPLOAD_DIR.$file.$file_name_final);
                            return $message_true .= '已经上传文件:'.$_FILES['file_'.$nb]['name'].'<br>'; 
                    }else{
                        return $message_false .= '文件上传失败:'.$_FILES['file_'.$nb]['name'].'<br>'; 
                    }
                }else{
                    // return $message_false .= '文件尺寸超过'.MAX_SIZE_FILE_UPLOAD/1000.'KB: "'.$_FILES['file_'.$nb]['tmp_name'].'"<br>';
                }
            }
        }//end for
        break;
    }
}

function time_tran($the_time) {
    $now_time = date("Y-m-d H:i:s", time());  
    
    $now_time = strtotime($now_time);  
    // echo $now_time;  die;
    $show_time = strtotime($the_time);  
    $dur = $now_time - $show_time;  
    if ($dur < 0) {  
        return $the_time;  
    } else {  
        if ($dur < 60) {  
            return $dur . '秒前';  
        } else {  
            if ($dur < 3600) {  
                return floor($dur / 60) . '分钟前';  
            } else {  
                if ($dur < 86400) {  
                    return floor($dur / 3600) . '小时前';  
                } else {  
                    if ($dur < 259200) {//3天内  
                        return floor($dur / 86400) . '天前';  
                    } else {  
                        return date('Y-m-d',strtotime($the_time));

                    }  
                }  
            }  
        }  
    }  
}

//分页  页数 ，条数
function pagex($page=1,$num=10){
    $page = $page? : 1;
    $first = ($page-1)*$num;
    $last = $num;
    $pages = $first.','.$last;
    return $pages;
}

function rec_assoc_shuffle($array)  
{  
  $ary_keys = array_keys($array);  
  $ary_values = array_values($array);  
  shuffle($ary_values);  
  foreach($ary_keys as $key => $value) {  
    if (is_array($ary_values[$key]) AND $ary_values[$key] != NULL) {  
      $ary_values[$key] = rec_assoc_shuffle($ary_values[$key]);  
    }  
    $new[$value] = $ary_values[$key];  
  }  
  return $new;  
}  

function shuffle_assoc($list) {   
if (!is_array($list)) return $list;   
  
$keys = array_keys($list);   
shuffle($keys);   
$random = array();   
foreach ($keys as $key)   
$random[$key] = shuffle_assoc($list[$key]);   
  
return $random;   
}   

//二维数组去掉重复值,并保留键值
function array_unique_fb($array2D){
    foreach ($array2D as $k=>$v){
        $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        $temp[$k]=$v;
    }
    $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
    foreach ($temp as $k => $v){
        $array=explode(',',$v); //再将拆开的数组重新组装
        //下面的索引根据自己的情况进行修改即可
        $temp2[$k]['school_culture'] = $array[0];
        // $temp2[$k]['title'] =$array[1];
        // $temp2[$k]['keywords'] =$array[2];
        // $temp2[$k]['content'] =$array[3];
    }
    return $temp2;
}


//秒数格式化
function Sec2Time($time){
    if(is_numeric($time)){
    $value = array(
      "years" => 0, "days" => 0, "hours" => 0,
      "minutes" => 0, "seconds" => 0,
    );
    if($time >= 31556926){
      $value["years"] = floor($time/31556926);
      $time = ($time%31556926);
    }
    if($time >= 86400){
      $value["days"] = floor($time/86400);
      $time = ($time%86400);
    }
    if($time >= 3600){
      $value["hours"] = floor($time/3600);
      $time = ($time%3600);
    }
    if($time >= 60){
      $value["minutes"] = floor($time/60);
      $time = ($time%60);
    }
    $value["seconds"] = floor($time);
    //return (array) $value;
    $t=$value["years"] ."年". $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
    Return $t;
    
     }else{
    return (bool) FALSE;
    }
 }

 function qrcode($url,$size=4){
    Vendor('Phpqrcode.phpqrcode');
    QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);
}