<?php

require("../api/config.php");

if (!empty($_POST)) {

    $response = array(
        "error" => FALSE
    );

    $query = " SELECT 1 FROM users WHERE name = :name";

    //now lets update what :user should be
    $query_params = array(
        ':name' => $_POST['name']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {

        $response["error"] = TRUE;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }

    $row = $stmt->fetch();
    if ($row) {

        $response["error"] = TRUE;
        $response["message"] = "I'm sorry, this email is already in use";
        die(json_encode($response));
    } else {
        $query = "INSERT INTO users ( unique_id, name,encrypted_password, created_at ) VALUES ( :uuid, :name, :encrypted_password, NOW() ) ";

        $query_params = array(
            ':uuid' => uniqid('', true),
            ':name' => $_POST['name'],
            ':encrypted_password' => password_hash($_POST['password'], PASSWORD_DEFAULT) // encrypted password
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch (PDOException $ex) {
            $response["error"] = TRUE;
            $response["message"] = "Database Error2. Please Try Again!";
            die(json_encode($response));
        }

        $response["error"] = FALSE;
        $response["message"] = "Register successful!";
        echo json_encode($response);
    }

} else {
    echo 'Android Learning';
}

?>