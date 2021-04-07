<?php


//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_stock'].'</small></h3>';

$jk_prefs = jk_get_preferences();


/* delete by id */

if(isset($_POST['delete_item'])) {
	$dbh = new PDO("sqlite:$mod_db");
	$delete = (int) $_POST['delete_item'];
	$sql = "DELETE FROM stock WHERE item_id = $delete";
	$cnt_changes = $dbh->exec($sql);
	$dbh = null;
}


/* default filter status */
if($_SESSION['switch_stock_status'] == '') {
	$_SESSION['switch_stock_status'] = 'all';
}

/* change filter status */
if(isset($_REQUEST['switch_stock_status'])) {
	if($_REQUEST['switch_stock_status'] == 'private') {
		$_SESSION['switch_stock_status'] = 'private';
	} else if($_REQUEST['switch_stock_status'] == 'public') {
		$_SESSION['switch_stock_status'] = 'public';
	} else if($_REQUEST['switch_stock_status'] == 'all') {
		$_SESSION['switch_stock_status'] = 'all';
	}
}

if($_SESSION['switch_stock_status'] == 'all') {
	$active_class_all = 'active';
} else if($_SESSION['switch_stock_status'] == 'public'){
	$active_class_public = 'active';
} else {
	$active_class_private = 'active';
}

$switch_stock_status = '<div class="button-group d-flex">';
$switch_stock_status .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_status=all" class="btn btn-fc btn-sm w-100 '.$active_class_all.'">Alle</a>';
$switch_stock_status .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_status=public" class="btn btn-fc btn-sm w-100 '.$active_class_public.'">Public</a>';
$switch_stock_status .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_status=private" class="btn btn-fc btn-sm w-100 '.$active_class_private.'">Private</a>';
$switch_stock_status .= '</div>';


/* default type filter */
if($_SESSION['switch_stock_type'] == '') {
	$_SESSION['switch_stock_type'] = 'all';
}

/* change filter status */
if(isset($_REQUEST['switch_stock_type'])) {
	if($_REQUEST['switch_stock_type'] == 'p') {
		$_SESSION['switch_stock_type'] = 'p';
	} else if($_REQUEST['switch_stock_type'] == 's') {
		$_SESSION['switch_stock_type'] = 's';
	} else if($_REQUEST['switch_stock_type'] == 'all') {
		$_SESSION['switch_stock_type'] = 'all';
	}
}

if($_SESSION['switch_stock_type'] == 'all') {
	$active_class_stock_all = 'active';
} else if($_SESSION['switch_stock_type'] == 'p'){
	$active_class_stock_product = 'active';
} else {
	$active_class_stock_service = 'active';
}

$switch_stock_type = '<div class="button-group d-flex">';
$switch_stock_type .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_type=all" class="btn btn-fc btn-sm w-100 '.$active_class_stock_all.'">Alle</a>';
$switch_stock_type .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_type=p" class="btn btn-fc btn-sm w-100 '.$active_class_stock_product.'">Produkte</a>';
$switch_stock_type .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&switch_stock_type=s" class="btn btn-fc btn-sm w-100 '.$active_class_stock_service.'">Service</a>';
$switch_stock_type .= '</div>';


/* choose categories */

if($_SESSION['set_cat_string'] == '') {
	$_SESSION['set_cat_string'] = 'all';
}

if(isset($_REQUEST['set_cat']) && $_REQUEST['set_cat'] == 'all') {
	$_SESSION['set_cat_string'] = 'all';
} else if(is_numeric($_REQUEST['set_cat'])) {
	
	$arry_cats = explode('<|>',$_SESSION['set_cat_string']);
	if(!in_array($_REQUEST['set_cat'], $arry_cats)) {
		// add to array
		$arry_cats[] = $_REQUEST['set_cat'];
	} else {
		// remove from array
		$arry_cats = array_diff($arry_cats, array($_REQUEST['set_cat']));
	}
	
	$_SESSION['set_cat_string'] = implode('<|>', $arry_cats);
}

$get_cats = jk_get_stock_categories();
$cnt_get_cats = count($get_cats);
$arry_cats = explode('<|>',$_SESSION['set_cat_string']);

if($_SESSION['set_cat_string'] == 'all') {
	$active_class_cat_all = 'active';
}

$set_cat = '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&set_cat=all" class="btn btn-fc btn-sm w-100 '.$active_class_cat_all.'">Alle</a>';

for($i=0;$i<$cnt_get_cats;$i++) {
	$active_class = '';
	if(in_array($get_cats[$i]['cat_id'], $arry_cats)) {
		$active_class = 'active';
	}
	
	$set_cat .= '<a href="?tn=moduls&sub=jobsKit.mod&a=stock&set_cat='.$get_cats[$i]['cat_id'].'" class="btn btn-fc btn-sm w-100 '.$active_class.'">'.$get_cats[$i]['cat_title'].'</a>';
}


$stock_start = 0;
$stock_limit = 25;

if((isset($_GET['stock_start'])) && is_numeric($_GET['stock_start'])) {
	$stock_start = (int) $_GET['stock_start'];
}

if((isset($_POST['setPage'])) && is_numeric($_POST['setPage'])) {
	$stock_start = (int) $_POST['setPage'];
}


$products_array = jk_get_products($stock_start,$stock_limit,$_SESSION['product_filter'],$_SESSION['switch_stock_status'],$_SESSION['switch_stock_type'],$_SESSION['set_cat_string'],'','DESC');
$cnt_stock = jk_cnt_stock_entries();
$cnt_filter_items = $products_array[0]['cnt_items'];


$nextPage = $stock_start+$stock_limit;
$prevPage = $stock_start-$stock_limit;
$cnt_pages = ceil($cnt_filter_items / $stock_limit);

if($prevPage < 0) {
	$prevPage_btn = '<a class="btn btn-fc w-100 disabled" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
} else {
	$prevPage_btn = '<a class="btn btn-fc w-100" href="?tn=moduls&sub=jobsKit.mod&a=stock&stock_start='.$prevPage.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
}

if($nextPage < ($cnt_filter_items-$stock_limit)+$stock_limit) {
	$nextPage_btn = '<a class="btn btn-fc w-100" href="?tn=moduls&sub=jobsKit.mod&a=stock&stock_start='.$nextPage.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
} else {
	$nextPage_btn = '<a class="btn btn-fc w-100 disabled" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
}

$pag_form = '<form action="?tn=moduls&sub=jobsKit.mod&a=stock" method="POST">';
$pag_form .= '<select class="form-control custom-select" name="setPage" onchange="this.form.submit()">';
for($i=0;$i<$cnt_pages;$i++) {
	$x = $i+1;
	$thisPage = ($x*$stock_limit)-$stock_limit;
	$sel = '';
	if($thisPage == $stock_start) {
		$sel = 'selected';
	}
	$pag_form .= '<option value="'.$thisPage.'" '.$sel.'>Seite '.$x.'</option>';
}
$pag_form .= '</select>';
$pag_form .= '</form>';



/* print the list */

$products_table = '<table class="table table-sm table-sm table-striped">';
$products_table .= '<tr>';
$products_table .= '<th>'.$mod_lang['label_type'].'</th>';
$products_table .= '<th>'.$mod_lang['label_status'].'</th>';
$products_table .= '<th>'.$mod_lang['label_title'].'</th>';
$products_table .= '<th>'.$mod_lang['label_time_recorded'].'</th>';
$products_table .= '<th>'.$mod_lang['label_time_last_edit'].'</th>';
$products_table .= '<th></th>';
$products_table .= '</tr>';

foreach($products_array as $products) {
	
	$type = $products['item_type'];
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=stock_edit&stockid='.$products['item_id'].'&edit='.$type.'" class="btn btn-fc">'.$icon['edit'].'</a>';
	$duplicate_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=stock_edit&stockid='.$products['item_id'].'&edit='.$type.'&mode=d" class="btn btn-fc">'.$icon['copy'].'</a>';
	
	$del_btn  = '<form action="?tn=moduls&sub=jobsKit.mod&a=stock" method="POST">';
	$del_btn .= '<button onclick="return confirm(\''.$mod_lang['confirm_delete_data'].'\')" type="submit" class="btn btn-fc text-danger">'.$icon['trash_alt'].'</button>';
	$del_btn .= '<input type="hidden" name="delete_item" value="'.$products['item_id'].'">'; 
	$del_btn .= '</form>';
	

	if($products['item_status'] == 'public') {
		$item_status = '<span class="text-success">'.$icon['circle'].'</span>';
	} else {
		$item_status = '<span class="text-danger">'.$icon['circle'].'</span>';
	}
	
	if($products['item_type'] == 'p') {
		$item_type = '<span class="">'.$icon['store'].'</span>';
	} else {
		$item_type = '<span class="">'.$icon['user'].'</span>';
	}
	
	$products_table .= '<tr>';
	$products_table .= '<td class="text-center">'.$item_type.'</td>';
	$products_table .= '<td>'.$item_status.'</td>';
	$products_table .= '<td><strong>'.$products['item_title'].'</strong></td>';
	$products_table .= '<td>'.date('d.m.Y H:i',$products['item_time_created']).'</td>';
	$products_table .= '<td>'.date('d.m.Y H:i',$products['item_time_lastedit']).'</td>';

	$products_table .= '<td nowrap><div class="btn-group">'.$edit_btn.' '.$duplicate_btn.' '.$done_btn.' '.$del_btn.'</div></td>';
	$products_table .= '</tr>';
	
}

$products_table .= '</table>';


$tpl_form = file_get_contents($mod_root.'backend/templates/stock.tpl');
$tpl_form = str_replace('{stock_list}', $products_table, $tpl_form);
$tpl_form = str_replace('{status_switch}', $switch_stock_status, $tpl_form);
$tpl_form = str_replace('{type_switch}', $switch_stock_type, $tpl_form);
$tpl_form = str_replace('{choose_categories}', $set_cat, $tpl_form);

$tpl_form = str_replace('{prev_btn}', $prevPage_btn, $tpl_form);
$tpl_form = str_replace('{next_btn}', $nextPage_btn, $tpl_form);
$tpl_form = str_replace('{select_page}', $pag_form, $tpl_form);

$tpl_form = str_replace('{cnt_all}', $cnt_stock['all'], $tpl_form);

echo $tpl_form;


?>