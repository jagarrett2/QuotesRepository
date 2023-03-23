<?php

//Just outputs an anonymous object with a key of "Message" containing the value of the error message
function error($message){
    $err = new stdClass();
    $err->message = $message;
    echo json_encode($err);
}