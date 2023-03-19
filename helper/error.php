<?php

function error($message){
    $err = new stdClass();
    $err->message = $message;
    echo json_encode($err);
}