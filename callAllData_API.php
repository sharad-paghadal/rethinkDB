<?php

//URL : url

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $result = r\db("protrade")->table("call")->pluck(array("code","type","rate"))->run($conn);

    $response = array();

    foreach ($result as $doc) {
    	array_push($response, $doc);
	}
    echo json_encode($response);

    $conn->close();
?>