var map = [];

function setList() {
	var list = "";
	for (var i = 0; i < map.length; i++) {
		var station = map[i];
		list += '<li data-role="list-divider">';
		list += station.stationNm !== undefined ? station.stationNm : 'none';
		if (station.stationNum !== undefined && station.stationNum.length > 2) {
			if (station.stationNum.length === 4) {
				station.stationNum = "0" + station.stationNum;
			}
			list += " <font color=\"gray\">("
					+ station.stationNum.substring(0, 2) + "-"
					+ station.stationNum.substring(2) + ")</font>";
		}
		list += '</li>';
		for (var k = 0; k < station.routes.length; k++) {
			var route = station.routes[k];
			list += '<li><a>';
			list += "<font color=\"blue\">" + route.routeNm + "</font>";
			list += '</a><a href="javascript:delData(' + i + ', ' + k
					+ ')">삭제</a></li>';
		}
	}
	if (list.length === 0) {
		list += "<li>저장된 정보가 없습니다.</li>";
	}
	$('#savedList').empty();
	$('#savedList').append(list);
	$('#savedList').listview('refresh');
}

function addData(route_id, route_nm, station_id, station, station_nm, _region, _ord, _cityCode) {
	var existStation = false;
	for (var i = 0; i < map.length; i++) {
		var item = map[i];
		if (item.stationId == station_id) {
			existStation = true;
			var existRoute = false;
			for (var k = 0; k < item.routes.length; k++) {
				if (item.routes[k].routeId === route_id) {
					existRoute = true;
					break;
				}
			}
			if (!existRoute) {
				var index = item.routes.length;
				item.routes[index] = {
					routeId : route_id,
					routeNm : route_nm
				};
				if (_ord !== undefined)
					item.routes[index].ord = _ord;
				if (_cityCode !== undefined)
					item.routes[index].cityCode = _cityCode;
			}
			break;
		}
	}
	if (!existStation) {
		var index = map.length;
		if (_ord === undefined) {
			map[index] = {
				stationNm : station_nm,
				stationId : station_id,
				stationNum : station,
				routes : [ {
					routeId : route_id,
					routeNm : route_nm
				} ],
				region : _region
			};
		} else {
			map[index] = {
				stationNm : station_nm,
				stationId : station_id,
				stationNum : station,
				routes : [ {
					routeId : route_id,
					routeNm : route_nm,
					ord : _ord
				} ],
				region : _region
			};
		}
		if (_cityCode !== undefined)
			map[index].cityCode = _cityCode;
	}
	setList();
}

function delData(i, k) {
	console.log("remove 1 item");
	if (map[i].routes.length == 1) {
		map.splice(i, 1);
	} else {
		map[i].routes.splice(k, 1);
	}
	setList();
}
// <li>검색 결과가 없습니다.</li>

function search() {
	var searchValue = $("#routeNm").val();
	$('#searchList').empty();
	if (searchValue.length > 0) {
		var region = $("#region option:selected").val();
		if (region.length > 1) {
			region = region.substring(0, 1);
		}
		switch (region) {
		case "0":
			$.get("search-route-list-gyeonggi.php", {
				routeNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			$.get("search-station-list-gyeonggi.php", {
				stationNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			break;
		case "2":
			$.get("search-route-list-seoul.php", {
				routeNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			$.get("search-station-list-seoul.php", {
				routeNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			break;
		case "3":
			$.get("search-route-list-busan.php", {
				routeNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			$.get("search-station-list-busan.php", {
				stationNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			break;
		case "4": {
			var cityCode = $("#region option:selected").val().substring(1);
			console.log("cityCode: "
					+ cityCode
					+ ", search: " + searchValue);
			$.get("search-route-list-tago.php", {
				cityCode : cityCode,
				routeNo : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			$.get("search-station-list-tago.php", {
				cityCode : cityCode,
				nodeNm : searchValue
			}, function(data) {
				$('#searchList').append(data);
				$('#searchList').listview('refresh');
			});
			break;
		}
		}
	} else {
		$('#searchList').append("<li>검색어를 넣으세요.</li>");
		$('#searchList').listview('refresh');
	}
}

function getStationListGyeonggi(route_id) {
	$.get("get-station-list-gyeonggi.php", {
		routeId : route_id
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}
function getRouteListGyeonggi(stId, stNm, ars_id) {
	$.get("get-route-list-gyeonggi.php", {
		stationId : stId,
		stationNm : stNm,
		stationNo : ars_id
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getStationListSeoul(route_id) {
	$.get("get-station-list-seoul.php", {
		routeId : route_id
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getRouteListSeoul(stId, stNm, ars_id) {
	$.get("get-route-list-seoul.php", {
		stationId : stId,
		stationNm : stNm,
		arsId : ars_id
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getStationListBusan(route_id) {
	$.get("get-station-list-busan.php", {
		routeId : route_id
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getRouteListBusan(station_id, station_nm, station_no) {
	$.get("get-route-list-busan.php", {
		stationId : station_id,
		stationNm : station_nm,
		stationNo : station_no
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getStationListTago(city_code, route_id, route_no) {
	console.log("get-station-list-tago.php?cityCode="+city_code+"&routeId="+route_id+"&routeNo="+route_no);
	$.get("get-station-list-tago.php", {
		cityCode : city_code,
		routeId : route_id,
		routeNo : route_no
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

function getRouteListTago(city_code, node_id, node_nm) {
	console.log("get-route-list-tago.php?cityCode="+city_code+"&nodeId="+node_id+"&nodeNm="+node_nm);
	$.get("get-route-list-tago.php", {
		cityCode : city_code,
		nodeId : node_id,
		nodeNm : node_nm
	}, function(data) {
		$('#searchList').empty();
		$('#searchList').append(data);
		$('#searchList').listview('refresh');
	});
}

$().ready(
		function() {
			$('#b-reset').click(function() {
				console.log("clear");
				map = [];
				setList();
			});
			$("#b-submit").click(
					function() {
						console.log("Submit");
						var options = {
							busData : map,
							detailFontFamily : $("#big-text").val()
						};
						console.log("options: " + JSON.stringify(options));
						document.location = "pebblejs://close#"
								+ encodeURIComponent(JSON.stringify(options));
					});

		});

// 화면이 처음 불러왔을 때
$(document).ready(
		function() {
			setList();
			var settings = JSON.parse(decodeURIComponent(window.location.hash)
					.substr(1));
			console.log("settings: " + JSON.stringify(settings));
			if (settings.busData) {
				map = settings.busData;
				// 마이그레이션
				if (!map && !map[0].stationNm) {

					var temp = [];
					for (var i = 0; i < map.length; i++) {
						var subItem = [];
						for (var k = 0; k < map[i][2].length; k++) {
							subItem[k] = {
								routeId : map[i][2][k],
								routeNm : map[i][3][k]
							};
							if (map[i][5] !== undefined) {
								subItem[k].ord = map[i][5][k];
							}
						}
						temp[i] = {
							stationNm : map[i][0],
							stationId : map[i][1],
							routes : subItem,
							region : map[i][4]
						};
					}
					map = temp;
				}

			}
			if (map === undefined) {
				map = [];
			}
			setList();

			if (settings.detailFontFamily) {
				$('#big-text').val(settings.detailFontFamily).flipswitch(
						"refresh");
			}
		});