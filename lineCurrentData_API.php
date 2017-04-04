<?php

//URL : url?code=value

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $symbolCode = $_REQUEST['code'];

    $result = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->nth(-1)->pluck(array("current_price"))->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";

    $response['status'] = "SUCCESS";
    $response['data'] = $result;
    echo json_encode($response);

    $conn->close();
?>