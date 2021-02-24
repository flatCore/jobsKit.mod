<?php
//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

include 'functions_jobs.php';
include 'functions_stock.php';
include 'functions_tasks.php';

/**
 * get preferences
 */
 
function jk_get_preferences() {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");

	$sql = "SELECT * FROM prefs WHERE prefs_status = 'active' ";
	$prefs = $dbh->query($sql);
	$prefs = $prefs->fetch(PDO::FETCH_ASSOC);

	$dbh = null;
	
	return $prefs;
	
}


/**
 * update preferences
 */
 
function jk_update_preferences() {
	
	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "UPDATE prefs
						SET prefs_item_units = :prefs_item_units,
						prefs_item_commissions = :prefs_item_commissions,
						prefs_item_quantities = :prefs_item_quantities,
						prefs_tax_1 = :prefs_tax_1,
						prefs_tax_2 = :prefs_tax_2,
						prefs_tax_3 = :prefs_tax_3,
						prefs_commission_1 = :prefs_commission_1,
						prefs_commission_2 = :prefs_commission_2,
						prefs_commission_3 = :prefs_commission_3
						WHERE prefs_status = 'active' ";	
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':prefs_item_units', $_POST['prefs_item_units'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_item_commissions', $_POST['prefs_item_commissions'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_item_quantities', $_POST['prefs_item_quantities'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_tax_1', $_POST['prefs_tax_1'], PDO::PARAM_INT);
	$sth->bindParam(':prefs_tax_2', $_POST['prefs_tax_2'], PDO::PARAM_INT);
	$sth->bindParam(':prefs_tax_3', $_POST['prefs_tax_3'], PDO::PARAM_INT);
	$sth->bindParam(':prefs_commission_1', $_POST['prefs_commission_1'], PDO::PARAM_INT);
	$sth->bindParam(':prefs_commission_2', $_POST['prefs_commission_2'], PDO::PARAM_INT);
	$sth->bindParam(':prefs_commission_3', $_POST['prefs_commission_3'], PDO::PARAM_INT);
	$cnt_changes = $sth->execute();
	
	jk_record_log('update preferences','jobskit');
	
}




/**
 * save or update Client
 *
 */

function jk_save_client($data) {
	
	global $mod_db;
	global $mod_lang;

	$client_time_recorded = time();
	

	$client_nbr = $data['client_nbr'];
	$client_company = $data['client_company'];
	$client_lastname = $data['client_lastname'];
	$client_firstname = $data['client_firstname'];
	$client_adress = $data['client_adress'];
	$client_mail = $data['client_mail'];
	$client_phone = $data['client_phone'];


	$dbh = new PDO("sqlite:$mod_db");
	
	/**
	 * $_POST['mode'] = new or integer (client_id)
	 */
	
	if($data['mode'] == 'new') {
		
		$sql = "INSERT INTO clients (
			client_id, client_company, client_nbr, client_lastname, client_firstname, client_adress, client_mail, client_telephone
		) VALUES (
			NULL, :client_company, :client_nbr, :client_lastname, :client_firstname, :client_adress, :client_mail, :client_phone
		)";
		
		try {
			$sth = $dbh->prepare($sql);
			
			$sth->bindParam(':client_company', $client_company, PDO::PARAM_STR);
			$sth->bindParam(':client_nbr', $client_nbr, PDO::PARAM_STR);
			$sth->bindParam(':client_lastname', $client_lastname, PDO::PARAM_STR);
			$sth->bindParam(':client_firstname', $client_firstname, PDO::PARAM_STR);
			$sth->bindParam(':client_adress', $client_adress, PDO::PARAM_STR);
			$sth->bindParam(':client_mail', $client_mail, PDO::PARAM_STR);
			$sth->bindParam(':client_phone', $client_phone, PDO::PARAM_STR);

		} catch(PDOException $e) {
			 $message = $e->getMessage();
			 print_r($message);
		}
			
	} else {
		
		$client_id = (int) $data['mode'];
		
		$sql = "UPDATE clients
						SET client_company = :client_company,
						client_nbr = :client_nbr,
						client_lastname = :client_lastname,
						client_firstname = :client_firstname,
						client_adress = :client_adress,
						client_mail = :client_mail,
						client_telephone = :client_phone
						WHERE client_id = :client_id ";
		
		try {
			
			$sth = $dbh->prepare($sql);
					
			$sth->bindParam(':client_id', $client_id, PDO::PARAM_INT);
			$sth->bindParam(':client_company', $client_company, PDO::PARAM_STR);
			$sth->bindParam(':client_nbr', $client_nbr, PDO::PARAM_STR);
			$sth->bindParam(':client_lastname', $client_lastname, PDO::PARAM_STR);
			$sth->bindParam(':client_firstname', $client_firstname, PDO::PARAM_STR);
			$sth->bindParam(':client_adress', $client_adress, PDO::PARAM_STR);
			$sth->bindParam(':client_mail', $client_mail, PDO::PARAM_STR);
			$sth->bindParam(':client_phone', $client_phone, PDO::PARAM_STR);
		} catch(PDOException $e) {
			 $message = $e->getMessage();
			 print_r($message);
		}
		
	}
	
	$cnt_changes = $sth->execute();
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $mod_lang['msg_client_saved'];
		record_log("$_SESSION[user_nick]","New Client <i>$client_nbr</i>","0");
	} else {
		$sys_message = '{ERROR} ' . $mod_lang['msg_client_saved_error'];
	}
	
	return $sys_message;
	
}

/**
 * get clients
 */
 
function jk_get_clients() {

	global $mod_db;
	
	
	$where = "WHERE client_id IS NOT NULL";
	
		
	if($filter != '') {
		$where .= " AND (project_title LIKE '%$filter%' OR project_text LIKE '%$filter%')";
	}
	

	if($direction == '') {
		$direction = 'ASC';
	}	
	
	if($order == '') {
		//$order = 'client_company DESC, -client_company ASC';
		$order = 'CASE WHEN client_company = "" THEN 2 ELSE 1 END, client_company';
	}


	
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM clients $where ORDER BY $order";

   foreach ($dbh->query($sql) as $row) {
     $entries[] = $row;
   }

	 $dbh = null;
	 
	 return $entries;

	
}

/**
 * get all clients
 * return array $array[client_id] => client
 */

function jk_get_all_clients() {
	global $mod_db;
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT client_id, client_company, client_lastname, client_firstname FROM clients";
	$get_clients = $dbh->query($sql);
	$get_clients = $get_clients->fetchAll(PDO::FETCH_GROUP);
	$dbh = null;
	return $get_clients;
}



/**
 * get client by id
 */

function jk_get_client_by_id($client_id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM clients WHERE client_id = $client_id";
	$get_client = $dbh->query($sql);
	$get_client = $get_client->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_client;

}



/**
 * record activities
 */

function jk_record_log($activity_text = '', $activity_module = '') {

	$activity_time = time();
	$activity_user = $_SESSION['user_nick'];
	
	global $mod_db;
	$dbh = new PDO("sqlite:$mod_db");
	
		$sql_insert = "INSERT INTO activities (
		activity_id, activity_time, activity_user, activity_text, activity_module
	) VALUES (
		NULL, :activity_time, :activity_user, :activity_text, :activity_module
	)";
	
	$sth = $dbh->prepare($sql_insert);
	$sth->bindParam(':activity_time', $activity_time, PDO::PARAM_STR);
	$sth->bindParam(':activity_user', $activity_user, PDO::PARAM_STR);
	$sth->bindParam(':activity_text', $activity_text, PDO::PARAM_STR);
	$sth->bindParam(':activity_module', $activity_module, PDO::PARAM_STR);
	
	$sth->execute();
	$dbh = null;

}



?>