<?php

// Database class makes a connection to the database using the current
// config variables 
class Database {

  // Database connection config
  const DB_HOST = 'localhost';
  const DB_USERNAME = 'root';
  const DB_PASSWORD = 'y11groupproject';
  const DB_NAME = '2014_comp10120_y11';

  // Current database connection to be passed to areas where DB access needed
  private $connection = null;

  // Create a database connection returning success as a boolean
  public function __construct() {
    // If there is not already a connection
    if (!$this->connection) {
      // Start a new mysqli connection and store it in instance vars
      $this->connection = new mysqli(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
      // Return success of connection as boolean
      return is_null($this->connection->connect_error);
    }
  }

  // Get the current database connection
  public function getConnection() {
    return $this->connection;
  }

  // Destructor method to close database connection
  public function __destruct() {
    if($this->connection) {
      $this->connection->close();
    }
  }

  // Add any custom complex query types needed for specific classes here

}

?>
