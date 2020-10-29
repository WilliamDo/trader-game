<?php

/* PostgreSQL Function Library */

/*
* Connect to Group 27 database
* Throws an exception if connection error occurs
*/

$dbconn = pg_pconnect("host=localhost port=5432 dbname=g0727127_u user=g0727127_u password=6IJWzyfJiN"); //db.doc.ic.ac.uk	146.169.

if (!$dbconn) {
	throw new Exception("Unable to connect to the database");
}

/*
* Executes the query
*/
function db_exec_query($query) {
	$result = pg_query($query);

	if ($result) {
		return $result;
	}
	else {
		throw new Exception("Query failed");
	}
}

/* db_gen family generates various queries */

/*
* @param tables, conditions
* tables separated by commas
* conditions separated by commas
* e.g. $tables = "table1, table2"
*      $conditons = "field1=value1, field2=value2"
*/
function db_gen_select_cond($tables, $conditions) {
	return <<<END
SELECT * FROM $tables WHERE $conditions
END;
}

function db_gen_select_all($tables) {
	return <<<END
SELECT * FROM $tables
END;
}

function db_gen_insert($table, $fields, $values) {
	return <<<END
INSERT INTO $table ($fields) VALUES ($values)
END;
}

function db_gen_update($table, $field, $value, $cond) {
	return <<<END
UPDATE $table SET $field = $value WHERE $cond
END;
}

?>
