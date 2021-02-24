<?php

/**
 * database for products or services
 * 
 * item_type = service / product
 * item_price_purchasing (net)
 * item_commission (%) calculates the sales price (net)
 * item_producer eg. Apple, Nike, BMW
 * item_supplier eg. Amazon, AliBaba
 * 
 */
 
$database = "jobsKit";
$table_name = "stock";

$cols = array(
  "item_id"  => 'INTEGER NOT NULL PRIMARY KEY',
  "item_hash" => 'VARCHAR',
  "item_hash_parent" => 'VARCHAR',
  "item_categories"  => 'VARCHAR',
  "item_keywords"  => 'VARCHAR',
  "item_type"  => 'VARCHAR',
  "item_categories"  => 'VARCHAR',
  "item_quantity"  => 'VARCHAR',
  "item_unit"  => 'VARCHAR',
  "item_price_purchasing"  => 'VARCHAR',
  "item_commission"  => 'VARCHAR',
  "item_cash_discount"  => 'VARCHAR',
  "item_tax"  => 'VARCHAR',
  /*quantity discount*/
  "item_quantity_scaled1"  => 'VARCHAR',
  "item_price_purchasing_scaled1"  => 'VARCHAR',
  "item_quantity_scaled2"  => 'VARCHAR',
  "item_price_purchasing_scaled2"  => 'VARCHAR',
  "item_quantity_scaled3"  => 'VARCHAR',
  "item_price_purchasing_scaled3"  => 'VARCHAR',
  "item_quantity_scaled4"  => 'VARCHAR',
  "item_price_purchasing_scaled4"  => 'VARCHAR',
  "item_quantity_scaled5"  => 'VARCHAR',
  "item_price_purchasing_scaled5"  => 'VARCHAR',
  "item_quantity_scaled6"  => 'VARCHAR',
  "item_price_purchasing_scaled6"  => 'VARCHAR',
  "item_quantity_scaled7"  => 'VARCHAR',
  "item_price_purchasing_scaled7"  => 'VARCHAR',
  "item_quantity_scaled8"  => 'VARCHAR',
  "item_price_purchasing_scaled8"  => 'VARCHAR',
  "item_quantity_scaled9"  => 'VARCHAR',
  "item_price_purchasing_scaled9"  => 'VARCHAR',
  "item_quantity_scaled10"  => 'VARCHAR',
  "item_price_purchasing_scaled10"  => 'VARCHAR',
  
  /* service */
  "item_hourly_rate"  => 'VARCHAR',
  "item_time_estimated"  => 'VARCHAR',
  
  "item_title"  => 'VARCHAR',
  "item_description"  => 'VARCHAR',
  "item_images"  => 'VARCHAR',
  "item_time_created" => 'VARCHAR',
  "item_time_lastedit" => 'VARCHAR',
  "item_notes" => 'VARCHAR',
  "item_producer"  => 'VARCHAR',
  "item_supplier"  => 'VARCHAR',
  "item_status" => 'VARCHAR',
  "item_snippet_price" => 'VARCHAR'
);


?>