<?php

$db = pg_connect("host=db port=5432 dbname=foo user=foo password=bar");

if (!$db) {
    http_response_code(500);
    die("Database connection failed.");
}

function generateAlphaNum(int $length = 8): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charLength - 1)];
    }
    
    return $randomString;
}

$unique_code = generateAlphaNum();
$player_name = "player-{$unique_code}";

$query = "INSERT INTO players (alias, pos_x, pos_y) VALUES ($1, $2, $3)";
$result = pg_query_params($db, $query, [$player_name, 0, 0]);

if ($result) {
    echo "Successfully inserted new record!";
} else {
    http_response_code(500);
    echo "Insert failed: " . pg_last_error($db);
}

?>
