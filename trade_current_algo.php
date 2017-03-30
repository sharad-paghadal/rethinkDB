<?php
	//require_once("rdb/rdb.php");
    //$conn = r\connect('localhost');

    $totalTable = r\db("trade_cycle")->tableList()->run($conn);

    //delete it after proper working code
    // date_default_timezone_set("Asia/Kolkata");
    // $docForRawValue = array(
    //     "id" => "9".date("YmdHis"),
    //     "code" => "gold",
    //     "symbol_id" => 9,
    //     "current_price" => 1000,
    //     "time_stamp" => date("Y-m-d H:i:s")
    // );

    foreach ($totalTable as $table) {
        $cycleLimit = substr($table, 6);

        // main logic
        // if flag true{
        //     take that true flag as -> object,
        //      if ( docForRawValue[time_stamp] - object[time_stamp] <= diff should be according to table name ){
        //            update object according to high low ->>> open will remain same ->>> close will always latest
        //      }else{
        //         make flag flase in object --> remove flag key-value pair in object,
        //         make new object and push it to rethinkdb --> with flag true
        //      }

        // }else{
        //     make new object and push it to rethinkdb --> with flag true
        // }

        $incompleteCycleData = r\db("trade_cycle")->table($table)->orderBy(array("index" => "id"))->nth(-1)->run($conn);
        if($incompleteCycleData["flag"]){
            if(compareTime($docForRawValue["time_stamp"],$incompleteCycleData["time_stamp"]) <= $cycleLimit){
                //for update
                if($docForRawValue["current_price"] >= $incompleteCycleData["high"]){
                    r\db("trade_cycle")->table($table)->get($incompleteCycleData["id"])->update(array('high' => $docForRawValue["current_price"]))->run($conn);
                }elseif ($docForRawValue["current_price"] <= $incompleteCycleData["low"]) {
                    r\db("trade_cycle")->table($table)->get($incompleteCycleData["id"])->update(array('low' => $docForRawValue["current_price"]))->run($conn);
                }
                //for update close everytime
                r\db("trade_cycle")->table($table)->get($incompleteCycleData["id"])->update(array('close' => $docForRawValue["current_price"]))->run($conn);
            }else{
                //update to make flag false in $incompleteCycleData
                $updateQuery = r\db("trade_cycle")->table($table)->get($incompleteCycleData["id"])->replace(array
                    (
                        'id' => $incompleteCycleData["id"],
                        'open' => $incompleteCycleData["open"],
                        'high' => $incompleteCycleData["high"],
                        'low' => $incompleteCycleData["low"],
                        'close' => $incompleteCycleData["close"],
                        'time_stamp' => $incompleteCycleData["time_stamp"]
                        //just removed flag key value from here...
                    )
                )->run($conn);

                //make new object and push it to rethinkdb --> with flag true
                $newData = array(
                    'id' => $docForRawValue["id"],
                    'open' => $docForRawValue["current_price"],
                    'high' => $docForRawValue["current_price"],
                    'low' => $docForRawValue["current_price"],
                    'close' => $docForRawValue["current_price"],
                    'time_stamp' => $docForRawValue["time_stamp"],
                    'flag' => true
                );
                $insertNewData = r\db("trade_cycle")->table($table)->insert($newData)->run($conn);
            }
        }else{
            //make new object and push it to rethinkdb --> with flag true
            $newData = array(
                'id' => $docForRawValue["id"],
                'open' => $docForRawValue["current_price"],
                'high' => $docForRawValue["current_price"],
                'low' => $docForRawValue["current_price"],
                'close' => $docForRawValue["current_price"],
                'time_stamp' => $docForRawValue["time_stamp"],
                'flag' => true
            );
            $insertNewData = r\db("trade_cycle")->table($table)->insert($newData)->run($conn);
        }
    }
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