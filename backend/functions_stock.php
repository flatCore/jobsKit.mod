<?php

/* stock functions */

if(!defined('FC_INC_DIR')) {
	die("No access");
}


/**
 * get stock item by id
 * return array
 */
 
function jk_get_stock_item_by_id($stock_id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM stock WHERE item_id = $stock_id";
	$get_item = $dbh->query($sql);
	$get_item = $get_item->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_item;

}


/**
 * save, duplicate or update Product/Service entry
 */
 
function jk_save_product($data) {
	
	global $mod_db;
	$item_time_created = time();
	
	$images_string = @implode("<->", $data['post_images']);
	$images_string = "<->$images_string<->";
	
	$cat_string = @implode("<->", $data['item_categories']);
	$cat_string = "<->$cat_string<->";
	
	$dbh = new PDO("sqlite:$mod_db");
	
	if($data['mode'] == 'new' OR $data['mode'] == 'duplicate') {
		// new product
		$sql = "INSERT INTO stock (
		item_id, item_title, item_description, item_time_created, item_time_lastedit, item_unit, item_quantity, item_price_purchasing, item_tax, item_commission, item_status, item_images,
		item_type, item_keywords, item_hourly_rate, item_time_estimated, item_categories,
		item_quantity_scaled1, item_price_purchasing_scaled1, item_quantity_scaled2, item_price_purchasing_scaled2, item_quantity_scaled3, item_price_purchasing_scaled3,
		item_quantity_scaled4, item_price_purchasing_scaled4, item_quantity_scaled5, item_price_purchasing_scaled5, item_quantity_scaled6, item_price_purchasing_scaled6,
		item_quantity_scaled7, item_price_purchasing_scaled7, item_quantity_scaled8, item_price_purchasing_scaled8, item_quantity_scaled9, item_price_purchasing_scaled9
		) VALUES (
			NULL, :item_title, :item_description, :item_time_created, :item_time_lastedit, :item_unit, :item_quantity, :item_price_purchasing, :item_tax, :item_commission, :item_status, :item_images,
			:item_type, :item_keywords, :item_hourly_rate, :item_time_estimated, :item_categories,
			:item_quantity_scaled1, :item_price_purchasing_scaled1, :item_quantity_scaled2, :item_price_purchasing_scaled2, :item_quantity_scaled3, :item_price_purchasing_scaled3,
			:item_quantity_scaled4, :item_price_purchasing_scaled4, :item_quantity_scaled5, :item_price_purchasing_scaled5, :item_quantity_scaled6, :item_price_purchasing_scaled6,
			:item_quantity_scaled7, :item_price_purchasing_scaled7, :item_quantity_scaled8, :item_price_purchasing_scaled8, :item_quantity_scaled9, :item_price_purchasing_scaled9
		)";
		
		$sth = $dbh->prepare($sql);
		
		$sth->bindParam(':item_title', $data['item_title'], PDO::PARAM_STR);
		$sth->bindParam(':item_description', $data['item_description'], PDO::PARAM_STR);
		$sth->bindParam(':item_type', $data['item_type'], PDO::PARAM_STR);
		$sth->bindParam(':item_keywords', $data['item_keywords'], PDO::PARAM_STR);
		$sth->bindParam(':item_time_created', $item_time_created, PDO::PARAM_STR);
		$sth->bindParam(':item_time_lastedit', $item_time_created, PDO::PARAM_STR);
		$sth->bindParam(':item_unit', $data['item_unit'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity', $data['item_quantity'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing', $data['item_price_purchasing'], PDO::PARAM_STR);
		$sth->bindParam(':item_tax', $data['item_tax'], PDO::PARAM_STR);
		$sth->bindParam(':item_commission', $data['item_commission'], PDO::PARAM_STR);
		$sth->bindParam(':item_status', $data['item_status'], PDO::PARAM_STR);
		$sth->bindParam(':item_images', $images_string, PDO::PARAM_STR);
		$sth->bindParam(':item_categories', $cat_string, PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled1', $data['item_quantity_scaled1'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled2', $data['item_quantity_scaled2'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled3', $data['item_quantity_scaled3'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled4', $data['item_quantity_scaled4'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled5', $data['item_quantity_scaled5'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled6', $data['item_quantity_scaled6'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled7', $data['item_quantity_scaled7'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled8', $data['item_quantity_scaled8'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled9', $data['item_quantity_scaled9'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled1', $data['item_price_purchasing_scaled1'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled2', $data['item_price_purchasing_scaled2'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled3', $data['item_price_purchasing_scaled3'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled4', $data['item_price_purchasing_scaled4'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled5', $data['item_price_purchasing_scaled5'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled6', $data['item_price_purchasing_scaled6'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled7', $data['item_price_purchasing_scaled7'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled8', $data['item_price_purchasing_scaled8'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled9', $data['item_price_purchasing_scaled9'], PDO::PARAM_STR);
		$sth->bindParam(':item_hourly_rate', $data['item_hourly_rate'], PDO::PARAM_STR);
		$sth->bindParam(':item_time_estimated', $data['item_time_estimated'], PDO::PARAM_STR);

		$cnt_changes = $sth->execute();
		$last_insert_id = $dbh->lastInsertId();
		
		jk_record_log('new stock item','stock');
			
	} else {
		// update product
		$product_id = (int) $data['mode'];
		$sql = "UPDATE stock
						SET item_title = :item_title,
						item_time_lastedit = :item_time_lastedit,
						item_description = :item_description,
						item_type = :item_type,
						item_keywords = :item_keywords,
						item_unit = :item_unit,
						item_quantity = :item_quantity,
						item_price_purchasing = :item_price_purchasing,
						item_tax = :item_tax,
						item_commission = :item_commission,
						item_status = :item_status,
						item_images = :item_images,
						item_categories = :item_categories,
						item_quantity_scaled1 = :item_quantity_scaled1,
						item_quantity_scaled2 = :item_quantity_scaled2,
						item_quantity_scaled3 = :item_quantity_scaled3,
						item_quantity_scaled4 = :item_quantity_scaled4,
						item_quantity_scaled5 = :item_quantity_scaled5,
						item_quantity_scaled6 = :item_quantity_scaled6,
						item_quantity_scaled7 = :item_quantity_scaled7,
						item_quantity_scaled8 = :item_quantity_scaled8,
						item_quantity_scaled9 = :item_quantity_scaled9,
						item_price_purchasing_scaled1 = :item_price_purchasing_scaled1,
						item_price_purchasing_scaled2 = :item_price_purchasing_scaled2,
						item_price_purchasing_scaled3 = :item_price_purchasing_scaled3,
						item_price_purchasing_scaled4 = :item_price_purchasing_scaled4,
						item_price_purchasing_scaled5 = :item_price_purchasing_scaled5,
						item_price_purchasing_scaled6 = :item_price_purchasing_scaled6,
						item_price_purchasing_scaled7 = :item_price_purchasing_scaled7,
						item_price_purchasing_scaled8 = :item_price_purchasing_scaled8,
						item_price_purchasing_scaled9 = :item_price_purchasing_scaled9,
						item_hourly_rate = :item_hourly_rate,
						item_time_estimated = :item_time_estimated
						WHERE item_id = :item_id ";
		
		$sth = $dbh->prepare($sql);
		
		$sth->bindParam(':item_id', $product_id, PDO::PARAM_INT);
		$sth->bindParam(':item_time_lastedit', $item_time_created, PDO::PARAM_STR);
		$sth->bindParam(':item_title', $data['item_title'], PDO::PARAM_STR);
		$sth->bindParam(':item_description', $data['item_description'], PDO::PARAM_STR);
		$sth->bindParam(':item_type', $data['item_type'], PDO::PARAM_STR);
		$sth->bindParam(':item_keywords', $data['item_keywords'], PDO::PARAM_STR);
		$sth->bindParam(':item_unit', $data['item_unit'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity', $data['item_quantity'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing', $data['item_price_purchasing'], PDO::PARAM_STR);
		$sth->bindParam(':item_tax', $data['item_tax'], PDO::PARAM_STR);
		$sth->bindParam(':item_commission', $data['item_commission'], PDO::PARAM_STR);
		$sth->bindParam(':item_status', $data['item_status'], PDO::PARAM_STR);
		$sth->bindParam(':item_images', $images_string, PDO::PARAM_STR);
		$sth->bindParam(':item_categories', $cat_string, PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled1', $data['item_quantity_scaled1'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled2', $data['item_quantity_scaled2'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled3', $data['item_quantity_scaled3'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled4', $data['item_quantity_scaled4'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled5', $data['item_quantity_scaled5'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled6', $data['item_quantity_scaled6'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled7', $data['item_quantity_scaled7'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled8', $data['item_quantity_scaled8'], PDO::PARAM_STR);
		$sth->bindParam(':item_quantity_scaled9', $data['item_quantity_scaled9'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled1', $data['item_price_purchasing_scaled1'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled2', $data['item_price_purchasing_scaled2'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled3', $data['item_price_purchasing_scaled3'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled4', $data['item_price_purchasing_scaled4'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled5', $data['item_price_purchasing_scaled5'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled6', $data['item_price_purchasing_scaled6'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled7', $data['item_price_purchasing_scaled7'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled8', $data['item_price_purchasing_scaled8'], PDO::PARAM_STR);
		$sth->bindParam(':item_price_purchasing_scaled9', $data['item_price_purchasing_scaled9'], PDO::PARAM_STR);
		$sth->bindParam(':item_hourly_rate', $data['item_hourly_rate'], PDO::PARAM_STR);
		$sth->bindParam(':item_time_estimated', $data['item_time_estimated'], PDO::PARAM_STR);

		$cnt_changes = $sth->execute();
		$last_insert_id = $product_id;
		
		jk_record_log('update stock item','stock');
		
	}
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $lang['entry_saved'];
	} else {
		$sys_message = '{ERROR} ' . $lang['entry_saved_error'];
	}
	
	$return = array("$last_insert_id","$sys_message");

	return $return;
	

}




/**
 * get stock - products and services
 * filter - title and description
 * status - all 0 / public 1 / private 2
 */
 
function jk_get_products($start='0',$nbr='10',$filter='',$status='',$type='',$categories='',$order='',$direction='') {

	global $mod_db;
	
	$start = (int) $start;
	$nbr = (int) $nbr;
	
	$where = "WHERE item_id IS NOT NULL";
	
		
	if($filter != '') {
		$where .= " AND (item_title LIKE '%$filter%' OR item_decription LIKE '%$filter%')";
	}
	
	if($order == '') {
		$order = 'item_id';
	}

	if($direction == '') {
		$direction = 'DESC';
	}
	
	if($status != '' AND $status != 'all') {
		$where .= " AND (item_status LIKE '$status')";
	}
	
	if($type != '' AND $type != 'all') {
		$where .= " AND (item_type LIKE '$type')";
	}
	
	
	/* filter by category */
	if($categories != '' AND $categories != 'all') {
		
		$arry_cats = explode('<|>',$categories);
		
		$where .= " AND (";
		foreach($arry_cats as $category) {
			$where .= "item_categories LIKE '%$category%' OR ";
		}
		$where = substr($where, 0,-3);
		$where .= ")";	
	}


	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM stock $where ORDER BY $order $direction LIMIT $start, $nbr";
	

   foreach ($dbh->query($sql) as $row) {
     $entries[] = $row;
   }
   
	$sql_cnt = "SELECT count(*) AS 'A', (SELECT count(*) FROM stock $where) AS 'F'";
	$stat = $dbh->query("$sql_cnt")->fetch(PDO::FETCH_ASSOC);

	 $dbh = null;
	 
	/* number of items that match the filter */
	$entries[0]['cnt_items'] = $stat['F'];
	 
	 return $entries;

	
}


/**
 * count all entries from stock
 */
 
function jk_cnt_stock_entries() {
	
	global $mod_db;
	global $mod;
	
	if(FC_SOURCE == 'frontend') {
		$mod_db = $mod['database'];
	}
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "
	SELECT count(*) AS 'all',
	(SELECT count(*) FROM stock WHERE item_type = 'p' ) AS 'product', 
	(SELECT count(*) FROM stock WHERE item_type = 's' ) AS 'service',
	(SELECT count(*) FROM stock WHERE item_status = 'public' ) AS 'public',
	(SELECT count(*) FROM stock WHERE item_status = 'private' ) AS 'private'
	FROM stock
	";
	$stats = $dbh->query("$sql")->fetch(PDO::FETCH_ASSOC);
	return $stats;
}




/**
 * get categories
 *
 */

function jk_get_stock_categories() {
	
	global $mod_db;
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM categories ORDER BY cat_sort ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$entries = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	return $entries;

}





?>