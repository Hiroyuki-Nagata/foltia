<?php
/*
 Anime recording system foltia
 http://www.dcc-jpl.com/soft/foltia/

settings.php

目的
SQLへのアクセスを行うコードをユーリーティ関数として使用する

引数
DBへのコネクション

 DCC-JPL Japan/foltia project

*/

// 放送局一覧情報を取得する
function get_foltia_station_data($con) {

    $station_array = array();
    $query = "SELECT stationid, stationname, stationrecch FROM foltia_station_temp";
    $rs = sql_query($con, $query, "DBクエリに失敗しました");

    $row = $rs->fetch();
    if ($row) {
	do {
	    //$val = var_dump($row);
	    $stationid = $row['stationid'];
	    $stationname = $row['stationname'];
	    $stationrecch = $row['stationrecch'];

	    array_push($station_array, $row);
	} while ($row = $rs->fetch());
    }

    return $station_array;
}

// 使用されている放送局のmap情報を取得する
function get_used_foltia_station_map($con) {

    $used_station_map = array();
    $query = "SELECT stationid, stationrecch FROM foltia_station";
    $rs = sql_query($con, $query, "DBクエリに失敗しました");

    $row = $rs->fetch();
    if ($row) {
	do {
	    $stationid = $row['stationid'];
	    $stationrecch = $row['stationrecch'];

	    $used_station_map['$stationid'] = $stationrecch;

	} while ($row = $rs->fetch());
    }

    return $used_station_map;
}

// 指定された放送局情報をfoltia_stationのテーブルに突っ込む
function set_foltia_station_recch($con, $post_map) {

    //$query = "DELETE FROM foltia_station WHERE stationid = {$post_map['stationid']}";
    //sql_query($con, $query, "DBクエリに失敗しました");

    $query = <<<EOF
INSERT INTO foltia_station (
    stationid,
    stationname, 
    stationrecch,
    stationcallsign,
    stationuri,  
    tunertype,   
    tunerch,     
    device,	     
    ontvcode,    
    digitalch,
    digitalstationband
) SELECT 
    stationid,
    stationname,
    '{$post_map['stationrecch']}' as stationrecch,
    stationcallsign,
    stationuri,  
    tunertype,   
    tunerch,     
    device,	     
    ontvcode,    
    digitalch,
    digitalstationband
FROM foltia_station_temp WHERE stationid = '{$post_map['stationid']}'

EOF
;
    logging($query);
    $rs = sql_query($con, $query, "DBクエリに失敗しました");
    logging(var_dump($rs));
}

?>
