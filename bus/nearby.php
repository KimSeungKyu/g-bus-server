<?php
/**
 * 위치기반 주변의 가까운 역 검색
 * pebblejs의 Menu section 내 item에 바로 쓸 수 있게 만들어서 전송
 * 부산은 API가 없다.
 */
include '../utils/util.php';
$lat = $_REQUEST ['lat'];
$lon = $_REQUEST ['lon'];

$result = array();

//경기
$url = 'http://openapi.gbis.go.kr/ws/rest/busstationservice/searcharound?serviceKey='.$serviceKey.'&x='.$lon.'&y='.$lat;
$xml = connection ( $url );
$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
if (array_key_exists(msgBody, $array) && array_key_exists(busStationAroundList, $array->msgBody)){
	$json = json_encode($array->msgBody->busStationAroundList, JSON_UNESCAPED_UNICODE);
	$array = json_decode($json);
	if (count($array)>1){
		for ($count = 0; $count < count($array); $count++){
			$item['title'] = $array[$count]->stationName;
			$item['subtitle'] = '경기: '.getStationNo ( $array[$count]->mobileNo);
			$item['stationId'] = $array[$count]->stationId;
			$item['region'] = 0;
			$item['lat'] = $array[$count]->y;
			$item['lon'] = $array[$count]->x;
			array_push($result, $item);
		}
	}else{
		$item['title'] = $array->stationName;
		$item['subtitle'] = '경기: '.getStationNo ( $array->mobileNo);
		$item['stationId'] = $array->stationId;
		$item['region'] = 0;
		$item['lat'] = $array->y;
		$item['lon'] = $array->x;
		array_push($result, $item);
	}
}

//서울
$url = 'http://ws.bus.go.kr/api/rest/stationinfo/getStationByPos?serviceKey='.$serviceKey.'&tmX='.$lon.'&tmY='.$lat.'&radius=100';
$xml = connection ( $url );
$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
if (array_key_exists(msgBody, $array) && array_key_exists(itemList, $array->msgBody)){
	$json = json_encode($array->msgBody->itemList, JSON_UNESCAPED_UNICODE);
	$array = json_decode($json);
	if (count($array)>1){
		for ($count = 0; $count < count($array); $count++){
			$item['title'] = $array[$count]->stationNm;
			$item['subtitle'] = '서울: '.getStationNo ( $array[$count]->arsId);
			$item['stationNum'] = $array[$count]->arsId;
			$item['region'] = 2;
			$item['lat'] = $array[$count]->gpsY;
			$item['lon'] = $array[$count]->gpsX;
			array_push($result, $item);
		}
	}else{
		$item['title'] = $array->stationNm;
		$item['subtitle'] = '서울: '.getStationNo ( $array->arsId);
		$item['stationNum'] = $array->arsId;
		$item['region'] = 2;
		$item['lat'] = $array->gpsY;
		$item['lon'] = $array->gpsX;
		array_push($result, $item);
	}
}

//국토교통부
$url = 'http://openapi.tago.go.kr/openapi/service/BusSttnInfoInqireService/getCrdntPrxmtSttnList?serviceKey='.$serviceKey.'&gpsLong='.$lon.'&gpsLati='.$lat;
$xml = connection ( $url );
$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
if (array_key_exists(body, $array) && array_key_exists(items, $array->body) && array_key_exists(item, $array->body->items)){
	$json = json_encode($array->body->items->item, JSON_UNESCAPED_UNICODE);
	$array = json_decode($json);
	if (count($array)>1){
		for ($count = 0; $count < count($array); $count++){
			$item['title'] = $array[$count]->nodenm;
			$item['subtitle'] = getCitiCodeToName($array[$count]->citycode).': '.getStationNo ( $array[$count]->nodeno);
			$item['stationId'] = $array[$count]->nodeid;
			$item['region'] = 4;
			$item['cityCode'] = $array[$count]->citycode;
			$item['lat'] = $array[$count]->gpsLati;
			$item['lon'] = $array[$count]->gpsLong;
			array_push($result, $item);
		}
	}else{
		$item['title'] = $array->nodenm;
		$item['subtitle'] = getCitiCodeToName($array->citycode).': '.getStationNo ( $array->nodeno);
		$item['stationId'] = $array->nodeid;
		$item['region'] = 4;
		$item['cityCode'] = $array->citycode;
		$item['lat'] = $array->gpsLati;
		$item['lon'] = $array->gpsLong;
		array_push($result, $item);
	}
}

echo json_encode($result);
?>