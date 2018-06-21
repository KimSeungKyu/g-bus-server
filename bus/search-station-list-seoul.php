<?php
/**
 * 서울 버스 정류장 검색
 */

include '../utils/util.php';
$route_nm = $_REQUEST ['routeNm'];
if ($route_nm) {
	$url = 'http://ws.bus.go.kr/api/rest/stationinfo/getStationByName?serviceKey=' . $serviceKey . '&stSrch=' . urlencode ( $route_nm );
	$xml = connection($url);
	createCsv ( $xml );
}
function createCsv($xml) {
	$stId;//정류소 ID
	$stNm;//정류소명
	$arsId;//정류소 고유번호
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array ( $item->getName (), $item );
			if ($put_arr [0] == 'stId') {
				$stId = $put_arr [1];
			} else if ($put_arr [0] == 'arsId') {
				$arsId = $put_arr [1];
			} else if ($put_arr [0] == 'stNm') {
				$stNm = $put_arr [1];
				echo '<li><a href="javascript:getRouteListSeoul(\'' . $stId . '\',\'' . $stNm . '\',\'' . $arsId . '\');">';
				echo $stNm . ' <font color="gray">(' . getStationNo ( $arsId ) . ')</font></a></li>';
			}
		} else {
			createCsv ( $item );
		}
	}
}

?>