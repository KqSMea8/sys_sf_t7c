<?php  
/** 
 * Created by PhpStorm. 
 * User: ForeverTime 
 * Date: 2016/12/10 
 * Time: 16:27 
 */  
class Ali_Lite{  
    protected  $config;  
    protected  $aliLive;  
  
    public function __construct()  
    {  
        require_once  '/public/ThinkPHP/Library/vendor/Aliyun.php';
 
        $this -> aliLive = new \Aliyun();  
    }  
  
    /** 
     * 查询在线人数 
     * @param $domainName  直播域名 
     * @param $appName     应用名 
     * @param $streamName  推流名 
     */  
    public function describeLiveStreamOnlineUserNum($domainName,$appName,$streamName){  
        $apiParams = array(  
            'Action'=>'DescribeLiveStreamOnlineUserNum',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
            'StreamName'=>$streamName,  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  
  
  
    /** 
     * 获取某个域名或应用下的直播流操作记录 
     * @param $domainName      域名 
     * @param $appName         应用名 
     * @param $streamName      推流名 
     */  
    public function describeLiveStreamsControlHistory($domainName,$appName,$startTime,$endTime){  
        $apiParams = array(  
            'Action'=>'DescribeLiveStreamsControlHistory',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
            'StartTime'=>$startTime,  
            'EndTime'=>$endTime,  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  
  
    /** 
     * 查看指定域名下（或者指定域名下某个应用）的所有正在推的流的信息 
     * @param $domainName       域名 
     * @param $appName          应用名 
     * @return bool|int|mixed 
     */  
    public function describeLiveStreamsOnlineList($domainName,$appName){  
        $apiParams = array(  
            'Action'=>'DescribeLiveStreamsOnlineList',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  

    /** 
     * 查询推流黑名单列表 
     * @param $domainName       域名 
     * @return bool|int|mixed 
     */  
    public function describeLiveStreamsBlockList($domainName){  
        $apiParams = array(  
            'Action'=>'DescribeLiveStreamsBlockList',  
            'DomainName'=>$domainName,  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  
  
    /** 
     * 生成推流地址 
     * @param $streamName 用户专有名 
     * @param $vhost 加速域名 
     * @param $time 有效时间单位秒 
     */  
    public function getPushSteam($streamName,$vhost,$time=3600){  
        $time = time()+$time;  
        $videohost = $this->aliLive->video_host;  
        $appName=$this->aliLive->appName;  
        $privateKey=$this->aliLive->privateKey;  
        if($privateKey){  
            $auth_key =md5('/'.$appName.'/'.$streamName.'-'.$time.'-0-0-'.$privateKey);  
            $url =$videohost.'/'.$appName.'/'.$streamName.'?vhost='.$vhost.'&auth_key='.$time.'-0-0-'.$auth_key;  
        }else{  
            $url = $videohost.'/'.$appName.'/'.$streamName.'?vhost='.$vhost;  
        }  
        return $url;  
    }  
  
    /** 
     * 生成拉流地址 
     * @param $streamName 用户专有名 
     * @param $vhost 加速域名 
     * @param $type 视频格式 支持rtmp、flv、m3u8三种格式 
     */  
    public function getPullSteam($streamName,$vhost,$time=3600,$type='rtmp'){  
        $time = time()+$time;  
        $appName=$this->aliLive->appName;  
        $privateKey=$this->aliLive->privateKey;  
        $url='';  
        switch ($type){  
            case 'rtmp':  
                $host = 'rtmp://'.$vhost;  
                $url = '/'.$appName.'/'.$streamName;  
                break;  
            case 'flv':  
                $host = 'http://'.$vhost;  
                $url = '/'.$appName.'/'.$streamName.'.flv';  
                break;  
            case 'm3u8':  
                $host = 'http://'.$vhost;  
                $url = '/'.$appName.'/'.$streamName.'.m3u8';  
                break;  
        }  
        if($privateKey){  
            $auth_key =md5($url.'-'.$time.'-0-0-'.$privateKey);  
            $url = $host.$url.'?auth_key='.$time.'-0-0-'.$auth_key;  
        }else{  
            $url = $host.$url;  
        }  
        return $url;  
    }  
  
    /** 
     * 禁止推流接口 
     * @param $domainName        您的加速域名 
     * @param $appName          应用名称 
     * @param $streamName       流名称 
     * @param $liveStareamName  用于指定主播推流还是客户端拉流, 目前支持”publisher” (主播推送) 
     * @param $resumeTime       恢复流的时间 UTC时间 格式：2015-12-01T17:37:00Z 
     * @return bool|int|mixed 
     */  
    public function forbid($streamName,$resumeTime,$domainName='www.test.com',$appName='xnl',$liveStreamType='publisher'){  
        $apiParams = array(  
            'Action'=>'ForbidLiveStream',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
            'StreamName'=>$streamName,  
            'LiveStreamType'=>$liveStreamType,  
            'ResumeTime'=>$resumeTime  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  


     /** 
     * 恢复直播流推送 
     * @param $streamName              流名称 
     * @param string $appName          应用名称 
     * @param string $liveStreamType   用于指定主播推流还是客户端拉流, 目前支持”publisher” (主播推送) 
     * @param string $domainName       您的加速域名 
     */  
    public function resumeLive($streamName,$domainName='www.test.top',$appName='xnl',$liveStreamType='publisher'){  
        $apiParams = array(  
            'Action'=>'resumeLiveStream',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
            'StreamName'=>$streamName,  
            'LiveStreamType'=>$liveStreamType,  
        );  
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }  
    
    /**
     * 添加APP录制配置
     * @param string $AppName 直播流所属应用名称
     * @param string $OSSEndpoint OSS endpoint
     * @param string $OSSBucket OSS 存储 bucket 名称
     * @param string $Format 格式，目前支持 m3u8、flv、mp4 周期录制
     * @param string $RecordFormat.n.OssObjectPrefix OSS 存储文件名，每次截图都递增存储，
     * @param string $RecordFormat.n.SliceOssObjectPrefix 当 format 格式是 m3u8 录制，则需要配置，表示 ts 切片名称，默认 30 秒一片，小于 256byte，支持变量匹配，包含
     * @param int $RecordFormat.n.CycleDuration 周期录制时长，单位秒, 不填则默认为 1 小时
     */
    public function RecordConfig($domainName='www.test.top',$appName='xnl',$OssEndpoint,$OssBucket,$Format,$OssObjectPrefix,$SliceOssObjectPrefix,$CycleDuration){
        $apiParams = array(  
            'Action'=>'AddLiveAppRecordConfig',  
            'DomainName'=>$domainName,  
            'AppName'=>$appName,  
            'OssEndpoint'=>$OssEndpoint,  
            'OssBucket'=>$OssBucket,
            'Format'  =>$Format,
            'OssObjectPrefix'=>$OssObjectPrefix,
            'SliceOssObjectPrefix'=>$SliceOssObjectPrefix,
            'CycleDuration'=>$CycleDuration,
        );  

        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  

    }


    /**
     * 查询域名下所有 App 录制配置。
     * @param  $AppName 应用名称
     * @param int $PageNum 分页的页码，默认值：1
     * @param imt $PageSize 每页大小，默认值：10，范围：5~30
     * @param string $Order 排序，asc：升序，desc：降序，默认：asc
     */
    public function recordQuery($domainName='www.test.top',$appName='xnl',$pageNUM='1',$pageSize='10',$order='asc'){
        $apiParams = array(
            'Action'=>'DescribeLiveRecordConfig',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'PageNum'=>$pageNUM,
            'PageSize'=>$pageSize,
            'Order'=>$order,
        );

        return $this->aliLive->aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");
    }

    /**
     * 查询录制内容
     * @param string $AppName 直播流所属应用名称
     * @param string $StreamName 直播流名称
     * @param string $StartTime 开始时间，格式：2015-12-01T17:36:00Z
     * @param string $EndTime 结束时间，格式：2015-12-01T17:36:00Z，与 StartTime 的间隔时间不能超过 4 天
     */
    public function recordContent($domainName='www.test.top',$appName='xnl',$streamName,$startTime,$endTime){
        $apiParams = array(
            'Action'=>'DescribeLiveStreamRecordContent',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'StreamName'=>$streamName,
            'StartTime'=>$startTime,
            'EndTime'=>$endTime,
        );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }

    /**
     * 添加截图配置
     * @param string $AppName 直播流所属应用名称
     * @param int $TimeInterval 截图周期，范围：5~3600，单位秒
     * @param string $OSSEndpoint OSS endpoint
     * @param string $OSSBucket OSS 存储 bucket 名称
     * @param string $OverwriteOSSObject OSS 存储文件名，每次截图都覆盖此文件
     * @param string $SequenceOSSObject OSS 存储文件名，每次截图都递增存储，
     */
    public function addPicConfig($domainName='www.test.top',$appName='xnl',$timeInterval,$ossEndpoint,$ossBucket,$overwriteOSSObject = null,$sequenceOSSObject = null){
        $apiParams = array(
            'Action'=>'AddLiveAppSnapshotConfig',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'TimeInterval'=>$timeInterval,
            'OSSEndpoint'=>$ossEndpoint,
            'OSSBucket' => $ossBucket
        );
        if($overwriteOSSObject){
            $apiParams['OverwriteOSSObject'] = $overwriteOSSObject;
        }
        if($sequenceOSSObject){
            $apiParams['SequenceOSSObject'] = $sequenceOSSObject;
        }
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com"); 
    }

    /**
     *查询域名下的截图配置。
     * @param string $AppName 直播流所属应用名称
     * @param int $PageNum 分页的页码，默认值：1
     * @param int $PageSize 每页大小，默认值：10，范围：5~30
     * @param string $Order 排序，asc：升序，desc：降序，默认：asc
     */
    public function showPicConfig($domainName='www.test.top',$appName='xnl',$pageNUM='1',$pageSize='10',$order='asc'){
        $apiParams = array(
            'Action'=>'DescribeLiveSnapshotConfig',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'PageNum'=>$pageNUM,
            'PageSize'=>$pageSize,
            'Order'=>$order,
        );

        return $this->aliLive->aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com"); 
    }
    
    /**
     *查询截图信息
     * @param string $AppName 直播流所属应用名称
     * @param string $StreamName 直播流名称
     * @param string $StartTime 开始时间，格式：2015-12-01T17:36:00Z
     * @param string $EndTime 结束时间，格式：2015-12-01T17:36:00Z，与 StartTime 的间隔时间不能超过 1 天
     * @param int $Limit 一次调用获取的数量，范围 1~100，默认值：10
     */
    public function showPicture($domainName='www.test.top',$appName='xnl',$streamName,$startTime,$endTime,$limit){
        $apiParams = array(
            'Action'=>'DescribeLiveStreamSnapshotInfo',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'StreamName'=>$streamName,
            'StartTime'=>$startTime,
            'EndTime'=>$endTime,
            'Limit'=>$limit,
        );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }

    /**
     * 删除截图配置
     * @param string $AppName 直播流所属应用名称
     */
    public function delPicConfig($domainName='www.test.top',$appName='xnl'){
        $apiParams = array(
            'Action'=>'DeleteLiveAppSnapshotConfig',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
        );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }

    /**
     * 查询在线人数
     * @param string $AppName 直播流所属应用名称
     * @param string $StreamName 直播流名称
     */
    public function onlineUserNum($domainName='www.test.top',$appName='xnl',$streamName){
        $apiParams = array(
            'Action'=>'DescribeLiveStreamOnlineUserNum',
            'DomainName'=>$domainName,  
            'AppName'=>$appName, 
            'StreamName'=>$streamName,
            );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");  
    }

    /**
     *查询转码配置信息
     */
    public function TranscodeInfo($domainTranscodeName){
        $apiParams = array(
            'Action'=>'DescribeLiveStreamTranscodeInfo',
            'DomainTranscodeName' => $domainTranscodeName,
            );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com"); 
    }


    /**
     * 查询媒体-使用OSS文件地址
     * @param string $FileURLs  文件地址，逗号分隔，最多10个
     * @param string $IncludePlayList   结果是否包含播放信息，
     * @param string $IncludeSnapshotList   结果是否包含截图信息，范围
     * @param string $IncludeMediaInfo 结果是否包含媒体信息，
     */
    public function QueryMedia($FileURLs,$IncludePlayList,$IncludeSnapshotList,$IncludeMediaInfo){
        $apiParams = array(
            'Action'=>'QueryMediaListByURL',
            'FileURLs'=>$FileURLs,  
            'IncludePlayList'=>$IncludePlayList, 
            'IncludeSnapshotList'=>$IncludeSnapshotList,
            'IncludeMediaInfo'=>$IncludeMediaInfo,
        );
        return $this -> aliLive -> aliApi($apiParams,$credential="GET", $domain="cdn.aliyuncs.com");    
    }
}  


// date('Y-m-d\TH:i:s\Z')
// $live = new Ali_Lite();
// $data = $live->describeLiveStreamOnlineUserNum('live1.aiegi.cn','test','zhang');//查看在线人数
// $data = $live->getPushSteam('zhang','live1.aiegi.cn',3600); //推流
// $data = $live->getPullSteam('zhang','live1.aiegi.cn',$time=3600,$type='flv'); //拉流
// $data = $live->forbid('zhang','2017-11-07T11:00:00Z','live1.aiegi.cn','test',$liveStreamType='publisher'); //禁用推流
// $data = $live->resumeLive('zhang','live1.aiegi.cn','test',$liveStreamType='publisher'); //恢复推流
// $data = $live->recordQuery('live1.aiegi.cn','test','1','10','asc'); //查询域名下所有 App 录制配置
// $data = ($live->recordContent('live1.aiegi.cn','test','zhang','2017-11-06T10:00:00Z','2017-11-07T15:37:11Z'));
// $data = $live->showPicture('live1.aiegi.cn','test','zhang','2017-12-04T13:00:00Z','2017-12-04T18:00:00Z',1);  //查看截图内容
// $data = $live->describeLiveStreamsOnlineList('live1.aiegi.cn','test');   //查看正在推流的信息
// $data = $live->onlineUserNum('live1.aiegi.cn','test','zhang'); //查看直播人数
// $a = rand(0000,1111);
// $data = $live->addPicConfig('live1.aiegi.cn','*','5','oss-cn-beijing.aliyuncs.com','qige','zhang.jpg'); //添加截图配置
// $data = $live->showPicConfig('live1.aiegi.cn','*','1','10','asc'); //查看截图配置
// $data = $live->TranscodeInfo('live1.aiegi.cn');  //查看转码配置
// $data = $live->delPicConfig($domainName='live1.aiegi.cn','*'); //删除截图配置

// print_r($data);
