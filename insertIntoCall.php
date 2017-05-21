<?php
//finding type - BUY OR SELL
	$type = "";
	$call = algorithmToGenerateCall($docForRawValue, $conn);
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
	echo "Data Inserted into Call table";

	//funtion area
	function algorithmToGenerateCall($docForRawValue, $conn){
            
                $data = r\db("protrade")->table("rawvalue")->orderBy(array("index" => r\desc("id")))->filter(array("code" => $docForRawValue["code"]))->limit(1000)->run($conn);
		$avg = 0;
                $devider = 0;
                foreach ($data as $document) {
                    $avg += (int) $document["current_price"];
                    $devider++;
                }
                $avg /= $devider;
                $call = array(
			'call_min' => $avg,
			'call_max' => $avg
			);
                return $call;
	}
?>