<?php

include_once('../../models/Quote.php');
include_once('../../helper/error.php');
include_once('../../helper/verify.php');

Class HttpMethods{
  private $db;
  public function __construct($db) {
    $this->db = $db;
  }
    public function Get(){
        // Instantiate blog quote object
        $quote = new Quote($this->db);
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        switch (true){
            case isset($id):
                $quote->id = $id;
                $result = $quote->read_single();
                break;
    
            case isset($author_id) && isset($category_id):
                $quote->author_id = $author_id;
                $quote->category_id = $category_id;
                $result = $quote->read_by_author_and_category();
                break;
    
            case isset($author_id):
                $quote->author_id = $author_id;
                $result = $quote->read_by_author();
                break;
    
            case isset($category_id):
                $quote->category_id = $category_id;
                $result = $quote->read_by_category();
                break;
    
            default:
                $result = $quote->read();
                break;
        }
        if($result){
            echo json_encode($result);
        }
        elseif (empty($result)){
            error("No Quotes Found");
        }
        else{
          error("Error");
        }
    }

    public function Post(){
      $quote = new Quote($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->quote) or !isset($data->author_id) or !isset($data->category_id)){
        error("Missing Required Parameters");
        return;
      } 

      $quote->quote = $data->quote;
      $quote->author_id = $data->author_id;
      $quote->category_id = $data->category_id;

      if(VerifyAuthor($this->db, $quote->author_id) == false){
        error("author_id Not Found");
        return;
      }
      if(VerifyCategory($this->db, $quote->category_id) == false){
        error("category_id Not Found");
        return;
      }
    
      // Create quote
      if($quote->create()) {
        echo json_encode(
          array('message' => 'Quote Created')
        );
      } else {
        echo json_encode(
          array('message' => 'Quote Not Created')
        );
      }
    }
    
    public function Put(){
      $quote = new Quote($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->id) or !isset($data->quote) or !isset($data->author_id) or !isset($data->category_id)){
        error("Missing Required Parameters");
        return;
      } 

      $quote->id = $data->id;
      $quote->quote = $data->quote;
      $quote->author_id = $data->author_id;
      $quote->category_id = $data->category_id;

      if(VerifyQuote($this->db, $quote->id) == false){
        error("No Quotes Found");
        return;
      }

      if(VerifyAuthor($this->db, $quote->author_id) == false){
        error("author_id Not Found");
        return;
      }
      if(VerifyCategory($this->db, $quote->category_id) == false){
        error("category_id Not Found");
        return;
      }
    
      // Create quote
      if($quote->update()) {
        echo json_encode(
          array('message' => 'Quote Updated')
        );
      } else {
        echo json_encode(
          array('message' => 'Quote Not Upated')
        );
      }
    }

    public function Delete(){
      $quote = new Quote($this->db);

      $data = json_decode(urldecode(file_get_contents("php://input")));

      if($data == null or !isset($data->id)){
        error("Missing Required Parameters");
        return;
      } 

      $quote->id = $data->id;

      if(VerifyQuote($this->db, $quote->id) == false){
        error("No Quotes Found");
        return;
      }

      if($quote->delete()) {
        echo json_encode(
          array('message' => 'Quote Deleted')
        );
      } else {
        echo json_encode(
          array('message' => 'Quote Not Deleted')
        );
      }
    }
}