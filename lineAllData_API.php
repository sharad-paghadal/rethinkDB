<?php

	require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

	$symbolCode = $_REQUEST["code"];

		$result = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->pluck(array("price"))->run($conn);
		$count = r\db("protrade")->table("rawvalue")->filter(array("code" => $symbolCode))->count()->run($conn);

	    $response = array();
	    $response['data'] = array();
	    $response['status'] = "";
	    $response['count'] = $count;

	    $response['status'] = "SUCCESS";

	    foreach ($result as $doc) {
	    	array_push($response['data'], $doc);
    	}

	    echo json_encode($response);
	

	$conn->close();
?>