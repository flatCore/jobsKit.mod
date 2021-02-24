<?php

/**
 * jobsKit Database-Scheme
 * install/update the table for preferences
 * 
 * prefs_project_cnt -> next project will become this number
 */

$database = "jobsKit";
$table_name = "prefs";

$cols = array(
	"prefs_id"  => 'INTEGER NOT NULL PRIMARY KEY',
	"prefs_status"  => 'VARCHAR',
	"prefs_version" => 'VARCHAR',
	"prefs_template" => 'VARCHAR',
	"prefs_default_hourly_wage" => 'VARCHAR',
	"prefs_stock_extra_charge_title" => 'VARCHAR',
	"prefs_stock_extra_charge_value" => 'VARCHAR',
	"prefs_stock_extra_charge_unit" => 'VARCHAR',
	"prefs_tax_1" => 'INTEGER',
	"prefs_tax_2" => 'INTEGER',
	"prefs_tax_3" => 'INTEGER',
	"prefs_tax_4" => 'INTEGER',
	"prefs_tax_5" => 'INTEGER',
	"prefs_tax_6" => 'INTEGER',
	"prefs_commission_1" => 'INTEGER',
	"prefs_commission_2" => 'INTEGER',
	"prefs_commission_3" => 'INTEGER',
	"prefs_commission_4" => 'INTEGER',
	"prefs_commission_5" => 'INTEGER',
	"prefs_commission_6" => 'INTEGER',
	"prefs_item_units" => 'VARCHAR',
	"prefs_item_commissions" => 'VARCHAR',
	"prefs_item_quantities" => 'VARCHAR',
	"prefs_project_cnt" => 'INTEGER'
  );
  
  
 
?>