<?php

include('../config/database.php');

function fetch_response($conn) {

    if (!isset($_POST['user_id']) || !isset($_POST['question_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters"
        ]);
        $conn->close();
        return;
    }
    
    $user_id = $_POST['user_id'];
    $question_id = $_POST['question_id'];

    $query = "SELECT response FROM response_queue WHERE question_id = ? AND user_id = ? ORDER BY created_at ASC LIMIT 1";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "SQL prepare error: " . $conn->error
        ]);
        return;
    }
    
    $stmt->bind_param("ss", $question_id, $user_id);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response = array(
            "status" => "success",
            "message" => "Response generated",
            "question_id" => $row['response']
        );
        echo json_encode($response);
        return;
    }

    $query = "SELECT * FROM question_queue WHERE question_id = ? AND user_id = ? ORDER BY created_at ASC LIMIT 1";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "SQL prepare error: " . $conn->error
        ]);
        return;
    }
    
    $stmt->bind_param("ss", $question_id, $user_id);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response = array(
            "status" => "processing",
            "message" => "Response in progress"
        );
        echo json_encode($response);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No query found"
        ]);
    }
}

fetch_response($conn);
$conn->close();

?>
