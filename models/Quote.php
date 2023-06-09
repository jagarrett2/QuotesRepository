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


    private function extract_rows($result, $single){
      $count = $result->rowCount();
      $arr = array();
      if($count > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $item = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category
          );
          array_push($arr, $item);
        }
      }
      if($count > 0 and $single){
        return $arr[0];
      }
      return $arr;
    }


    // Get Quotes
    public function read() {
      // Create query
      $query = 'SELECT q.id, q.quote, a.author, c.category
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
      return $this->extract_rows($stmt, false);
    }

    // Get Single Quote
    public function read_single() {
          // Create query
          $query = 'SELECT q.id, q.quote, a.author, c.category
                                    FROM ' . $this->table . ' q
                                    LEFT JOIN
                                      categories c ON q.category_id = c.id
                                    LEFT JOIN
                                      authors a ON q.author_id = a.id
                                    WHERE
                                      q.id = ?
                                    LIMIT 1';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();
          return $this->extract_rows($stmt, true);
    }

    //Get Quotes by Category
    public function read_by_category(){
      $query = 'SELECT q.id, q.quote, a.author, c.category
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
      return $this->extract_rows($stmt, false);
    }

    public function read_by_author(){
      $query = 'SELECT q.id, q.quote, a.author, c.category
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
      return $this->extract_rows($stmt, false);
    }

    //Get Quotes by Author and Category ID
    public function read_by_author_and_category(){
      $query = 'SELECT q.id, q.quote, a.author, c.category
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
      return $this->extract_rows($stmt, false);
    }
    
    // Create Quote
    public function create() {
          // Create query
          $query = 'INSERT INTO quotes (quote, author_id, category_id) VALUES (?, ?, ?)';
          $stmt = $this->conn->prepare($query);      

          $quote = htmlspecialchars(strip_tags($this->quote));
          $author_id = htmlspecialchars(strip_tags($this->author_id));
          $category_id = htmlspecialchars(strip_tags($this->category_id));

          // Execute query
          if($stmt->execute(array($quote, $author_id, $category_id))) {
            $response = new stdClass();
            $response->id = $last_id = $this->conn->lastInsertId();
            $response->quote = $quote;
            $response->author_id = $author_id;
            $response->category_id = $category_id;
            return $response;
          }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);
    }

    // Update Quote
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
            $response = new stdClass();
            $response->id = $this->id;
            $response->quote = $this->quote;
            $response->author_id = $this->author_id;
            $response->category_id = $this->category_id;
            return $response;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);
    }

    // Delete Quote
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
            $response = new stdClass();
            $response->id = $this->id;
            return $response;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }