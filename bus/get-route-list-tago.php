<?php
/**
 * 국토교통부 버스 정류장 검색 목록에서 선택한 정류장에 오는 버스 목록
 */
include '../utils/util.php';
$cityCode = $_REQUEST ['cityCode'];
$nodeId = $_REQUEST ['nodeId'];
$nodeNm = $_REQUEST ['nodeNm'];

$url = 'http://openapi.tago.go.kr/openapi/service/ArvlInfoInqireService/getSttnAcctoArvlPrearngeInfoList?serviceKey=' . $serviceKey . '&cityCode=' . $cityCode . '&nodeId=' . urlencode ( $nodeId ).'&numOfRows=999';
$xml = connection ( $url );
echo '<li data-role="list-divider">' . $nodeNm . '</li>';
echo "<li>정류소별 버스목록 조회기능이 없어서</li>";
echo "<li>도착정보를 이용하기 때문에 버스가</li>";
echo "<li>운행중이지 않으면 버스가 안나올 </li>";
echo "<li>수도 있습니다.</li>";
createCsv ( $xml, $cityCode, $nodeId, $nodeNm );

// addData(route_id, route_nm, station_id, station, station_nm, region, ord, cityCode)
function createCsv($xml, $cityCode, $nodeId, $nodeNm) {
	$busRouteId;
	$busRouteNm;
	$busRouteType;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item
			);
			if ($put_arr [0] == 'routeid') {
				$busRouteId = $put_arr [1];
			} else if ($put_arr [0] == 'routeno') {
				$busRouteNm = $put_arr [1];
			} else if ($put_arr [0] == 'routetp') {
				echo '<li data-icon="plus">';
				echo '<a href="javascript:addData(\'' . $busRouteId . '\', \'' . $busRouteNm . '\', \'' . $nodeId . '\', \'' . $arsId . '\', \'' . $nodeNm . '\', 4, \'' . $ord . '\', ' . $cityCode . ');">';
				echo '<font color="gray">' . $busRouteType . '</font> <font color="blue">' . $busRouteNm . '</font>';
				echo '</a></li>';
			}
		} else {
			createCsv ( $item, $cityCode, $nodeId, $nodeNm );
		}
	}
}

?>
