<!-- insert into rethink while changing the value of symbol, as well as check for insert into cell table... -->
<?php
	require_once("rdb/rdb.php");
	$symbol_name = $_REQUEST["name"];
	$value=$_REQUEST["value"];
	$conn=r\connect('localhost');
	//r\db("test")->tableCreate("My")->run($conn);

	$count = r\db("protrade")->table("trade")->count()->run($conn);

	$doc=array(
		"id"=>$count+1,
		"name"=>$symbol_name,
		"symbol_id"=>getSymbolId($symbol_name),
		"price"=>$value
		);

	if($doc["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$result = r\db("protrade")->table("trade")->insert($doc)->run($conn);
	echo "Data Inserted into trade table\n";
	// print_r($result);
	$count = r\db("protrade")->table("call")->count()->run($conn);

	//finding timestamp
	$ts = time();
	$date = new DateTime("@$ts");
	$finalTimeStamp =  $date->format('Y-m-d H:i:s');

	//finding type - BUY OR SELL
	$type = "";
	if($value < 2000){
		$type = "BUY";
	}elseif ($value > 3000) {
		$type = "SELL";
	}else{
		exit();
	}

	$docForCall = array(
		"id" => $count+1,
		"timeStamp" => $finalTimeStamp,
		"symbol_id" => getSymbolId($symbol_name),
		"type" => $type,
		"rate" => $value
		);

	if($docForCall["symbol_id"] == ""){
		// echo "Symbol Id not found in mysql database";
		exit();
	}

	$result = r\db("protrade")->table("call")->insert($docForCall)->run($conn);
	echo "Data Inserted into Call table\t";

    // Functions
    function getSymbolId($symbol_name){
		$server = "localhost";
		$user = "root";
		$pass = "";
		$dbName = "protrade";
		$connn = mysqli_connect($server,$user,$pass,$dbName);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT  `id` FROM `symbol` WHERE `name` = '$symbol_name' ";
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