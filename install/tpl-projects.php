<?php

$database = "jobsKit";
$table_name = "projects";

$cols = array(
  "project_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "project_author"  => 'VARCHAR',
  "project_hash"  => 'VARCHAR',
  "project_nbr"  => 'INTEGER',
  "project_title"  => 'VARCHAR',
  "project_time_recorded"  => 'VARCHAR',
  "project_time_due"  => 'VARCHAR',
  "project_users" => 'VARCHAR',
  "project_text" => 'VARCHAR',
  "project_client" => 'VARCHAR',
  "project_images" => 'VARCHAR',
  "project_attachments" => 'VARCHAR',
  "project_status" => 'VARCHAR',
  "project_steps" => 'VARCHAR',
  "project_budget" => 'VARCHAR'
  );

?>