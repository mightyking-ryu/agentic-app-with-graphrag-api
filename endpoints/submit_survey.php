<?php

include('../config/database.php');
include('../utils/generate_uuid.php');

function submit_survey($conn) {

    $user_id = generate_uuid();
    $result = $_POST['result']; //JSON

    $query = "INSERT INTO survey (user_id, result) VALUES (?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("bs", $user_id, $result);
    
    if ($stmt->execute()) {
        $response = array(
            "status" => "success",
            "message" => "Survey submitted successfully",
            "user_id" => $user_id
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "message" => $stmt->error
        );
        echo json_encode($response);
    }
}

submit_survey($conn);
$conn->close();

?>