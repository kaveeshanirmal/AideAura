<?php

Trait Model
{
    use Database;

    // Set the table name dynamically
    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    // Select all rows from the table
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        error_log("Executing query: " . $query);
        return $this->get_all($query);
    }

    // Select the first row with a specific ID
    public function find($id, $id_column = 'id')
    {
        $query = "SELECT * FROM {$this->table} WHERE {$id_column} = :id LIMIT 1";
        return $this->get_row($query, ['id' => $id]);
    }

    // Insert a new row into the table
    public function insert($data)
    {
        $keys = array_keys($data);
        $query = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES (:" . implode(',:', $keys) . ")";
        return $this->query($query, $data);
    }

    // Insert a new row into the table and return the ID
    public function insertAndGetId($data)
    {
        $keys = array_keys($data);
        $query = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES (:" . implode(',:', $keys) . ")";

        // Execute the query
        $result = $this->query($query, $data);

        // Return the last inserted ID using the $pdo connection
        return $result ? $this->getLastInsertId() : false;
    }

    // Update a row in the table
    public function update($id, $data, $id_column = 'id')
    {
        $query = "UPDATE {$this->table} SET ";

        // Dynamically build the query based on the data array
        foreach ($data as $key => $value) {
            $query .= "$key = :$key, ";
        }

        // Remove the trailing comma and space
        $query = rtrim($query, ", ");

        $query .= " WHERE {$id_column} = :id";
        $data['id'] = $id; // Add the id to the data array

        return $this->query($query, $data);
    }

    // Delete a row from the table
    public function delete($id, $id_column = 'id')
    {
        $query = "DELETE FROM {$this->table} WHERE {$id_column} = :userID";
        return $this->query($query, ['userID' => $id]);
    }
}
