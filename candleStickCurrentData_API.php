<?php

//URL : url?code=value&cycle=value

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $symbolCode = $_REQUEST['code'];
    $tableName = "cycle_".$_REQUEST['cycle'];

    $result = r\db("trade_cycle")->table($tableName)->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->nth(-1)->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";

    $response['status'] = "SUCCESS";
    $response['data'] = $result;
    echo json_encode($response['data']);

    $conn->close();
?>