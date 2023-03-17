<?php

$mongodb_link= "mongodb+srv://mithun:mithun@cluster0.gyeyuvq.mongodb.net/?retryWrites=true&w=majority";
$mongodb_database = "guvi";
$mongodb_collection = "guvi";
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$mongo = new MongoDB\Driver\Manager($mongodb_link);


$redisId = $_GET['redisID'];

$username = $redis->get("session:$redisId");

$filter = ['username' => $username];

$options = [];

$query = new MongoDB\Driver\Query($filter, $options);

$cursor = $mongo->executeQuery("$mongodb_database.$mongodb_collection", $query);

$document = current($cursor->toArray());
$response = array(
    'status' => true,
    'newUser' => false,
    'userDetails' => $document,
    'username' => $username,

);

if ($document) {
sendRespose(200, $response);
}else {
    sendRespose(200, array(
        'status' => false,
        'message' => 'No documentation',
    ));
}

// if ($data->mongoDbId) {

//     $id = new MongoDB\BSON\ObjectID($data->mongoDbId); // ID object
//     $filter = ['_id' => $id];
//     $options = ['limit' => 1];

//     $query = new MongoDB\Driver\Query($filter, $options);

//     $cursor = $mongo->executeQuery("$mongodb_database.$mongodb_collection", $query);

//     $document = current($cursor->toArray());
//     $response = array(
//         'status' => true,
//         'newUser' => false,
//         'userDetails' => $document,
//         'username' => $data->username,

//     );
//     if ($document) {
//         sendRespose(200, $response);
//     } else {

//         sendRespose(200, array(
//             'status' => false,
//             'message' => 'No documentation',
//         ));
//     }

//     sendRespose(200, $data);
// } else {
//     $response = array(
//         'status' => true,
//         'newUser' => true,
//         'username' => $data->username,
//     );
//     sendRespose(200, $response);
// }
// // echo $data->username;
?>