<?php

require_once '../api/db_api.php';

$db = new DB_API();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name'])&& isset($_POST['password'])) {

    // receiving the post params
    $name = $_POST['name'];
    $password = $_POST['password'];

    // check if user is already existed with the same email
    if ($db -> isUserExisted($name)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $name;
        echo json_encode($response);

    } else {
        // create a new user
        $user = $db->storeUser($name,$password);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["user"]["name"] = $user["Name"];
            $response["user"]["email"] = $user["Password"];
            $response["user"]["designation"] = $user["Designation"];
            $response["user"]["role"] = $user["Role"];
            echo json_encode($response);

        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>