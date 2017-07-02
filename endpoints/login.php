<?php

require_once '../api/db_api.php';


$db = new DB_API();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) and isset($_POST['password'])) {

    // receiving the post params
    $name = $_POST['name'];
    $password = $_POST['password'];

    // get the user by email and password
    $user = $db -> getUserByUsernameAndPassword($name, $password);

    if ($user != false) {
        // user is found
        $response["error"] = FALSE;
        $response["name"] = $user["Name"];
        $response["password"] = $user["Password"];
        echo json_encode($response);

    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>