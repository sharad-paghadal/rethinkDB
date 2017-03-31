<?php
    require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

    $symbolCode = $_REQUEST['name'];

    $result = r\db("protrade")->table("trade")->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->nth(-1)->pluck(array("current_price"))->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";

    $response['status'] = "SUCCESS";
    $response['data'] = $result;
    echo json_encode($response);

    $conn->close();
?>