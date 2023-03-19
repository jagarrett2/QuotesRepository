<?php

include_once '../../models/category.php';
include_once '../../helper/error.php';

Class HttpMethods{
  private $db;
  public function __construct($db) {
    $this->db = $db;
  }
    public function Get(){
        $category = new Category($this->db);
        $id = isset($_GET['id']) ? $_GET['id'] : null;
      
        switch (true){
          case isset($id):
              $category->id = $id;
              $result = $category->read_single();
              break;
      
          default:
              $result = $category->read();
              break;
        }
        if($result){
          echo json_encode($result);
        }
        else{
          error("No Categories Found");
        }
    }

    public function Post(){
      $category = new Category($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->category)){
        error("Missing Required Parameters");
        return;
      } 

      $category->category = $data->category;
    
      // Create quote
      if($category->create()) {
        echo json_encode(
          array('message' => 'Category Created')
        );
      } else {
        echo json_encode(
          array('message' => 'Category Not Created')
        );
      }
    }
    
    public function Put(){
      $category = new Category($this->db);
    
      $data = json_decode(urldecode(file_get_contents("php://input")));
      
      if($data == null or !isset($data->id) or !isset($data->category)){
        error("Missing Required Parameters");
        return;
      } 

      $category->id = $data->id;
      $category->category = $data->category;

      if(VerifyCategory($this->db, $category->id) == false){
        error("No Category Found");
        return;
      }
    
      // Create quote
      if($category->update()) {
        echo json_encode(
          array('message' => 'Category Updated')
        );
      } else {
        echo json_encode(
          array('message' => 'Category Not Upated')
        );
      }
    }

    public function Delete(){
      $category = new Category($this->db);

      $data = json_decode(urldecode(file_get_contents("php://input")));

      if($data == null or !isset($data->id)){
        error("Missing Required Parameters");
        return;
      } 

      $category->id = $data->id;

      if(VerifyCategory($this->db, $category->id) == false){
        error("No Category Found");
        return;
      }

      if($category->delete()) {
        echo json_encode(
          array('message' => 'Category Deleted')
        );
      } else {
        echo json_encode(
          array('message' => 'Category Not Deleted')
        );
      }
    }
}