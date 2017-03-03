<?php
    require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

    $symbolName = $_REQUEST['name'];
    // $symbolName = "gold";

    $result = r\db("protrade")->table("trade")->orderBy(array("index" => "id"))->filter(array("name" => $symbolName))->nth(-1)->pluck(array("current_price"))->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";


    $response['status'] = "SUCCESS";
    $response['data'] = $result;
    // echo json_encode($doc);

    //access each element
    // echo $doc["name"];

    if($response['status'] != "SUCCESS"){
        $response['status'] = "FAIL";
        $response['message'] = "Some Error";
    }

    echo json_encode($response);
?>