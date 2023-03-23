<?php

include_once '../../models/Author.php';
include_once '../../helper/error.php';
include_once('../../helper/verify.php');

Class HttpMethods{
  private $db;
  public function __construct($db) {
    $this->db = $db;
  }
    //Handles the GET Quotes and Quotes/?id paths
    public function Get(){
        $quote = new Author($this->db);
        $id = isset($_GET['id']) ? $_GET['id'] : null;
      
        switch (true){
          case isset($id):
              $quote->id = $id;
              $result = $quote->read_single();
              break;
      
          default:
              $result = $quote->read();
              break;
        }
        if($result){
          echo json_encode($result);
        }
        else{
          error("author_id Not Found");
        }
    }

    public function Post(){
      $author = new Author($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->author)){
        error("Missing Required Parameters");
        return;
      } 

      $author->author = $data->author;
    
      // Create quote
      $response = $author->create();
      if($response){
        echo json_encode($response);
      }
      else{
        error("Unable to Create Author");
      }
    }
    
    public function Put(){
      $author = new Author($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->id) or !isset($data->author)){
        error("Missing Required Parameters");
        return;
      } 

      $author->id = $data->id;
      $author->author = $data->author;

      if(VerifyAuthor($this->db, $author->id) == false){
        error("No Author Found");
        return;
      }
    
      // Create quote
      $response = $author->update();
      if($response){
        echo json_encode($response);
      }
      else{
        error("Unable to Create Author");
      }
    }

    public function Delete(){
      $author = new Author($this->db);

      $data = json_decode(urldecode(file_get_contents("php://input")));

      if($data == null or !isset($data->id)){
        error("Missing Required Parameters");
        return;
      } 

      $author->id = $data->id;

      if(VerifyQuote($this->db, $author->id) == false){
        error("No Authors Found");
        return;
      }

      $response = $author->delete();
      if($response){
        echo json_encode($response);
      }
      else{
        error("Unable to Create Author");
      }
    }
}