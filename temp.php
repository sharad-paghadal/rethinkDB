<?php
	require_once("rdb/rdb.php");
    $conn = r\connect('localhost');
    $incompleteCycleData = r\db("trade_cycle")->table("cycle_10")->orderBy(array("index" => "id"))->nth(-1)->run($conn);
    if($incompleteCycleData["flag"]){
        echo "Flag true";
    }else{
        $newData = array(
            'open' => 1000,
            'flag' => true
        );
        $insertNewData = r\db("trade_cycle")->table("cycle_10")->insert($newData)->run($conn);
    }
?>