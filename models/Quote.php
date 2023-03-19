<?php 
  class Quote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // Post Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }


    private function extract_rows($result){
      $count = $result->rowCount();
      $arr = array();
      if($count > 0){

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $item = array(
            'id' => $id,
            'quote' => $quote,
            'author_name' => $author_name,
            'category_name' => $category_name
          );
          array_push($arr, $item);
        }
      }
      return $arr;
    }


    // Get Posts
    public function read() {
      // Create query
      $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name
                                FROM ' . $this->table . ' q
                                LEFT JOIN
                                  categories c ON q.category_id = c.id
                                LEFT JOIN
                                  authors a ON q.author_id = a.id
                                ORDER BY
                                  q.id DESC';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $this->extract_rows($stmt);
    }

    // Get Single Post
    public function read_single() {
          // Create query
          $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name
                                    FROM ' . $this->table . ' q
                                    LEFT JOIN
                                      categories c ON q.category_id = c.id
                                    LEFT JOIN
                                      authors a ON q.author_id = a.id
                                    WHERE
                                      q.id = ?
                                    LIMIT 0,1';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();
          return $this->extract_rows($stmt);
    }

    public function read_by_category(){
      $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name
                                FROM ' . $this->table . ' q
                                LEFT JOIN
                                  categories c ON q.category_id = c.id
                                LEFT JOIN
                                  authors a ON q.author_id = a.id
                                WHERE category_id = ?
                                ORDER BY
                                  q.id DESC';

      $stmt = $this->conn->prepare($query);      
      // Bind ID
      $stmt->bindParam(1, $this->category_id);

      $stmt->execute();
      return $this->extract_rows($stmt);
    }

    public function read_by_author(){
      $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name
                                FROM ' . $this->table . ' q
                                LEFT JOIN
                                  categories c ON q.category_id = c.id
                                LEFT JOIN
                                  authors a ON q.author_id = a.id
                                WHERE author_id = ?
                                ORDER BY
                                  q.id DESC';

      $stmt = $this->conn->prepare($query);      
      // Bind ID
      $stmt->bindParam(1, $this->author_id);

      $stmt->execute();
      return $this->extract_rows($stmt);
    }

    public function read_by_author_and_category(){
      $query = 'SELECT q.id, q.quote, a.author as author_name, c.category as category_name
      FROM ' . $this->table . ' q
      LEFT JOIN
        categories c ON q.category_id = c.id
      LEFT JOIN
        authors a ON q.author_id = a.id
      WHERE author_id = ?
      AND category_id = ?
      ORDER BY
        q.id DESC';

      $stmt = $this->conn->prepare($query);      
      // Bind ID
      $stmt->bindParam(1, $this->author_id);
      $stmt->bindParam(2, $this->category_id);

      $stmt->execute();
      return $this->extract_rows($stmt);
    }
    
    // Create Post
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Post
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . '
                                SET quote = :quote, author_id = :author_id, category_id = :category_id
                                WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    // Delete Post
    public function delete() {
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }