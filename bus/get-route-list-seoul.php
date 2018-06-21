<?php
/**
 * 서울 버스 정류장 검색 목록에서 선택한 정류장에 오는 버스 목록
 */

include '../utils/util.php';
$stId = $_REQUEST['stationId'];
$stNm = $_REQUEST['stationNm'];
$arsId = $_REQUEST ['arsId'];
if ($arsId){
	$url = 'http://ws.bus.go.kr/api/rest/stationinfo/getRouteByStation?serviceKey=' . $serviceKey . '&arsId=' . $arsId;
	$xml = connection($url);
	echo '<li data-role="list-divider">' . $stNm . '<font color="gray">(' . getStationNo ( $arsId ) . ')</font></li>';
	createCsv ( $xml, $stId, $stNm, $arsId );
}

// addData(route_id, route_nm, station_id, station, station_nm, region, ord)
function createCsv($xml, $stId, $stNm, $arsId) {
	$busRouteId;
	$busRouteNm;
	$busRouteType;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array ( $item->getName (), $item );
		if ($put_arr [0] == 'busRouteId') {
				$busRouteId = $put_arr [1];
			} else if ($put_arr [0] == 'busRouteNm') {
				$busRouteNm = $put_arr [1];
			} else if ($put_arr [0] == 'busRouteType') {
				// 노선 유형 (1:공항, 2:마을, 3:간선, 4:지선, 5:순환, 6:광역, 7:인천, 8:경기, 9:폐지, 0:공용)
				switch ($put_arr [1]) {
					case '1' :
						$busRouteType =  '공항';
						break;
					case '2' :
						$busRouteType =  '마을';
						break;
					case '3' :
						$busRouteType =  '간선';
						break;
					case '4' :
						$busRouteType =  '지선';
						break;
					case '5' :
						$busRouteType =  '순환';
						break;
					case '6' :
						$busRouteType =  '광역';
						break;
					case '7' :
						$busRouteType =  '인천';
						break;
					case '8' :
						$busRouteType =  '경기';
						break;
					case '9' :
						$busRouteType =  '폐지';
						break;
					case '0' :
						$busRouteType =  '공용';
						break;
					default :
						$busRouteType =  '';
						break;
				}
				echo '<li data-icon="plus">';
				echo '<a href="javascript:addData(\'' . $busRouteId . '\', \'' . $busRouteNm . '\', \'' . $stId . '\', \'' . $arsId . '\', \'' . $stNm . '\', 2, \'' . $ord . '\');">';
				echo '<font color="gray">'.$busRouteType.'</font> <font color="blue">'.$busRouteNm. '</font>';
				echo '</a></li>';
			}
		} else {
			createCsv ( $item, $stId, $stNm, $arsId );
		}
	}
}

?>
