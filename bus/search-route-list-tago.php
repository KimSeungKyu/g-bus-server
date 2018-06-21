<?php
// 국토교통부 버스 검색
include '../utils/util.php';
$cityCode = $_REQUEST ['cityCode'];
$route_nm = $_REQUEST ['routeNo'];
if ($route_nm) {
	$url = 'http://openapi.tago.go.kr/openapi/service/BusRouteInfoInqireService/getRouteNoList?serviceKey=' . $serviceKey . '&cityCode=' . $cityCode . '&routeNo=' . $route_nm;
	$xml = connection ( $url );
	createCsv ( $xml, $cityCode );
}
function createCsv($xml, $cityCode) {
	$routeId;
	$routeNo;
	$routeType;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'routeid') {
				$routeId = $put_arr [1];
			} else if ($put_arr [0] == 'routeno') {
				$routeNo = $put_arr [1];
			} else if ($put_arr [0] == 'routetp') {
				$routType = $put_arr [1];
				echo '<li><a href="javascript:getStationListTago(\'' . $cityCode . '\', \'' . $routeId . '\', \'' . $routeNo. '\');"><font color="gray">' . $routType . '</font> <font color="blue">' . $routeNo . '</font></a></li>';
			}
		} else {
			createCsv ( $item, $cityCode);
		}
	}
}
?>