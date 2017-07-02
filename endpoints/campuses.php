<?php

require("../api/config.php");

if (!empty($_POST)) {

    $response = array(
        "error" => FALSE
    );

    $query = " SELECT * FROM Campuses ";

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
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

        $response["error"] = FALSE;
        $response["message"] = "Data read successfully!";

        echo json_encode($response);
    }

} else {
    echo 'Android Learning';
}

?>