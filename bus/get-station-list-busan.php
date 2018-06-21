<?php
/**
 * 遺��궛 踰꾩뒪 寃��깋 紐⑸줉�뿉�꽌 �꽑�깮�븳 踰꾩뒪�쓽 �쟾泥� �끂�꽑
 */
include '../utils/util.php';
$route_id = $_REQUEST ['routeId'];
if ($route_id) {
	$ch = curl_init ();
	$url = 'http://61.43.246.153/openapi-data/service/busanBIMS2/busInfoRoute?serviceKey='.$serviceKey.'&numOfRows=999&lineid=' . $route_id;
	$xml = connection ( $url );
	createCsv ( $xml, $route_id );
	// echo $data;
}

// addData(route_id, route_nm, station_id, station, station_nm, region, ord)
function createCsv($xml, $route_id) {
	$lineNo;
	$nodeId;
	$bstopnm;
	$arsNo;
	$bstopIdx;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'lineNo') {
				$lineNo = $put_arr [1];
			} else if ($put_arr [0] == 'nodeId') {
				$nodeId = $put_arr [1];
				echo '<li data-icon="plus">';
				echo '<a href="javascript:addData(\'' . $route_id . '\', \'' . $lineNo . '\', \'' . $nodeId . '\', \'' . $arsNo . '\', \'' . $bstopnm . '\', 3, \'' . $bstopIdx . '\');">&darr;' . $bstopnm;
				if (strlen ( $stationNo ) > 0) {
					echo ' <font color="gray">(' . getStationNo ( $arsNo ) . ')</font>';
				}
				echo '</a></li>';
			} else if ($put_arr [0] == 'arsNo') {
				$arsNo = $put_arr [1];
			} else if ($put_arr [0] == 'bstopnm') {
				$bstopnm = $put_arr [1];
			} else if ($put_arr [0] == 'bstopIdx') {
				$bstopIdx = $put_arr [1];
			}
		} else {
			createCsv ( $item, $route_id );
		}
	}
}
?>
