<?php
require_once("utils.php");

$db = get_db();
$stmt = $db->prepare("SELECT `image` FROM product WHERE id = :id");
$stmt->bindParam(":id", $_GET["id"]);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($result) != 1) {
    header("Location: https://picsum.photos/1000/1000");
} else if($result[0]["image"] === null) {
    header("Location: https://picsum.photos/1000/1000");
} else {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($result[0]["image"]);
    header("Content-Type: $mime");
    header("Cache-Control: public, max-age=31536000");
    header("Content-Length: ".strlen($result[0]["image"]));
    echo $result[0]["image"];
}