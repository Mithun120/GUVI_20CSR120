<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12606419";
$password = "wCimSR1VNb";
$database = "sql12606419";
$redis = new Redis();
$redis->connect('redis-17424.c15.us-east-1-2.ec2.cloud.redislabs.com', 17424);
$conn=mysqli_connect($servername,$username,$password,$database);
if(mysqli_connect_errno())
{
    echo "falied to connect";
}
// echo "server connected";
// include "./services/Response.php";

// DATA FROM POST
$username = $_POST['username'];
$verifyPassword = $_POST['password'];
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379');

// PREPARING STATEMENT

$loginSql = "SELECT username,password,mongoDbId FROM users WHERE username = ?";
$loginStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($loginStmt, $loginSql)) {
    $response = array(
        'status' => false,
        'message' => 'Statement Failed',
    );
    sendRespose(200, $response);
}

mysqli_stmt_bind_param($loginStmt, "s", $username);

// execute the query
mysqli_stmt_execute($loginStmt);

// bind the results to variables
mysqli_stmt_bind_result($loginStmt, $username, $password, $mongoDbId);

$value = mysqli_stmt_fetch($loginStmt);
if (!$value) {
    $response = array(
        'status' => false,
        'message' => 'Invalid User',
    );
    echo $response;
}
// fetch the results
while ($value) {

    if (password_verify($verifyPassword, $password)) {
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

       die(json_encode($response));
    } else {
        $response = array(
            'status' => false,
            'message' => 'Invalid Password',
        );
       die(json_encode($response));
    }
    break;
}
mysqli_stmt_close($loginStmt);
mysqli_close($conn);

?>
