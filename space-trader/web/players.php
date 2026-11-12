<?php
// Connection string parameters
$conn_string = "host=db port=5432 dbname=foo user=foo password=bar";
$db = pg_connect($conn_string);

if (!$db) {
    http_response_code(500);
    die("Database connection failed.");
}

// 2. Execute the SELECT query directly
$result = pg_query($db, "SELECT alias, pos_x, pos_y, dest_x, dest_y FROM players");

if (!$result) {
    echo "Query failed: " . pg_last_error($db);
    exit;
}

// 3. Loop through the records row by row and print them
while ($row = pg_fetch_assoc($result)) {
    echo "Alias: {$row['alias']} | X: {$row['pos_x']} | Y: {$row['pos_y']} | Destination: ({$row['dest_x']}, {$row['dest_y']})<br>\n";
}