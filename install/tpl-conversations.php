<?php

/**
 * post_unit 		- chat or comments
 * post_parent 	- another post_hash if it's an answer
 *							- null if it's the first post
 *
 */

$database = "jobsKit";
$table_name = "conversations";

$cols = array(
  "post_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "post_hash"  => 'VARCHAR',
  "post_unit"  => 'VARCHAR',
  "post_parent"  => 'VARCHAR',
  "post_time_recorded"  => 'VARCHAR',
  "post_user" => 'VARCHAR',
  "post_content" => 'VARCHAR'
  );

?>