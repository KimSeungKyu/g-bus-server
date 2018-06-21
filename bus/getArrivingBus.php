<?php
/**
 * 위치기반 주변의 가까운 역 검색
 * pebblejs의 Menu section 내 item에 바로 쓸 수 있게 만들어서 전송
 */
include '../utils/util.php';

$stationId= $_REQUEST ['stationId'];
$arsId= $_REQUEST ['arsId'];
$region = $_REQUEST ['region'];

$result = array();

switch ($region){
	case 0:{
		//경기
		$url = 'http://openapi.gbis.go.kr/ws/rest/busarrivalservice/station?serviceKey='.$serviceKey.'&stationId='.$stationId;
		$xml = connection ( $url );
		$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
		$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
		if (array_key_exists(msgBody, $array) && array_key_exists(busArrivalList, $array->msgBody)){
			$json = json_encode($array->msgBody->busArrivalList, JSON_UNESCAPED_UNICODE);
			$result = json_decode($json);
		}
		break;
	}
	case 2:{
		//서울
		$url = 'http://ws.bus.go.kr/api/rest/stationinfo/getStationByUid?serviceKey='.$serviceKey.'&arsId='.$arsId;
		$xml = connection ( $url );
		$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
		$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
		if (array_key_exists(msgBody, $array) && array_key_exists(itemList, $array->msgBody)){
			$json = json_encode($array->msgBody->itemList, JSON_UNESCAPED_UNICODE);
			$result = json_decode($json);
		}
		break;
	}
	case 3:{//부산
		$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/stopArr?serviceKey='.$serviceKey."&bstopid=".$stationId ."&numOfRows=99";
		$xml = connection ( $url );
		$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
		$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
		if (array_key_exists(body, $array) && array_key_exists(items, $array->body) && array_key_exists(item, $array->body->items)){
			$json = json_encode($array->body->items->item, JSON_UNESCAPED_UNICODE);
			$result = json_decode($json);
		}
		break;
	}
	case 4:{
		//국토교통부
		$cityCode= $_REQUEST ['cityCode'];
		$url = 'http://openapi.tago.go.kr/openapi/service/ArvlInfoInqireService/getSttnAcctoArvlPrearngeInfoList?serviceKey='.$serviceKey."&cityCode=".$cityCode."&nodeId=".$stationId ."&numOfRows=99";
		$xml = connection ( $url );
		$json = json_encode($xml, JSON_UNESCAPED_UNICODE); // convert the XML string to JSON
		$array = json_decode($json); // convert the JSON-encoded string to a PHP variable
		if (array_key_exists(body, $array) && array_key_exists(items, $array->body) && array_key_exists(item, $array->body->items)){
			$json = json_encode($array->body->items->item, JSON_UNESCAPED_UNICODE);
			$result = json_decode($json);
		}
		break;
	}
}

echo json_encode($result);

?>