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

	include 'insertIntoCall.php';

	$conn->close();

    // Functions
    function getSymbolId($symbol_code){
		$mySqlConnection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT `id` FROM `symbol` WHERE `code` = '$symbol_code' ";
		$result = mysqli_query($mySqlConnection, $sql);
		if ($result->num_rows == 0) {
			echo "0 results";
			$conn->close();
			mysqli_close($mySqlConnection);
			exit();
		}
		$symbol_id = "";
		while ($row = mysqli_fetch_assoc($result)) {
			$symbol_id = $row;
		}
		mysqli_close($mySqlConnection);
		return $symbol_id['id'];
	}
?>