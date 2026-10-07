<?php
    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
    if (!defined('DB_USER')) define('DB_USER', 'root');
    if (!defined('DB_PASS')) define('DB_PASS', '');
    if (!defined('DB_NAME')) define('DB_NAME', 'miniqlsinhvien');
    if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

    $servername = DB_HOST;
    $username = DB_USER;
    $password = DB_PASS;
    $dbname = DB_NAME;

    if (!function_exists('getDbConnection')) {
        function getDbConnection(){
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if($conn->connect_error){
                die("Connection failed: " . $conn->connect_error);
            }

            $conn->set_charset(DB_CHARSET);

            return $conn;
        }
    }

    if (!isset($conn) || !($conn instanceof mysqli)) {
        $conn = getDbConnection();
    }
?>