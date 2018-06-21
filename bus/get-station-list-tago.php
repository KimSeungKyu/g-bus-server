<?php
/**
 * 국토교통부 노선별경유정류소목록조회
 */
include '../utils/util.php';
$cityCode = $_REQUEST ['cityCode'];
$route_id = $_REQUEST ['routeId'];
$route_no = $_REQUEST ['routeNo'];
if ($route_id) {
	$url = 'http://openapi.tago.go.kr/openapi/service/BusRouteInfoInqireService/getRouteAcctoThrghSttnList?serviceKey=' . $serviceKey . '&cityCode=' . $cityCode . '&routeId=' . $route_id.'&numOfRows=999';
	$xml = connection ( $url );
	createCsv ( $xml, $cityCode, $route_no );
}

// addData(route_id, route_nm, station_id, station, station_nm, region, ord, cityCode)
function createCsv($xml, $cityCode, $route_no) {
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
			if ($put_arr [0] == 'nodeid') {
				$station_id = $put_arr [1];
			} else if ($put_arr [0] == 'nodenm') {
				$station_nm = $put_arr [1];
			} else if ($put_arr [0] == 'nodeord') {
				$ord = $put_arr [1];
			} else if ($put_arr [0] == 'routeid') {
				$route_id = $put_arr [1];
				echo '<li data-icon="plus">';
				echo '<a href="javascript:addData(\'' . $route_id . '\', \'' . $route_no . '\', \'' . $station_id . '\', \'' . $station . '\', \'' . $station_nm . '\', 4, \'' . $ord . '\', ' . $cityCode . ');">';
				echo '&darr;' . $station_nm;
				echo '</a></li>';
			}
		} else {
			createCsv ( $item, $cityCode, $route_no );
		}
	}
}

?>
