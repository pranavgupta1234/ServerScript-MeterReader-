<?php

require_once '../api/db_api.php';

$db = new DB_API();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['flat_id']) && isset($_POST['name']) && isset($_POST['takenOn']) && isset($_POST['reading'])&& isset($_POST['remarks'])) {

    // receiving the post params
    $name = $_POST['name'];
    $flat_id = $_POST['flat_id'];
    $newReading = $_POST['reading'];
    $remarks = $_POST['remarks'];
    $takenOn = $_POST['takenOn'];

    // create a new user
    $user = $db -> storeReading($flat_id,$takenOn,$newReading,$remarks,$name);

    if ($user) {
        // user stored successfully
        $response["error"] = FALSE;
        $response["user"]["flat_id"] = $user["Flat_Id"];
        $response["user"]["reading"] = $user["ReadingValue"];
        $response["user"]["remarks"] = $user["Remarks"];
        $response["user"]["takenBy"] = $user["TakenBy"];
        echo json_encode($response);

    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in saving data!";
        echo json_encode($response);
    }

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}
?>