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
    echo "Query failed: " . pg_last_error($db);
    exit;
}

// 2. Check if the query succeeded and returned at least one row
if ($result && pg_num_rows($result) > 0) {
    // 3. Fetch the single row directly into an associative array
    $user = pg_fetch_assoc($result);

    // Use your data
    echo "Welcome back, {$user['alias']}! Your current position is {$user['pos_x']}, {$user['pos_y']}.";

    $currX = $user['pos_x'];
    $currY = $user['pos_y'];

    $destX = $user['dest_x'];
    $destY = $user['dest_y'];

    $deltaX = $destX - $currX;
    $deltaY = $destY - $currY;

    $distance = sqrt(($deltaX * $deltaX) + ($deltaY * $deltaY));

    if ($distance > 0) {
        $speed_factor = 5;

        $stepX = ($deltaX / $distance) * $speed_factor;
        $stepY = ($deltaY / $distance) * $speed_factor;

        $nextX = round($currX + $stepX);
        $nextY = round($currY + $stepY);

        echo "Your next position is {$nextX}, {$nextY}.";

        $query = "UPDATE players SET pos_x = $2, pos_y = $3 WHERE alias = $1";
        $result = pg_query_params($db, $query, [$player_name, $nextX, $nextY]);

        if ($result) {
            echo "{$player_name} is on the move!";
        } else {
            http_response_code(500);
            echo "Insert failed: " . pg_last_error($db);
        }

    }

} else {
    echo "No user found with that ID.";
}

?>
