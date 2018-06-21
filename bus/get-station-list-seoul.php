<?php
/**
 * 서울 버스 검색 목록에서 선택한 버스의 전체 노선
 */
include '../utils/util.php';
$route_id = $_REQUEST ['routeId'];
if ($route_id) {
	$url = 'http://ws.bus.go.kr/api/rest/busRouteInfo/getStaionByRoute?serviceKey=' . $serviceKey . '&busRouteId=' . $route_id . '&numOfRows=999';
	$xml = connection ( $url );
	createCsv ( $xml );
}

// addData(route_id, route_nm, station_id, station, station_nm, region, ord)
function createCsv($xml) {
	$route_id;
	$route_nm;
	$station_id;
	$station_nm;
	$station;
	$ord;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'busRouteId') {
				$route_id = $put_arr [1];
			} else if ($put_arr [0] == 'busRouteNm') {
				$route_nm = $put_arr [1];
			} else if ($put_arr [0] == 'station') {
				$station_id = $put_arr [1];
			} else if ($put_arr [0] == 'arsId') {
				$station = $put_arr [1];
			} else if ($put_arr [0] == 'seq') {
				$ord = $put_arr [1];
			} else if ($put_arr [0] == 'stationNm') {
				$station_nm = $put_arr [1];
				echo '<li data-icon="plus">';
				echo '<a href="javascript:addData(\'' . $route_id . '\', \'' . $route_nm . '\', \'' . $station_id . '\', \'' . $station . '\', \'' . $station_nm . '\', 2, \'' . $ord . '\');">';
				echo '&darr;' . $station_nm . ' <font color="gray">(' . getStationNo ( $station ) . ')</font>';
				echo '</a></li>';
			}
		} else {
			createCsv ( $item );
		}
	}
}

?>
