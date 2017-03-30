<!-- insert into rethink while changing the value of symbol, as well as check for insert into cell table... -->
<?php
	require_once("rdb/rdb.php");

	$symbol_code = $_REQUEST["code"];
	$currentPrice = $_REQUEST["currentPrice"];
	
	$conn=r\connect('localhost');

	//finding timestamp
	date_default_timezone_set("Asia/Kolkata");

	$docForRawValue = array(
		"id" => getSymbolId($symbol_code).date("YmdHis"),
		"code" => $symbol_code,
		"symbol_id" => getSymbolId($symbol_code),
		"current_price" => $currentPrice,
		"time_stamp" => date("Y-m-d H:i:s")
		);

	if($docForRawValue["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$insertIntoRawValueQuery = r\db("protrade")->table("rawvalue")->insert($docForRawValue)->run($conn);
	echo "Data Inserted into rawvalue table\n";

	include 'trade_current_algo.php';

	//finding type - BUY OR SELL
	$type = "";
	if($currentPrice < 2000){
		$type = "BUY";
	}elseif ($currentPrice > 3000) {
		$type = "SELL";
	}else{
		exit();
	}

	$docForCall = array(
		"id" => $symbol_code.date("YmdHis"),
		"timeStamp" => date("Y-m-d H:i:s"),
		"symbol_id" => getSymbolId($symbol_code),
		"type" => $type,
		"rate" => $currentPrice
		);

	if($docForCall["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$insertIntoCallQuery = r\db("protrade")->table("call")->insert($docForCall)->run($conn);
	echo "Data Inserted into Call table\t";

    // Functions
    function getSymbolId($symbol_code){
		$server = "localhost";
		$user = "root";
		$pass = "";
		$dbName = "protrade";
		$connn = mysqli_connect($server,$user,$pass,$dbName);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT  `id` FROM `symbol` WHERE `code` = '$symbol_code' ";
		$result = mysqli_query($connn, $sql);
		if ($result->num_rows == 0) {
			echo "0 results";
		    exit();
		}
		$symbol_id = "";

		while ($row = mysqli_fetch_assoc($result)) {
			$symbol_id = $row;
		}

		mysqli_close($connn);

		return $symbol_id['id'];
	}
?>