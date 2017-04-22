<?php

//URL : url?code=value

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $symbolCode = $_REQUEST['code'];

    $result = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->nth(-1)->pluck(array("current_price","time_stamp"))->run($conn);

    $response = array();

    $time = strtotime($result['time_stamp']);
    $price = (float) $result['current_price'];
    array_push($response, $time);
    array_push($response, $price);

    echo json_encode($response);

    $conn->close();
?>