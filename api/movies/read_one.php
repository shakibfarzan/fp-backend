<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/model/movie.php';

$database = new Database();
$db = $database->getConnection();

$item = new Movie($db);

$item->id = isset($_GET['id']) ? $_GET['id'] : die();

$item->getSingleMovie();

if ($item->name != null) {
    // create array
    $moviesArr = array(
        "id" =>  $item->id,
        "name" => $item->name,
        "releasedYear" => $item->releasedYear,
        "description" => $item->description,
        "poster" => $item->poster
    );

    http_response_code(200);
    echo json_encode($moviesArr);
} else {
    http_response_code(404);
    echo json_encode("Movie not found.");
}
