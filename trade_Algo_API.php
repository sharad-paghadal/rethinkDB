<?php
    require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

    //setup table
    $tableName = "cycle_".$_REQUEST["cycle"];
    if (strlen($tableName) < 7) {
        exit();
    }
    $tableCreate = r\db('trade_cycle')->tableCreate($tableName)->run($conn);
    echo "Table Created...";

    $firstData = TRUE;

    $tradeData = array();
    $tempTradeData = array();
    $tempTradeData['time_stamp'] = NULL;

    $documents = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->run($conn);
    
    foreach ($documents as $document) {

        if(compareTime($document['time_stamp'], $tempTradeData['time_stamp']) > substr($tableName, 6)){
            echo "here";
            array_push($tradeData, $tempTradeData);
            $tempTradeData = NULL;
            $firstData = TRUE;
        }

        if ($firstData) {
            $tempTradeData['id'] = $document['id'];
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

        $checkDoc = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->nth(-1)->pluck(array("id"))->run($conn);

        if($document['id'] == $checkDoc['id']){
            $tempTradeData['flag'] = TRUE;
            array_push($tradeData, $tempTradeData);
            $tempTradeData = NULL;
            $firstData = TRUE;
        }
    }

    echo json_encode($tradeData);
    $insertIntoTableQuery = r\db("trade_cycle")->table($tableName)->insert($tradeData)->run($conn);

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
?>