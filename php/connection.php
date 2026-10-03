<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'miniqlsinhvien');
    define('DB_CHARSET', 'utf8mb4');

    $servername = DB_HOST;
    $username = DB_USER;
    $password = DB_PASS;
    $dbname = DB_NAME;

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    // @return mysqli

    function getDbConnection(){
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        $conn->set_charset(DB_CHARSET);

        return $conn;
    }

    $conn = getDbConnection();
?>