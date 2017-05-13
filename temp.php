<?php
	require_once("constants.php");
	$mySqlConnection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        if (mysqli_connect_errno()) {
            die("Connection Failed : ".mysqli_connect_error());
        }
        $sql = "SELECT `id` FROM `symbol` WHERE `code` = 'ALUMINI 1' ";
        $result = mysqli_query($mySqlConnection, $sql);
        if ($result->num_rows == 0) {
            echo "0 results";
            mysqli_close($mySqlConnection);
            exit();
        }
        $symbol_id = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $symbol_id = $row;
        }
        mysqli_close($mySqlConnection);
        echo $symbol_id['id'];
?>