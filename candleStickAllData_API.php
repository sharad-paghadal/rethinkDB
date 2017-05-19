<?php

//URL : url?code=value&cycle=value

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $symbolCode = $_REQUEST['code'];
    $tableName = "cycle_".$_REQUEST['cycle'];

    $result = r\db("trade_cycle")->table($tableName)->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->run($conn);

    $response = array();

    foreach ($result as $doc) {
    	$tempData = array();
    	$time = strtotime($doc['time_stamp']);
    	$open = (float) $doc['open'];
    	$high = (float) $doc['high'];
    	$low = (float) $doc['low'];
    	$close = (float) $doc['close'];

    	array_push($tempData, ($time+7200)*1000);
    	array_push($tempData, $open);
    	array_push($tempData, $high);
    	array_push($tempData, $low);
    	array_push($tempData, $close);

    	array_push($response, $tempData);
	}
    echo json_encode($response);

    $conn->close();
?>