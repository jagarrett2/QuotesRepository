<?php
    include_once '../../models/quote.php';
    include_once '../../models/author.php';
    include_once '../../models/category.php';
    function VerifyAuthor($db, $id){
      $author = new Author($db);
      $author->id = $id;
      $result = $author->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }

    function VerifyCategory($db, $id){
      $category = new Category($db);
      $category->id = $id;
      $result = $category->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }

    function VerifyQuote($db, $id){
      $quote = new Quote($db);
      $quote->id = $id;
      $result = $quote->read_single();
      if(empty($result)){
        return false;
      }
      return true;
    }