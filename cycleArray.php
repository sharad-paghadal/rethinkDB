<?php
	require_once("rdb/rdb.php");
	require_once("constants.php");
	$conn=r\connect(DB_HOST);

	$totalTable = r\db("trade_cycle")->tableList()->run($conn);

	$response = array();
	foreach ($totalTable as $table) {
        $cycleLimit = (int) substr($table, 6);
        array_push($response, $cycleLimit);
    }

    echo json_encode($response);
?>