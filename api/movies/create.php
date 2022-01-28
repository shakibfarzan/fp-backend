<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/model/movie.php';

$database = new Database();
$db = $database->getConnection();

$item = new Movie($db);

$data = json_decode(file_get_contents("php://input"), true);


if ($data != null) {
    $item->name = $data["name"];
    $item->releasedYear = $data["releasedYear"];
    $item->description = $data["description"];
    $item->poster = $data["poster"];
} else {
    $item->name = $_POST["name"];
    $item->releasedYear = $_POST["releasedYear"];
    $item->description = $_POST["description"];

    $posterName = $_FILES["poster"]["name"];
    $tempPath  =  $_FILES['poster']['tmp_name'];
    $imageSize = $_FILES["poster"]["size"];
    $postfix = date_timestamp_get(date_create());

    $uploadPath = '/api/posters/';
    $fileExt = strtolower(pathinfo($posterName, PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png');
    if (in_array($fileExt, $valid_extensions)) {

        if ($imageSize < 10000000) {
            move_uploaded_file($tempPath, $_SERVER['DOCUMENT_ROOT'] . $uploadPath . $postfix . $posterName);
        } else {
            $error = json_encode(array("message" => "Your file is too large, please upload lower than 10 MB file.", "status" => 400));
            echo $error;
        }
    } else {
        $error = json_encode(array("message" => "Only JPG, JPEG, PNG files are allowed", "status" => 400));
        echo $error;
    }
}
if ($item->name == null || $item->releasedYear == null || $item->description == null) {
    $error = json_encode(array("message" => "Properties shouldn't be null!", "status" => 400));
    echo $error;
}
if (!isset($error)) {
    $item->poster =  $postfix . $posterName;
    if ($item->createMovie()) {
        echo 'Movie created successfully.';
    } else {
        echo 'Movie could not be created.';
    }
}
