<?php
require_once("constants.php");
	$connn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if (mysqli_connect_errno()) {
			die("Connection Failed : ".mysqli_connect_error());
		}
		$sql = "SELECT  `call_min`,`call_max` FROM `symbol` WHERE `code` = 'gold' ";
		$result = mysqli_query($connn, $sql);
		if ($result->num_rows == 0) {
			echo "0 results";
			$conn->close();
			mysqli_close($connn);
		    exit();
		}

		print_r(mysqli_fetch_assoc($result));
		

		// while ($row = mysqli_fetch_assoc($result)) {
		// 	$symbol_id = $row;
		// }

		// mysqli_close($connn);

		// echo $symbol_id['id'];
?>