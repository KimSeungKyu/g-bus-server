<?php
/**
 * 부산 버스 검색
 */

include '../utils/util.php';
$route_nm = $_REQUEST ['routeNm'];
if ($route_nm) {
	$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/busInfo?serviceKey=' . $serviceKey . '&busno=' . urlencode($route_nm);
	$xml = connection($url);
	createCsv ( $xml );
}

function createCsv($xml) {
	$lineId;
	$buslinenum;
	$bustype;
	foreach ( $xml -> children () as $item ) {
		$hasChild = count ( $item -> children () ) > 0;
		if (! $hasChild) {
			$put_arr = array ( $item -> getName (), $item );
			if ($put_arr [0] == 'lineId') {
				$lineId = $put_arr [1];
				echo '<li><a href="javascript:getStationListBusan(' . $lineId . ');"><font color="gray">'.substr($bustype, 0, strpos($bustype, '버스')).'</font> <font color="blue">'.$buslinenum.'</font></a></li>';
			} else if ($put_arr [0] == 'buslinenum') {
				$buslinenum = $put_arr [1];
			} else if ($put_arr [0] == 'bustype') {
				$bustype = $put_arr [1];
			}
		} else {
			createCsv ( $item );
		}
	}
}

?>