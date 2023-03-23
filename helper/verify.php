<?php
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    //helper function to Verify the author entry exists by id
    function VerifyAuthor($db, $id){
      $author = new Author($db);
      $author->id = $id;
      $result = $author->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }

    //helper function to verify the category entry exists by id
    function VerifyCategory($db, $id){
      $category = new Category($db);
      $category->id = $id;
      $result = $category->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }


    //helper function to verify the quote entry exists by id
    function VerifyQuote($db, $id){
      $quote = new Quote($db);
      $quote->id = $id;
      $result = $quote->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }