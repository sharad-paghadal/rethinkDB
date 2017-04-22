<?php

//URL : url?code=value&cycle=value

    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    $symbolCode = $_REQUEST['code'];
    $tableName = "cycle_".$_REQUEST['cycle'];

    $result = r\db("trade_cycle")->table($tableName)->orderBy(array("index" => "id"))->filter(array("code" => $symbolCode))->nth(-1)->run($conn);

    $response = array();



        $time = strtotime($result['time_stamp']);
        $open = (float) $result['open'];
        $high = (float) $result['high'];
        $low = (float) $result['low'];
        $close = (float) $result['close'];

        array_push($response, $time);
        array_push($response, $open);
        array_push($response, $high);
        array_push($response, $low);
        array_push($response, $close);

    echo json_encode($response);

    $conn->close();
?>