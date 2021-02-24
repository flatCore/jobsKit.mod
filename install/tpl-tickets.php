<?php

$database = "jobsKit";
$table_name = "tickets";

$cols = array(
  "ticket_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "ticket_parent_id" => 'INTEGER',
  "ticket_time" => 'VARCHAR',
  "ticket_type"  => 'VARCHAR',
  "ticket_sender_name"  => 'VARCHAR',
  "ticket_sender_mail"  => 'VARCHAR'
);

?>