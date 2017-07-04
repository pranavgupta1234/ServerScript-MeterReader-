<?php

require("../api/config.php");

if (!empty($_POST)) {

    $response = array("error" => FALSE);

    $query = "SELECT * FROM users WHERE name = :name";

    $query_params = array(
        ':name' => $_POST['name']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }

    catch (PDOException $ex) {
        $response["error"] = true;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }

    $validated_info = false;
    $login_ok = false;
    $name = $_POST['name'];

    $row = $stmt -> fetch();

    if (password_verify($_POST['password'], $row['encrypted_password'])) {
        $login_ok = true;
    }

    if ($login_ok == true) {
        $response["error"] = false;
        $response["message"] = "Login successful!";
        $response["user"]["name"] = $row["name"];
        $response["user"]["uid"] = $row["unique_id"];
        $response["user"]["created_at"] = $row["created_at"];
        die(json_encode($response));

    } else {
        $response["error"] = true;
        $response["message"] = "Invalid Credentials!";
        die(json_encode($response));
    }

} else {

    echo 'Android Learning';
}

?>