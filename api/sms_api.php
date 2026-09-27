<?php
// +++++++++ SMS API - SONG BIRD +++++++++++++++
$apikey = urlencode("a6d11b914294fc7e");
$secretkey = urlencode("19205862");
$callerID = urlencode("UNITY");
$messageContent = urlencode($msg);
$toUser = preg_replace('/^0?/', '880', $mobile_no);

$url = "http://sms.songbirdtelecom.com:8746/sendtext?apikey=$apikey&secretkey=$secretkey&callerID=$callerID&toUser=$toUser&messageContent=$messageContent";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);
//print $resp;
?>
