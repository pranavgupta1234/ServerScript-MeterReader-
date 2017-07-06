<?php

/** this file will receive the post parameters and then after processing them will finally store the reading
*/


/**
 * Created by PhpStorm.
 * User: Pranav Gupta
 * Date: 7/4/2017
 * Time: 4:05 PM
 */

require("../api/config.php");

if(!empty($_POST)){

    if(!empty($_POST['campus'])){
        $campus_number = $_POST['campus'];
    }

    $building_no = $_POST['building'];
    $flat_no = $_POST['flat'];
    $readingValue = $_POST['ReadingValue'];
    $remarks = $_POST['Remarks'];
    $takenBy = $_POST['TakenBy'];

    //$building_no = mysql_real_escape_string($building_no);
    //$flat_no = mysql_real_escape_string($flat_no);


    $response = array("error" => FALSE);

    $query = "SELECT FlatId FROM Flats WHERE BuildingName = '$building_no' AND FlatNumber = '$flat_no' ;";

    try{

        $stmt = $db -> prepare($query);
        $result = $stmt -> execute();
        $data = $stmt -> fetch();

    }catch (Exception $ex){
        $response["error"] = TRUE;
        $response["message"] = "Database error !";
        die(json_encode($response));
    }

    //echo json_encode($data);

    $data = $data["FlatId"];

    $yo = "hu";

    $query_new_reading = "INSERT INTO MeterReadings ( FlatId, TakenOn, ReadingValue,Remarks,TakenBy,UpdatedOn,CreatedOn ) VALUES ( '$data', NOW(),'$readingValue','$remarks','$takenBy',NOW(),NOW()) ";

    try{
        $new_entry_stmt = $db -> prepare($query_new_reading);
        $result_new_reading = $new_entry_stmt -> execute();
    }catch (Exception $ex){
        $response["error"] = TRUE;
        $response["message"] = "Database error2 !";
        die(json_encode($response));
    }

    $response["message"] = "Successful entry done!";

    echo json_encode($response);

} else {
    echo "Empty post params";
}

?>