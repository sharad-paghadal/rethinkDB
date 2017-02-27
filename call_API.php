<?php
    require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

    $result = r\db("protrade")->table("call")->orderBy(array("index" => "id"))->nth(-1)->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";

    $response['status'] = "SUCCESS";
    $response['data'] = $result;

    if($response['status'] != "SUCCESS"){
        $response['status'] = "FAIL";
        $response['message'] = "Some Error";
    }

    echo json_encode($response);
?>