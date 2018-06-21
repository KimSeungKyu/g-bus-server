<?php
/**
 * ��� ���� ������ �˻� ��Ͽ��� ������ �����忡 ���� ���� ���
 */
include '../utils/util.php';
$station_id = $_REQUEST ['stationId'];
$station_nm = $_REQUEST ['stationNm'];
$station_no = $_REQUEST ['stationNo'];
if ($station_id) {
	$url = 'http://openapi.gbis.go.kr/ws/rest/busstationservice/route?serviceKey=' . $serviceKey . '&stationId=' . $station_id;
	$xml = connection ( $url );
	echo '<li data-role="list-divider">' . $station_nm;
	if (strlen ( $station_no ) > 0) {
		echo ' <font color="gray">(' . getStationNo ( $station_no ) . ')</font>';
	}
	echo '</li>';
	createCsv ( $xml, $station_id, $station_nm, $station_no );
}

// addData(route_id, route_nm, station_id, station, station_nm, _region, _ord)
function createCsv($xml, $station_id, $station_nm, $station_no) {
	$arsNo; // �����ҹ�ȣ
	$bstopId; // ������ID
	$bustype; // ���� ����
	$routeName; // ������ȣ
	$routeId; // �뼱ID
	$nodeNm; // �����Ҹ�
	$staOrder; // ������ ����
	$resultCode;
	foreach ( $xml->children () as $item ) {
		$hasChild = count ( $item->children () ) > 0;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'routeTypeName') {
				$bustype = $put_arr [1];
			} else if ($put_arr [0] == 'routeName') {
				$routeName = $put_arr [1];
			} else if ($put_arr [0] == 'routeId') {
				$routeId = $put_arr [1];
			} else if ($put_arr [0] == 'staOrder') {
				$staOrder = $put_arr [1];
				echo '<li><a href="javascript:addData(\'' . $routeId . '\',\'' . $routeName . '\',\'' . $station_id . '\',\'' . $station_no . '\',\'' . $station_nm . '\',0,' . $staOrder . ')">';
				echo '<font color="gray">' . substr ( $bustype, 0, strpos ( $bustype, '����' ) ) . '</font> <font color="blue">' . $routeName . '</font>';
				echo '</a></li>';
			} else if ($put_arr [0] == 'routeName') {
				$nodeNm = $put_arr [1];
			} else if ($put_arr [0] == 'resultCode') {
				$resultCode = $put_arr [1];
			} else if ($put_arr [0] == 'resultMessage') {
				if ($resultCode != 0) {
					echo '<li>' . $put_arr [1] . '</li>';
					return;
				}
			}
		} else {
			createCsv ( $item, $station_id, $station_nm, $station_no );
		}
	}
}
?>