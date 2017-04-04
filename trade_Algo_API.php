<?php
    require_once("rdb/rdb.php");
    require_once("constants.php");
    $conn = r\connect(DB_HOST);

    //setup table
    $tableName = "cycle_".$_REQUEST["cycle"];
    if (strlen($tableName) < 7) {
        $conn->close();
        exit();
    }
    $tableCreate = r\db('trade_cycle')->tableCreate($tableName)->run($conn);
    echo "Table Created...";

    $firstData = TRUE;

    $tradeData = array();
    $tempTradeData = array();
    $tempTradeData['time_stamp'] = NULL;

    // push all symbol from mysql to array
    // foreach element of array as ar
    //     find document having code = ar
    //     foreach document 
    //         

    $allSymbols = getAllSymbols();
    foreach ($allSymbols as $symbol) {
        $documents = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbol))->run($conn);
        foreach ($documents as $document) {
            if(compareTime($document['time_stamp'], $tempTradeData['time_stamp']) > substr($tableName, 6)){
                array_push($tradeData, $tempTradeData);
                $tempTradeData = NULL;
                $firstData = TRUE;
            }

            if ($firstData) {
                $tempTradeData['id'] = $document['id'];
                $tempTradeData['code'] = $document['code'];
                $tempTradeData['open'] = $document['current_price'];
                $tempTradeData['high'] = $document['current_price'];
                $tempTradeData['low'] = $document['current_price'];
                $tempTradeData['close'] = $document['current_price'];
                $tempTradeData['time_stamp'] = $document['time_stamp'];
                $firstData = FALSE;
            }else{
                if($tempTradeData['high'] < $document['current_price']){
                    $tempTradeData['high'] = $document['current_price'];
                }
                if($tempTradeData['low'] > $document['current_price']){
                    $tempTradeData['low'] = $document['current_price'];
                }
                $tempTradeData['close'] = $document['current_price'];
            }

            $checkDoc = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->filter(array("code" => $symbol))->nth(-1)->pluck(array("id"))->run($conn);

            if($document['id'] == $checkDoc['id']){
                $tempTradeData['flag'] = TRUE;
                array_push($tradeData, $tempTradeData);
                $tempTradeData = NULL;
                $firstData = TRUE;
            }
        }
    }

    echo json_encode($tradeData);
    $insertIntoTableQuery = r\db("trade_cycle")->table($tableName)->insert($tradeData)->run($conn);

    $conn->close();

    // Function Area
    function compareTime($currentTimeStr, $lastTimeStr) {
        date_default_timezone_set("Asia/Kolkata");

        if($lastTimeStr == NULL){
            return -1;
        }

        $currentTime = new DateTime($currentTimeStr);
        $lastTime = new DateTime($lastTimeStr);

        $timeDiff  = $lastTime->diff($currentTime);
        return $timeDiff->i; 

    }

    function getAllSymbols(){
        $connn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        if (mysqli_connect_errno()) {
            die("Connection Failed : ".mysqli_connect_error());
        }
        $sql = "SELECT  `code` FROM `symbol`";
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
        return $allSymbols;
    }
?>