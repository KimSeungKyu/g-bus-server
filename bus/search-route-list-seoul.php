<?php
/**
 * 서울 버스 검색
 */

include '../utils/util.php';
$route_nm = $_REQUEST ['routeNm'];
if ($route_nm) {
	$url = 'http://ws.bus.go.kr/api/rest/busRouteInfo/getBusRouteList?serviceKey=' . $serviceKey . '&strSrch=' . $route_nm;
	$xml = connection($url);
	createCsv ( $xml );
}
function createCsv($xml) {
	$busRouteId;
	$busRotueNm;
	$routeType;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array ( $item->getName (), $item );
			if ($put_arr [0] == 'busRouteId') {
				$busRouteId = $put_arr [1];
			} else if ($put_arr [0] == 'busRouteNm') {
				$busRouteNm = $put_arr [1];
			} else if ($put_arr [0] == 'routeType') {
				// 노선 유형 (1:공항, 2:마을, 3:간선, 4:지선, 5:순환, 6:광역, 7:인천, 8:경기, 9:폐지, 0:공용)
				switch ($put_arr [1]) {
					case '1' :
						$routType =  '공항';
						break;
					case '2' :
						$routType =  '마을';
						break;
					case '3' :
						$routType =  '간선';
						break;
					case '4' :
						$routType =  '지선';
						break;
					case '5' :
						$routType =  '순환';
						break;
					case '6' :
						$routType =  '광역';
						break;
					case '7' :
						$routType =  '인천';
						break;
					case '8' :
						$routType =  '경기';
						break;
					case '9' :
						$routType =  '폐지';
						break;
					case '0' :
						$routType =  '공용';
						break;
					default :
						echo '';
						break;
				}
				echo '<li><a href="javascript:getStationListSeoul(' . $busRouteId . ');"><font color="gray">'.$routType.'</font> <font color="blue">'.$busRouteNm. '</font></a></li>';
			}
		} else {
			createCsv ( $item );
		}
	}
}

?>