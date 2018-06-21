<?
/**
 * 경기도 버스 정류장 검색
 */
include '../utils/util.php';
include "../utils/dbconn.php";
$station_nm = $_REQUEST ['stationNm'];
if ($station_nm) {
	// $sql = 'SELECT route_nm, route_id, station_id, station_nm, (SELECT region_name FROM route WHERE route_id = rs.route_id limit 1) as region_name, (SELECT mobile_no FROM station WHERE station_id = rs.station_id limit 1) as mobile_no FROM routestation rs WHERE station_id in (SELECT station_id FROM station WHERE station_nm like \'%' . $route_nm . '%\' or LPAD(mobile_no, 5, 0) like REPLACE(\'%' . $route_nm . '%\', \'-\', \'\')) order by route_nm limit 50;';
	// $result = mysql_query ( $sql, $connect );
	// $total = mysql_num_rows ( $result );
	// if ($total) {
	// for($i = 0; $i < $total; $i ++) {
	// mysql_data_seek ( $result, $i );
	// $row = mysql_fetch_array ( $result );
	// echo '<li data-icon="plus">';
	// echo '<a href="javascript:addData(\'' . $row [route_id] . '\', \'' . $row [route_nm] . '\', \'' . $row [station_id] . '\', \'' . $row [mobile_no] . '\', \'' . $row [station_nm] . '\', 0);">';
	// echo $row [station_nm] . " <font color='gray'>(" . substr_replace ( sprintf ( "%05d", $row [mobile_no] ), "-", 2, 0 ) . ")</font>";
	// echo "<br/>";
	// echo "<font color='blue'>" . $row [route_nm] . "</font> <font color='gray'>" . $row [region_name]."</font>";
	// echo '</a></li>';
	// }
	// }
	$url = 'http://openapi.gbis.go.kr/ws/rest/busstationservice?serviceKey=' . $serviceKey . '&keyword=' . urlencode ( $station_nm );
	$xml = connection ( $url );
	createCsv ( $xml );
}
function createCsv($xml) {
	$stId; // 정류소 ID
	$stNm; // 정류소명
	$arsId; // 정류소 고유번호
	$resultCode;
	foreach ( $xml->children () as $item ) {
		$hasChild = (count ( $item->children () ) > 0) ? true : false;
		if (! $hasChild) {
			$put_arr = array (
					$item->getName (),
					$item 
			);
			if ($put_arr [0] == 'stationId') {
				$stId = $put_arr [1];
			} else if ($put_arr [0] == 'mobileNo') {
				$arsId = trim($put_arr [1]);
			} else if ($put_arr [0] == 'stationName') {
				$stNm = $put_arr [1];
				echo '<li><a href="javascript:getRouteListGyeonggi(\'' . $stId . '\',\'' . $stNm . '\',\'' . $arsId . '\');">';
				echo $stNm . ' <font color="gray">(' . getStationNo ( $arsId ) . ')</font></a></li>';
			} else if ($put_arr [0] == 'resultCode') {
				$resultCode = $put_arr [1];
			} else if ($put_arr [0] == 'resultMessage') {
				if ($resultCode != 0) {
					echo '<li>' . $put_arr [1] . '</li>';
					return;
				}
			}
		} else {
			createCsv ( $item );
		}
	}
}
?>