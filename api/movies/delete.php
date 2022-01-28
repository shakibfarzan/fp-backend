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

$data = json_decode(file_get_contents("php://input"));

if ($data != null) {
    $item->id = $data->id;
} else {
    $item->id = $_POST['id'];
}

$item->getPosterById();
$poster = $item->poster;

$uploadPath = '/api/posters/';
if ($item->deleteMovie()) {
    unlink($_SERVER['DOCUMENT_ROOT'] . $uploadPath . $poster);
    echo "Movie deleted.";
} else {
    echo "Movie could not be deleted";
}
