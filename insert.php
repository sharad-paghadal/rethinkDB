<!-- insert into rethink while changing the value of symbol, as well as check for insert into cell table... -->
<?php
	require_once("rdb/rdb.php");

	$symbol_code = $_REQUEST["code"];
	$timeStamp = $_REQUEST["timeStamp"];
	$currentPrice = $_REQUEST["currentPrice"];
	$openPrice = $_REQUEST["openPrice"];
	$highPrice = $_REQUEST["highPrice"];
	$lowPrice = $_REQUEST["lowPrice"];
	$closePrice = $_REQUEST["closePrice"];
	
	$conn=r\connect('localhost');

	$count = r\db("protrade")->table("trade")->count()->run($conn);

	//finding timestamp
	date_default_timezone_set("Asia/Kolkata");
	$finalTimeStamp = date("Y-m-d H:i:s");

	$doc=array(
		"id"=>$count+1,
		"name"=>$symbol_code,
		"symbol_id"=>getSymbolId($symbol_code),
		"time_stamp" => $finalTimeStamp,
		"current_price" => $currentPrice,
		"open_price" => $openPrice,
		"high_price" => $highPrice,
		"low_price" => $lowPrice,
		"close_price" => $closePrice
	);

	if($doc["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$result = r\db("protrade")->table("trade")->insert($doc)->run($conn);
	echo "Data Inserted into trade table\n";
	// print_r($result);
	$count = r\db("protrade")->table("call")->count()->run($conn);

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
		"id" => $count+1,
		"timeStamp" => $finalTimeStamp,
		"symbol_id" => getSymbolId($symbol_code),
		"type" => $type,
		"rate" => $currentPrice
		);

	if($docForCall["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$result = r\db("protrade")->table("call")->insert($docForCall)->run($conn);
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
		$sql = "SELECT  `id` FROM `symbol` WHERE `name` = '$symbol_code' ";
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