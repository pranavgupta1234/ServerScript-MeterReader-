<?php

/**
 * Created by PhpStorm.
 * User: Pranav Gupta
 * Date: 7/3/2017
 * Time: 12:10 PM
 */


require("../api/config.php");

if (!empty($_POST)) {

    $response = array(
        "error" => FALSE
    );

    $query = "SELECT * FROM Buildings ;";

    try {

        $stmt = $db->prepare($query);
        $result = $stmt->execute();

    }
    catch (PDOException $ex) {

        $response["error"] = TRUE;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($row != null) {
        $response["error"] = FALSE;
        $response["message"] = "Data read successfully!";
        echo json_encode($row);
    } else {
        $response["error"] = TRUE;
        $response["message"] = "Unable to read data";
        die(json_encode($response));
    }

} else {
    echo 'Pranav';
}

?>