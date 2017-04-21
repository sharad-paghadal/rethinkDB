<?php

	require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

	$symbolCode = $_REQUEST["code"];

		$result = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->pluck(array("current_price","time_stamp"))->run($conn);

	    $response = array();

	    foreach ($result as $doc) {
	    	$tempData = array();
	    	$time = strtotime($doc['time_stamp']);
	    	$price = (int) $doc['current_price'];
	    	array_push($tempData, $time);
	    	array_push($tempData, $price);
	    	
	    	array_push($response, $tempData);
    	}

	    echo json_encode($response);
	
	$conn->close();
?>