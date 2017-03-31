<?php

	require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

	$request = $_REQUEST["req"];

	if($request == "trade_price"){

	    $result = r\db("protrade")->table("trade")->orderBy(array("index" => "id"))->nth(-1)->pluck(array("price"))->run($conn);

	    $response = array();
	    $response['data'] = array();
	    $response['status'] = "";

	    $response['status'] = "SUCCESS";
	    $response['data'] = $result;

	    echo json_encode($response);

	}else if($request == "call"){
		$result = r\db("protrade")->table("call")->orderBy(array("index" => "id"))->nth(-1)->run($conn);

	    $response = array();
	    $response['data'] = array();
	    $response['status'] = "";

	    $response['status'] = "SUCCESS";
	    $response['data'] = $result;

	    echo json_encode($response);
	}else if($request == "trade_all"){

		$result = r\db("protrade")->table("trade")->orderBy(array("index" => "id"))->pluck(array("price"))->run($conn);
		$count = r\db("protrade")->table("trade")->count()->run($conn);

	    $response = array();
	    $response['data'] = array();
	    $response['status'] = "";
	    $response['count'] = $count;

	    $response['status'] = "SUCCESS";

	    foreach ($result as $doc) {
	    	array_push($response['data'], $doc);
    	}

	    echo json_encode($response);
	}

	$conn->close();
?>