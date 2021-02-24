<?php
	
	/**
	 * cat_type - global, stock, jobs	
	*/

$database = "jobsKit";
$table_name = "categories";

$cols = array(
  "cat_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "cat_parent_id" => 'INTEGER',
  "cat_sort" => 'VARCHAR',
  "cat_time" => 'VARCHAR',
  "cat_type"  => 'VARCHAR',
  "cat_title"  => 'VARCHAR',
  "cat_title_safe"  => 'VARCHAR',
  "cat_description"  => 'VARCHAR',
  "cat_images"  => 'VARCHAR'
);
  
  
  



?>