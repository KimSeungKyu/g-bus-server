<?
/**
 * 경기도 버스 검색 목록에서 선택한 버스의 전체 노선
 */

$route_id = $_REQUEST ['routeId'];
if ($route_id) {
	include "../utils/dbconn.php";
	
	$sql = 'SELECT route_id, station_id, route_nm, station_nm, updown, (select mobile_no from station where station_id = rs.station_id limit 1) as mobile_no FROM routestation rs WHERE route_id = "' . $route_id . '";';
	$result = mysql_query ( $sql, $connect );
	$total = mysql_num_rows ( $result );
	if ($total) {
		for($i = 0; $i < $total; $i ++) {
			mysql_data_seek ( $result, $i );
			$row = mysql_fetch_array ( $result );
			$stationName = $row [station_nm];
			$mobileNo = str_pad ( $row [mobile_no], 5, "0", STR_PAD_LEFT );
			echo '<li data-icon="plus">';
			echo '<a href="javascript:addData(\'' . $row [route_id] . '\', \'' . $row [route_nm] . '\', \'' . $row [station_id] . '\', \'' . $mobileNo . '\', \'' . $row [station_nm] . '\', 0, undefined);">';
			$color;
			if (strcmp ( $row [updown], '정' ) == 0)
				$color = '#0066FF';
			else
				$color = '#D63030';
			echo '<font color="' . $color . '">&darr;</font>';
			echo $stationName . '</a></li>';
		}
	} else {
		echo '<li>다시 시도해주세요.</li>';
	}
} else {
	echo '<li>다시 시도해주세요.</li>';
}
?>
