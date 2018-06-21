<?
/**
 * 경기도 버스 검색
 */

include "../utils/dbconn.php";
$route_nm = $_REQUEST ['routeNm'];
if ($route_nm) {
	$sql = 'SELECT route_nm, route_id, region_name, company_nm, st_sta_nm, ed_sta_nm FROM route WHERE route_nm LIKE "' . $route_nm . '%" order by route_nm;';
	$result = mysql_query ( $sql, $connect );
	$total = mysql_num_rows ( $result );
	if ($total) {
		for($i = 0; $i < $total; $i ++) {
			mysql_data_seek ( $result, $i );
			$row = mysql_fetch_array ( $result );
			echo '<li><a href="javascript:getStationListGyeonggi(' . $row [route_id] . ');">';
			echo $row[st_sta_nm].' → '.$row[ed_sta_nm].'<br/><font color="blue">'.$row [route_nm] . '</font> <font color="gray">' . $row [region_name].' ('.$row[company_nm].')</font>';
			echo '</a></li>';
		}
	}
}

?>