<!-- insert into rethink while changing the value of symbol, as well as check for insert into cell table... -->
<?php
	require_once("rdb/rdb.php");
	require_once("constants.php");

	$symbol_code = $_REQUEST["code"];
	$currentPrice = $_REQUEST["currentPrice"];
	
	$conn=r\connect(DB_HOST);

	//finding timestamp
	date_default_timezone_set("Asia/Kolkata");

	$symbol_id = getSymbolId($symbol_code);

	if($symbol_id == ""){
		// echo "Symbol Id not found in mysql database";
		$conn->close();
		exit();
	}

	$docForRawValue = array(
		"id" => $symbol_id.date("YmdHis"),
		"code" => $symbol_code,
		"symbol_id" => $symbol_id,
		"current_price" => $currentPrice,
		"time_stamp" => date("Y-m-d H:i:s")
		);

	$insertIntoRawValueQuery = r\db("protrade")->table("rawvalue")->insert($docForRawValue)->run($conn);
	echo "Data Inserted into rawvalue table <br>";

	include 'trade_current_algo.php';

	//finding type - BUY OR SELL
	$type = "";
	$call = getMinMax($symbol_code);
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
		"id" => $symbol_code.date("YmdHis"),
		"timeStamp" => date("Y-m-d H:i:s"),
		"symbol_id" => $symbol_id,
		"type" => $type,
		"rate" => $currentPrice
		);

	if($docForCall["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		$conn->close();
		exit();
	}

	$insertIntoCallQuery = r\db("protrade")->table("call")->insert($docForCall)->run($conn);
	echo "Data Inserted into Call table";

	$conn->close();

    // Functions
    function getSymbolId($symbol_code){
		$connn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT  `id` FROM `symbol` WHERE `code` = '$symbol_code' ";
		$result = mysqli_query($connn, $sql);
		if ($result->num_rows == 0) {
			echo "0 results";
			$conn->close();
			mysqli_close($connn);
		    exit();
		}
		$symbol_id = "";
		while ($row = mysqli_fetch_assoc($result)) {
			$symbol_id = $row;
		}
		mysqli_close($connn);
		return $symbol_id['id'];
	}

	function getMinMax($symbol_code){
		$connn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT  `call_min`,`call_max` FROM `symbol` WHERE `code` = '$symbol_code' ";
		$result = mysqli_query($connn, $sql);
		if ($result->num_rows == 0) {
			echo "0 results";
			$conn->close();
			mysqli_close($connn);
		    exit();
		}
		return mysqli_fetch_assoc($result);
	}
?>