<?php

$db = pg_connect("host=db port=5432 dbname=foo user=foo password=bar");

if (!$db) {
    http_response_code(500);
    die("Database connection failed.");
}

$player_name = $_GET['player_name'];
$dest_x = $_GET['x'];
$dest_y = $_GET['y'];

$query = "UPDATE players SET dest_x = $2, dest_y = $3 WHERE alias = $1";
$result = pg_query_params($db, $query, [$player_name, $dest_x, $dest_y]);

if ($result) {
    echo "{$player_name} is on the move!";
} else {
    http_response_code(500);
    echo "Insert failed: " . pg_last_error($db);
}

?>
