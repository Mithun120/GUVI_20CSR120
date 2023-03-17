<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12606419";
$password = "wCimSR1VNb";
$database = "sql12606419";
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$conn=mysqli_connect($servername,$username,$password,$database);
if(mysqli_connect_errno())
{
    echo "falied to connect";
}
echo "server connected";

// PREPARING STATEMENT
$insertSql = "INSERT INTO users (username, password, mongoDbId) VALUES (?, ?, ?)";
$insertStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($insertStmt, $insertSql)) {
  die("SQL error: " . mysqli_stmt_error($insertStmt));
}

// DATA FROM POST REQUEST
$username = $_POST['username'];
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$mongoDbId =NULL;

// $mongoDB_id = "123456789012345678901234";
mysqli_stmt_bind_param($insertStmt, "sss", $username, $hashedPassword, $mongoDbId);

// // Execute the statement
if (!mysqli_stmt_execute($insertStmt)) {
  die("Execution failed: " . mysqli_stmt_error($insertStmt));
}
else{
  $session_id = uniqid();
        $redis->set("session:$session_id", $username);
        $redis->expire("session:$session_id", 1000 * 60);
        $response = array(
            'status' => true,
            'message' => 'Success',
            'session_id' => $session_id,
            'data' => array(
                'username' => $username, 'password' => $password, 'mongoDbId' => $mongoDbId,
            ),
        );
    
}
// // Close the statement and connection
mysqli_stmt_close($insertStmt);
mysqli_close($conn);


?>