<?php
require_once("database_settings.php");

class Database {

    protected $host_name;
    protected $user_name;
    protected $password;
    protected $database;
    protected $connection;
    protected $query;
    protected $result;
    protected $insert_id;

    public function __construct($host_name, $user_name, $password, $database) {
        $this->host_name = $host_name;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->database = $database;

        $this->connection = mysqli_connect($this->host_name, $this->user_name, $this->password, $this->database);

        if (mysqli_connect_errno()) {
            echo "<p style='color:red'><b>Database Connection Problem!...</b></p>";
            echo "<p style='color:red'><b>Error No: </b>" . mysqli_connect_errno() . "</p>";
            echo "<p style='color:red'><b>Error Message: </b>" . mysqli_connect_error() . "</p>";
        }
    }

    public function get_insert_id(){
        return $this->insert_id;
    }

    public function execute_query($query) {
        $this->query = $query;
        $this->result = mysqli_query($this->connection, $this->query) or die(mysqli_error($this->connection));
        $this->insert_id = mysqli_insert_id($this->connection);
        return $this->result;
    }

    // Fetch all rows
    public function fetch_all($query) {
        $result = $this->execute_query($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


    // Fetch single row
    public function fetch_one($query) {
        $result = $this->execute_query($query);
        return mysqli_fetch_assoc($result);
    }

    // Insert dynamic data
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_map([$this->connection, 'real_escape_string'], array_values($data)));
        $query = "INSERT INTO $table ($columns) VALUES ('$values')";
        return $this->execute_query($query);
    }

    // Update dynamic data
    public function update($table, $data, $condition) {
        $update = [];
        foreach ($data as $key => $value) {
            $value = mysqli_real_escape_string($this->connection, $value);
            $update[] = "$key = '$value'";
        }
        $update_string = implode(", ", $update);
        $query = "UPDATE $table SET $update_string WHERE $condition";
        return $this->execute_query($query);
    }

    // Delete records
    public function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        return $this->execute_query($query);
    }

    // Count rows from query
    public function count_rows($query) {
        $result = $this->execute_query($query);
        return mysqli_num_rows($result);
    }

    public function __destruct() {
        mysqli_close($this->connection);
    }
}

	// Initialize DB connection
	$db = new Database($host_name, $user_name, $password, $database_name);
?>
