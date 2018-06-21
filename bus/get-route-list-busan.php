<?php
/**
 * 부산 버스 정류장 검색 목록에서 선택한 정류장에 오는 버스 목록
 */

include '../utils/util.php';
$station_id = $_REQUEST ['stationId'];
$station_nm = $_REQUEST ['stationNm'];
$station_no = $_REQUEST ['stationNo'];
if ($station_id) {
	$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/stopArr?serviceKey=' . $serviceKey . '&bstopid=' . $station_id;
	$xml = connection($url);
	echo '<li data-role="list-divider">' . $station_nm;
	if (strlen ( $station_no ) > 0) {
		echo ' <font color="gray">(' . getStationNo ( $station_no ) . ')</font>';
	}
	echo '</li>';
	createCsv ( $xml, $station_id );
}

//addData(route_id, route_nm, station_id, station, station_nm, _region, _ord)
function createCsv($xml) {
	$arsNo;//정류소번호
	$bstopId;//정류소ID
	$bustype;//버스 종류
	$lineNo;//버스번호
	$lineid;//노선ID
	$nodeNm;//정류소명
	foreach ( $xml -> children () as $item ) {
		$hasChild = count ( $item -> children () ) > 0;
		if (! $hasChild) {
			$put_arr = array ( $item -> getName (), $item );
			if ($put_arr [0] == 'arsNo') {
				$arsNo = $put_arr [1];
			} else if ($put_arr [0] == 'bstopId') {
				$bstopId = $put_arr [1];
			} else if ($put_arr [0] == 'bustype') {
				$bustype = $put_arr [1];
			} else if ($put_arr [0] == 'lineNo') {
				$lineNo = $put_arr [1];
			} else if ($put_arr [0] == 'lineid') {
				$lineid = $put_arr [1];
			} else if ($put_arr [0] == 'nodeNm') {
				$nodeNm = $put_arr [1];
				echo '<li><a href="javascript:addData(\'' . $lineid . '\',\'' . $lineNo . '\',\'' . $bstopId . '\',\'' . $arsNo . '\',\'' . $nodeNm . '\',3,0)">';
				echo '<font color="gray">'.substr($bustype, 0, strpos($bustype, '버스')).'</font> <font color="blue">'.$lineNo.'</font>';
				echo '</a></li>';
			}
		} else {
			createCsv ( $item );
		}
	}
}
?>