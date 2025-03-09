<?php

$request_method = $_SERVER["REQUEST_METHOD"];
$request_uri = $_SERVER["REQUEST_URI"];

switch($request_uri) {
    case '/submit_survey':
        if($request_method == 'POST') {
            include('endpoints/submit_survey.php');
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case '/ask_question':
        if($request_method == 'POST') {
            include('endpoints/ask_question.php');
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case '/fetch_response':
        if($request_method == 'POST') {
            include('endpoints/fetch_response.php');
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        break;
}

?>