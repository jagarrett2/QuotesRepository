<?php
  class Author {
    // DB Stuff
    private $conn;
    private $table = 'authors';

    // Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    private function extract_rows($result, $single){
      $count = $result->rowCount();
      $arr = array();
      if($count > 0){

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $item = array(
            'id' => $id,
            'author' => $author
          );
          array_push($arr, $item);
        }
      }
      if($count > 0 and $single){
        return $arr[0];
      }
      return $arr;
    }

    // Get categories
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        author
      FROM
        ' . $this->table . '
      ORDER BY
        id DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      return $this->extract_rows($stmt, false);
    }

    // Get Single author
  public function read_single(){
    // Create query
    $query = 'SELECT
          id,
          author
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      return $this->extract_rows($stmt, true);
  }

  public function create() {
    // Create query
    $query = 'INSERT INTO authors (author) VALUES (?)';
    $stmt = $this->conn->prepare($query);      

    $author = htmlspecialchars(strip_tags($this->author));

        // Execute query
      if($stmt->execute(array($author))) {
        $response = new stdClass();
        $response->id = $last_id = $this->conn->lastInsertId();
        $response->author = $author;
        return $response;
      }
  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);
  }

  // Update author
  public function update() {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
      author = :author
      WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->author = htmlspecialchars(strip_tags($this->author));
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt-> bindParam(':author', $this->author);
  $stmt-> bindParam(':id', $this->id);

  // Execute query
  if($stmt->execute()) {
    $response = new stdClass();
    $response->id = $this->id;
    $response->author = $this->author;
    return $response;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
  }

  // Delete author
  public function delete() {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt-> bindParam(':id', $this->id);

    // Execute query
    if($stmt->execute()) {
      $response = new stdClass();
      $response->id = $this->id;
      return $response;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
    }
  }
