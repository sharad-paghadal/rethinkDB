<?php
	require_once("constants.php");
	$connn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        if (mysqli_connect_errno()) {
            die("Connection Failed : ".mysqli_connect_error());
        }
        $sql = "SELECT `code` FROM `symbol`";
        $result = mysqli_query($connn, $sql);
        if ($result->num_rows == 0) {
            echo "0 results";
            $conn->close();
            mysqli_close($connn);
            exit();
        }
        $allSymbols = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($allSymbols, $row['code']);
        }
        mysqli_close($connn);
        print_r($allSymbols);
?>