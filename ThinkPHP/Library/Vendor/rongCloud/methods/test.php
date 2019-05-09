<?php
include 'rongcloud.php';
$appKey = 'sfci50a7s4zqi';
$appSecret = 'JxXq8AhwXxl';
$jsonPath = "jsonsource/";
$RongCloud = new RongCloud($appKey,$appSecret);

	$chatRoomInfo['chatroomId1'] = 'chatroomInfo1';
	$chatRoomInfo['chatroomId2'] = 'chatroomInfo2';
	$chatRoomInfo['chatroomId3'] = 'chatroomInfo3';
	$result = $RongCloud->chatroom()->create($chatRoomInfo);
	echo "create    ";
	print_r($result);
	echo "\n";

?>