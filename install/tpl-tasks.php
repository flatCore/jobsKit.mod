<?php

$database = "jobsKit";
$table_name = "tasks";

$cols = array(
  "task_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "task_hash" => 'VARCHAR',
  "task_author" => 'VARCHAR',
  "task_client" => 'VARCHAR',
  "task_project_id" => 'VARCHAR',
  "task_project_hash" => 'VARCHAR',
  "task_time_recorded"  => 'VARCHAR',
  "task_time_due"  => 'VARCHAR',
  "task_users" => 'VARCHAR',
  "task_title" => 'VARCHAR',
  "task_notes" => 'VARCHAR',
  "task_repeat" => 'VARCHAR',
  "task_priority" => 'VARCHAR',
  "task_hourly_wage" => 'VARCHAR',
  "task_status" => 'VARCHAR'
 );
  
  
  



?>