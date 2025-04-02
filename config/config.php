<?php
$dbhost = 'localhost';
$dbname = 'lms';
$dbuser = 'root';
$dbpass = '';
try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $exception ) {
    echo "Connection error :" . $exception->getMessage();
}
define( "BASE_URL", "http://localhost/lms/");
define("ADMIN_URL", BASE_URL."admin/");

// stmp 
define( "SMTP_HOST", "sandbox.smtp.mailtrap.io");
define( "SMTP_PORT", "587");
define( "SMTP_USERNAME", "94bd28e792ac1b");
define( "SMTP_PASSWORD", "8bbebf9a3a8da1");
define( "SMTP_FORM", "lms@info.com");
define( "SMTP_ENCRYPTION", "tls");