<?php
    require_once("rdb/rdb.php");
    $conn = r\connect('localhost');

    $firstData = TRUE;

    $tradeData = array();
    $tempTradeData = array();
    $tempTradeData['time_stamp'] = NULL;

    $delete = r\db("protrade")->table('trade')->delete()->run($conn);

    $result = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->run($conn);
    
    foreach ($result as $res) {

        if(compareTime($res['time_stamp'], $tempTradeData['time_stamp']) > 5){
            array_push($tradeData, $tempTradeData);
            $tempTradeData = NULL;
            $firstData = TRUE;
        }

        if ($firstData) {
            $tempTradeData['id'] = $res['id'];
            $tempTradeData['open'] = $res['current_price'];
            $tempTradeData['high'] = $res['current_price'];
            $tempTradeData['low'] = $res['current_price'];
            $tempTradeData['close'] = $res['current_price'];
            $tempTradeData['time_stamp'] = $res['time_stamp'];
            $firstData = FALSE;
        }else{
            if($tempTradeData['high'] < $res['current_price']){
                $tempTradeData['high'] = $res['current_price'];
            }
            if($tempTradeData['low'] > $res['current_price']){
                $tempTradeData['low'] = $res['current_price'];
            }
            $tempTradeData['close'] = $res['current_price'];
        }

        $result1 = r\db("protrade")->table("rawvalue")->orderBy(array("index" => "id"))->nth(-1)->pluck(array("id"))->run($conn);

        if($res['id'] == $result1['id']){
            $tempTradeData['flag'] = TRUE;
            array_push($tradeData, $tempTradeData);
            $tempTradeData = NULL;
            $firstData = TRUE;
        }
    }

    echo json_encode($tradeData);
    $result = r\db("protrade")->table("trade")->insert($tradeData)->run($conn);
    // echo "Data Inserted into Tarde table\t";

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