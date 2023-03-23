<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'OPTIONS') {
      header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
      header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
      exit();
  }

  include_once '../../config/Database.php';
  include_once './httpMethods.php';

  try{
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();
    //Selects method
    $http_methods = new HttpMethods($db);
    if ($method === 'GET'){
      $http_methods->Get();
    } 
    elseif ($method === 'POST'){
      $http_methods->Post();
    } 
    elseif ($method === 'PUT'){
      $http_methods->Put();
    } 
    elseif ($method === 'DELETE'){
      $http_methods->Delete();
    } 
    else{
      include_once '../../helper/error.php';
      error("Http Method not recognized");
    }
  }
  catch(Exception $e){
    echo 'Caught error: ', $e->getMessage(), "\n";
  }