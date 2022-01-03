<?php
function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return number_format($euros, 2, $separator, '').' €';
}

function get_db() {
    $database = new PDO("sqlite:db.sqlite");
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $database;
}
