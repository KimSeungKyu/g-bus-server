<?php
/**
 * 부산의 정류장 검색
 */

include '../utils/util.php';
$station_nm = $_REQUEST ['stationNm'];
if ($station_nm) {
	//정류소 명으로 검색
	$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/busStop?serviceKey=' . $serviceKey . '&bstopnm=' . urlencode($station_nm);
	$xml = connection($url);
	createCsv ( $xml );
	
	//정류소 번호로 검색
	$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/busStop?serviceKey=' . $serviceKey . '&arsno=' . urlencode($station_nm);
	$xml = connection($url);
	createCsv ( $xml );
}

function createCsv($xml) {
	$bstopArsno;
	$bstopId;
	$bstopNm;
	foreach ( $xml -> children () as $item ) {
		$hasChild = count ( $item -> children () ) > 0;
		if (! $hasChild) {
			$put_arr = array ( $item -> getName (), $item );
			if ($put_arr [0] == 'bstopArsno') {
				$bstopArsno = $put_arr [1];
			} else if ($put_arr [0] == 'bstopId') {
				$bstopId = $put_arr [1];
			} else if ($put_arr [0] == 'bstopNm') {
				$bstopNm = $put_arr [1];
				echo '<li><a href="javascript:getRouteListBusan(\'' . $bstopId . '\',\'' . $bstopNm . '\',\'' . $bstopArsno . '\');">';
				echo $bstopNm . ' <font color="gray">(' . getStationNo ( $bstopArsno ) . ')</font>';
				echo '</a></li>';
			}
		} else {
			createCsv ( $item );
		}
	}
}
?>