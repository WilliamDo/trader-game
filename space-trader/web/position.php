<?php

$db = pg_connect("host=db port=5432 dbname=foo user=foo password=bar");

if (!$db) {
    http_response_code(500);
    die("Database connection failed.");
}

$player_name = $_GET['player_name'];

// 2. Execute the SELECT query directly
$result = pg_query_params($db, "SELECT alias, pos_x, pos_y, dest_x, dest_y FROM players WHERE alias = $1", [$player_name]);

if (!$result) {
    http_response_code(500);
    echo "Query failed: " . pg_last_error($db);
    exit;
}

// 2. Check if the query succeeded and returned at least one row
if ($result && pg_num_rows($result) > 0) {
    // 3. Fetch the single row directly into an associative array
    $user = pg_fetch_assoc($result);

    $currX = $user['pos_x'];
    $currY = $user['pos_y'];

    header('Content-Type: application/json');
    echo json_encode(["x" => (int) $currX, "y" => (int) $currY]);

} else {
    http_response_code(404);
    echo "No user found with that ID.";
    exit;
}

?>
