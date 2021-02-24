<?php

$database = "jobsKit";
$table_name = "timers";

$cols = array(
  "timer_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "timer_hash" => 'VARCHAR',
  "timer_task_hash" => 'VARCHAR',
  "timer_project_id" => 'VARCHAR',
  "timer_project_hash" => 'VARCHAR',
  "timer_start"  => 'VARCHAR',
  "timer_end"  => 'VARCHAR',
  "timer_user" => 'VARCHAR',
  "timer_client" => 'VARCHAR',
  "timer_notes" => 'VARCHAR',
  "timer_status" => 'VARCHAR'
);
  
  
  



?>