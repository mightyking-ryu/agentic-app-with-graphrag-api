<?php

include('../config/database.php');
include('../utils/generate_uuid.php');

function ask_question($conn) {

    if (!isset($_POST['user_id']) || !isset($_POST['question'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters"
        ]);
        return;
    }

    $user_id = $_POST['user_id'];
    $question_id = generate_uuid();
    $question = $_POST['question'];

    $query = "INSERT INTO question_queue (user_id, question_id, question) VALUES (?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $user_id, $question_id, $question);
    
    if ($stmt->execute()) {
        $response = array(
            "status" => "success",
            "message" => "Your question has been received",
            "question_id" => $question_id
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

ask_question($conn);
$conn->close();

?>