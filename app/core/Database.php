<?php

//Using traits instead of classes allows to reuse code in multiple classes
//for example, this way we don't extend Database, we simply say, use Database. see "Model.php";
trait Database
{
    private $pdo;

    // Establish the connection only once
    private function connect()
    {
        if (!$this->pdo) { // Check if the PDO connection already exists
            try {
                $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
                $this->pdo = new PDO($string, DBUSER, DBPASS);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
        }

        return $this->pdo;
    }

    // Execute a query (no results expected)
    public function query($query, $data = [])
    {
        
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);

        return $check; // Return true if execution was successful, false otherwise
    }

    // Get a single row from the database
    public function get_row($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);
        
        $check = $stm->execute($data);

        if ($check) {
            return $stm->fetch(PDO::FETCH_OBJ); // Fetch a single result as an object
        }

        return false; // Return false if the query failed
    }

    // Get multiple rows from the database
    public function get_all($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);

        if ($check) {
            return $stm->fetchAll(PDO::FETCH_OBJ); // Fetch all results as an array of objects
        }

        return false; // Return false if the query failed
    }

        // In the Database trait, add this method to fetch the last insert ID.
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId(); // Returns the last inserted ID using the current PDO connection
    }

}


