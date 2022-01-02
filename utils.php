<?php
function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return str_replace('.', $separator, strval($euros)).' €';
}