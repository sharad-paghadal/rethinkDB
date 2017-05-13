<?php
//finding type - BUY OR SELL
	$type = "";
	$call = algorithmToGenerateCall($docForRawValue);
	if($currentPrice < $call['call_min']){
		$type = "BUY";
	}elseif ($currentPrice > $call['call_max']) {
		$type = "SELL";
	}else{
		$conn->close();
		exit();
	}

	$docForCall = array(
		"code" => $symbol_code,
		"id" => $symbol_id.date("YmdHis"),
		"timeStamp" => date("Y-m-d H:i:s"),
		"symbol_id" => $symbol_id,
		"type" => $type,
		"rate" => $currentPrice
		);

	$insertIntoCallQuery = r\db("protrade")->table("call")->insert($docForCall)->run($conn);
	//echo "Data Inserted into Call table";

	//funtion area
	function algorithmToGenerateCall($docForRawValue){
		$call = array(
			'call_min' => 2000,
			'call_max' => 3000
			);
		return $call;
	}
?>