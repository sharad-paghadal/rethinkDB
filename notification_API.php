<?php

//URL : url

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $result = r\db("protrade")->table("call")->run($conn);

    $response = array();
    $response['data'] = array();
    $response['status'] = "";

    $response['status'] = "SUCCESS";
    $response['data'] = $result;
    echo json_encode($response);

    $conn->close();
?>