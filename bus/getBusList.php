<?php
/**
 * 해당 정류장을 경유하는 모든 버스를 검색
 */
include '../utils/util.php';

$stationId= $_REQUEST ['stationId'];

$result = array();

//경기
$url = 'http://openapi.gbis.go.kr/ws/rest/busstationservice/route?serviceKey='.$serviceKey.'&stationId='.$stationId;
$xml = connection ( $url );
$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
if (array_key_exists(msgBody, $array) && array_key_exists(busRouteList, $array->msgBody)){
	$json = json_encode($array->msgBody->busRouteList, JSON_UNESCAPED_UNICODE);
	$result = json_decode($json);
}

echo json_encode($result);
?>