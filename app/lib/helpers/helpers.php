<?php

function prntl($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function dnd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

function sanitize($dirty) {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

function currentUser() {
    return Users::currentLoggedInUser();
}

function posted_values() {
    $cleanAr = [];
    foreach ($_POST as $key => $value) {
        $cleanAr[$key] = sanitize($value);
    }
    return $cleanAr;
}

function getJson($link) {
    $file = file_get_contents(ROOT. DS . 'app' . DS . 'json' . DS . $link . '.json');
    if ($json = json_decode($file, true)) {
        return $json;
    }
    return false;
}