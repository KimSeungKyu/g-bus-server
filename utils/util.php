<?php
$serviceKey = '공공데이터포털에서 발급 받은 키';

function connection($url) {
	$ch = curl_init (); // 파라미터:url -선택사항
	curl_setopt ( $ch, CURLOPT_URL, $url ); // 여기선 url을 변수로
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_HEADER, false );
	
	$data = curl_exec ( $ch );
	curl_close ( $ch );
	
	return simplexml_load_string ( substr ( $data, strpos ( $data, "<" ) ) );
}

function getStationNo($stationNo) {
	return substr_replace ( sprintf ( "%05d", $stationNo ), "-", 2, 0 );
}

function getCitiCodeToName($cityCode){
	switch($cityCode){
		case 22:
			return '대구광역시';
		case 23:
			return '인천광역시';
		case 24:
			return '광주광역시';
		case 25:
			return '대전광역시';
		case 26:
			return '울산광역시';
		case 39:
			return '제주도';
		case 32010:
			return '춘천시';
		case 32020:
			return '원주시';
		case 33010:
			return '청주시';
		case 34010:
			return '천안시';
		case 34040:
			return '아산시';
		case 35010:
			return '전주시';
		case 35020:
			return '군산시';
		case 36020:
			return '여수시';
		case 36030:
			return '순천시';
		case 36060:
			return '광양시';
		case 37010:
			return '포항시';
		case 437100:
			return '경산시';
		case 38010:
			return '창원시';
		case 38030:
			return '진주시';
		case 38050:
			return '통영시';
		case 38070:
			return '김해시';
		case 38080:
			return '밀양시';
		case 38090:
			return '거제시';
		case 38100:
			return '양산시';
	}
	return '';
}

?>