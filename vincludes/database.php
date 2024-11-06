<?php
require_once(LIB_PATH_INC . DS . "config.php");

class MySqli_DB {

    public $con;
    public $query_id;

    function __construct() {
        $this->db_connect();
    }

    /*--------------------------------------------------------------*/
    /* Function to escape special characters in a string for use in an SQL statement
    /*--------------------------------------------------------------*/
    public function escape($str) {
        return $this->con->real_escape_string($str);
    }

    /*--------------------------------------------------------------*/
    /* Function to prepare an SQL statement
    /*--------------------------------------------------------------*/
    public function prepare($sql) {
        $stmt = $this->con->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed: " . mysqli_error($this->con));
            return false;
        }
        return $stmt;
    }

    /*--------------------------------------------------------------*/
    /* Function for Open database connection
    /*--------------------------------------------------------------*/
    public function db_connect() {
        $this->con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$this->con) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }

    /*--------------------------------------------------------------*/
    /* Function for Close database connection
    /*--------------------------------------------------------------*/
    public function db_disconnect() {
        if (isset($this->con)) {
            mysqli_close($this->con);
            unset($this->con);
        }
    }

    /*--------------------------------------------------------------*/
    /* Function for mysqli query
    /*--------------------------------------------------------------*/
    public function query($sql) {
        if (trim($sql) != "") {
            $this->query_id = $this->con->query($sql);
            if (!$this->query_id) {
                // Only for development mode
                die("Error on this Query: <pre>" . htmlspecialchars($sql) . "</pre>");
                // For production mode
                // die("Error on Query");
            }
        }
        return $this->query_id;
    }

    /*--------------------------------------------------------------*/
    /* Function for Query Helper
    /*--------------------------------------------------------------*/
    public function fetch_array($statement) {
        return mysqli_fetch_array($statement);
    }

    public function fetch_object($statement) {
        return mysqli_fetch_object($statement);
    }

    public function fetch_assoc($statement) {
        return mysqli_fetch_assoc($statement);
    }

    public function num_rows($statement) {
        return mysqli_num_rows($statement);
    }

    public function insert_id() {
        return mysqli_insert_id($this->con);
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->con);
    }

    /*--------------------------------------------------------------*/
    /* Function for while loop
    /*--------------------------------------------------------------*/
    public function while_loop($loop) {
        $results = [];
        while ($result = $this->fetch_array($loop)) {
            $results[] = $result;
        }
        return $results;
    }
    public function begin_transaction() {
        $this->con->begin_transaction();
    }

    // Commit a transaction
    public function commit() {
        $this->con->commit();
    }
}

$db = new MySqli_DB();
?>
