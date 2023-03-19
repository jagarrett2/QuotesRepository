<?php

include_once '../../models/author.php';
include_once '../../helper/error.php';
include_once '../../helper/verify.php';

Class HttpMethods{
  private $db;
  public function __construct($db) {
    $this->db = $db;
  }
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
          error("No Authors Found");
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
      if($author->create()) {
        echo json_encode(
          array('message' => 'Author Created')
        );
      } else {
        echo json_encode(
          array('message' => 'Author Not Created')
        );
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
      if($author->update()) {
        echo json_encode(
          array('message' => 'Author Updated')
        );
      } else {
        echo json_encode(
          array('message' => 'Author Not Upated')
        );
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

      if($author->delete()) {
        echo json_encode(
          array('message' => 'Author Deleted')
        );
      } else {
        echo json_encode(
          array('message' => 'Author Not Deleted')
        );
      }
    }
}