<?php
// 전국버스 정류장 검색
include '../utils/util.php';
$cityCode = $_REQUEST ['cityCode'];
$nodeNm = $_REQUEST ['nodeNm'];
if ($nodeNm) {
	$url = 'http://openapi.tago.go.kr/openapi/service/BusSttnInfoInqireService/getSttnNoList?serviceKey=' . $serviceKey . '&cityCode=' . $cityCode . '&nodeNm=' . urlencode ( $nodeNm );
	$xml = connection ( $url );
	createCsv ( $xml, $cityCode );
}
function createCsv($xml, $cityCode) {
	$nodeId; // 정류소 ID
	$nodeNm; // 정류소명
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'nodeid') {
				$nodeId = $put_arr [1];
			} else if ($put_arr [0] == 'nodenm') {
				$nodeNm = $put_arr [1];
				echo '<li><a href="javascript:getRouteListTago(' . $cityCode . ', \'' . $nodeId . '\',\'' . $nodeNm . '\');">' . $nodeNm . '</a></li>';
			}
		} else {
			createCsv ( $item, $cityCode );
		}
	}
}
?>