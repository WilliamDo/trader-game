<?php
// 1. Set the header so the client knows it's receiving JSON
header('Content-Type: application/json');

// 2. Create a PHP array with your data
$response = [
    'status' => 'success',
    'message' => 'Data retrieved successfully',
    'user' => [
        'id' => 42,
        'username' => 'johndoe',
        'active' => true
    ],
    'items' => ['apple', 'banana', 'mango']
];

// 3. Convert the array to JSON and output it
echo json_encode($response);
?>
broken