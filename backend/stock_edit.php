<?php


//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}


if(!isset($_GET['jid'])) {
	echo '<h3>'.$mod_name.' <small>Eintrag erstellen</small></h3>';
} else {
	echo '<h3>'.$mod_name.' <small>Eintrag bearbeiten</small></h3>';
}


$jk_prefs = jk_get_preferences();
$get_item_id = '';


if(isset($_POST['submitProduct'])) {

	$save_product = jk_save_product($_POST);
	
	if(is_numeric($save_product[0])) {
		$get_stock_id = (int) $save_product[0];
	}

}

if(isset($_GET['stockid'])) {
	$get_stock_id = (int) $_GET['stockid'];
}

if(is_int($get_stock_id)) {
	
	$get_item = jk_get_stock_item_by_id($get_stock_id);
	
	foreach($get_item as $k => $v) {
	   $$k = stripslashes($v);
	}
	
}





if(isset($_GET['edit']) && ($_GET['edit'] == 'p')) {
	/* new or update item -> product */
	$item_type = 'p';
	$form_tpl = file_get_contents($mod_root.'backend/templates/stock-form-product.tpl');
	$form_tpl = str_replace('{item_type}', $item_type, $form_tpl);
		
	

	
	$get_tax = 0;
	
	if($get_item['item_tax'] == '1') {
		$sel_tax_1 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_1'];
	} else if($get_item['item_tax'] == '2') {
		$sel_tax_2 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_2'];
	} else if($get_item['item_tax'] == '3') {
		$sel_tax_3 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_3'];
	}
	
	$select_tax = "<select name='item_tax' class='form-control custom-select'>";
	$select_tax .= '<option value="1" '.$sel_tax_1.'>'.$jk_prefs['prefs_tax_1'].'</option>';
	$select_tax .= '<option value="2" '.$sel_tax_2.'>'.$jk_prefs['prefs_tax_2'].'</option>';
	$select_tax .= '<option value="3" '.$sel_tax_3.'>'.$jk_prefs['prefs_tax_3'].'</option>';
	$select_tax .= '</select>';
	$form_tpl = str_replace('{select_tax}', $select_tax, $form_tpl);
	

	$get_commission = 0;
	
	if($get_item['item_commission'] == '1') {
		$sel_com_1 = 'selected';
		$get_commission = $jk_prefs['prefs_commission_1'];
	} else if($get_item['item_commission'] == '2') {
		$sel_com_2 = 'selected';
		$get_commission = $jk_prefs['prefs_commission_2'];
	} else if($get_item['item_commission'] == '3') {
		$sel_com_3 = 'selected';
		$get_commission = $jk_prefs['prefs_commission_3'];
	}	

	$select_comission = "<select name='item_commission' class='form-control custom-select'>";
	$select_comission .= '<option value="1" '.$sel_com_1.'>'.$jk_prefs['prefs_commission_1'].'</option>';
	$select_comission .= '<option value="2" '.$sel_com_2.'>'.$jk_prefs['prefs_commission_2'].'</option>';
	$select_comission .= '<option value="3" '.$sel_com_3.'>'.$jk_prefs['prefs_commission_3'].'</option>';
	$select_comission .= '</select>';
	$form_tpl = str_replace('{select_commission}', $select_comission, $form_tpl);	
	
	
	$price_net = $item_price_purchasing*($get_commission+100)/100;
	$price_gross = $price_net*($get_tax+100)/100;
	
	$form_tpl = str_replace('{price_net}', number_format($price_net, 2, ',', '.'), $form_tpl);
	$form_tpl = str_replace('{price_gross}', number_format($price_gross, 2, ',', '.'), $form_tpl);
	
	
	for($i=1;$i<10;$i++) {
		$form_tpl = str_replace("{item_quantity_scaled$i}", $get_item["item_quantity_scaled$i"], $form_tpl);
		$form_tpl = str_replace("{item_price_purchasing_scaled$i}", $get_item["item_price_purchasing_scaled$i"], $form_tpl);
		$price_net = $get_item["item_price_purchasing_scaled$i"]*($get_commission+100)/100;
		$price_gross = $price_net*($get_tax+100)/100;
		$price_gross = number_format($price_gross, 2, ',', '.');
		$form_tpl = str_replace("{price_gross_scaled$i}", $price_gross, $form_tpl);
	}
	


	
} else if (isset($_GET['edit']) && ($_GET['edit'] == 's')) {
	/* new or update service */
	$item_type = 's';
	$form_tpl = file_get_contents($mod_root.'backend/templates/stock-form-service.tpl');
	$form_tpl = str_replace('{item_type}', $item_type, $form_tpl);
	
	$get_tax = 0;
	
	if($get_item['item_tax'] == '1') {
		$sel_tax_1 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_1'];
	} else if($get_item['item_tax'] == '2') {
		$sel_tax_2 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_2'];
	} else if($get_item['item_tax'] == '3') {
		$sel_tax_3 = 'selected';
		$get_tax = $jk_prefs['prefs_tax_3'];
	}
	
	$select_tax = "<select name='item_tax' class='form-control custom-select'>";
	$select_tax .= '<option value="1" '.$sel_tax_1.'>'.$jk_prefs['prefs_tax_1'].'</option>';
	$select_tax .= '<option value="2" '.$sel_tax_2.'>'.$jk_prefs['prefs_tax_2'].'</option>';
	$select_tax .= '<option value="3" '.$sel_tax_3.'>'.$jk_prefs['prefs_tax_3'].'</option>';
	$select_tax .= '</select>';
	$form_tpl = str_replace('{select_tax}', $select_tax, $form_tpl);
	

	$price_net = $item_hourly_rate*($item_time_estimated);
	$price_gross = $price_net*($get_tax+100)/100;
	
	$form_tpl = str_replace('{price_net}', number_format($price_net, 2, ',', '.'), $form_tpl);
	$form_tpl = str_replace('{price_gross}', number_format($price_gross, 2, ',', '.'), $form_tpl);	
	
	$form_tpl = str_replace('{item_hourly_rate}', $item_hourly_rate, $form_tpl);
	$form_tpl = str_replace('{item_time_estimated}', $item_time_estimated, $form_tpl);
		
}

/**
 * from here it doesn't matter
 * whether it's a product or a service
 */

/* mode update, new or duplicate */

if(is_int($get_stock_id)) {
	
	$get_item_id = $get_stock_id;
	
	if(isset($_GET['mode']) && ($_GET['mode'] == 'd')) {
		$btn_save = $lang['duplicate'];
		$form_tpl = str_replace('{mode}', "duplicate", $form_tpl);
		$btn_save = $lang['duplicate'];
	} else {
		$form_tpl = str_replace('{mode}', $get_item_id, $form_tpl);
		$btn_save = $lang['update'];
	}

		
} else {
	$form_tpl = str_replace('{mode}', 'new', $form_tpl);
	$btn_save = $lang['save'];
}



$get_cats = jk_get_stock_categories();
$cnt_get_cats = count($get_cats);
$cat_check = '';

$get_categories = explode("<->",$get_item['item_categories']);

for($i=0;$i<$cnt_get_cats;$i++) {
	$cat_title = $get_cats[$i]['cat_title'];
	$cat_id = $get_cats[$i]['cat_id'];
	$checked = '';
	if(in_array($cat_id, $get_categories)) {
		$checked = 'checked';
	}
	
	$cat_check .= '<div class="form-group form-check">';
	$cat_check .= '<input type="checkbox" name="item_categories[]" value="'.$cat_id.'" class="form-check-input" id="id-'.$cat_id.'" '.$checked.'>';
	$cat_check .= '<label class="form-check-label" for="id-'.$cat_id.'">'.$cat_title.'</label>';
	$cat_check .= '</div>';
	
}



/* image select */

$images = fc_scandir_rec('../'.FC_CONTENT_DIR.'/images');

foreach($images as $img) {
	$filemtime = date ("Y", filemtime("$img"));
	$all_images[] = array('name' => $img, 'dateY' => $filemtime);
}

foreach ($all_images as $key => $row) {
	$date[$key]  = $row['dateY'];
	$name[$key] = $row['name'];
}

array_multisort($date, SORT_DESC, $name, SORT_ASC, $all_images);
$array_images = explode("<->", $get_item['item_images']);

$choose_images = '<select multiple="multiple" class="image-picker show-html" name="post_images[]">';

/* if we have selected images, show them first */
if(count($array_images)>1) {
	$choose_images .= '<optgroup label="'.$mod_lang['label_image_selected'].'">';
	foreach($array_images as $sel_images) {
		if(is_file("$sel_images")) {
			$choose_images .= '<option data-img-src="'.$sel_images.'" value="'.$sel_images.'" selected>'.basename($sel_images).'</option>'."\r\n";
		}
	}
	$choose_images .= '</optgroup>'."\r\n";
}

for($i=0;$i<count($all_images);$i++) {
	
	$img_filename = basename($all_images[$i]['name']);
	$image_name = $all_images[$i]['name'];
	$imgsrc = "../$img_path/$all_images[$i][name]";	
	$filemtime = $all_images[$i]['dateY'];
	
	if($ft_prefs_image_prefix != "") {
		if((strpos($image_name, $ft_prefs_image_prefix)) === false) {
			continue;
		}
	}
	/* new label for each year */
	if($all_images[$i-1]['dateY'] != $filemtime) {	
		if($i == 0) {
			$choose_images .= '<optgroup label="'.$filemtime.'">'."\r\n";
		} else {
			$choose_images .= '</optgroup><optgroup label="'.$filemtime.'">'."\r\n";
		}
	}
	
	if(!in_array($image_name, $array_images)) {
		$choose_images .= '<option data-img-src="'.$image_name.'" value="'.$image_name.'">'.$img_filename.'</option>'."\r\n";
	}
	
}
$choose_images .= '</optgroup>'."\r\n";
$choose_images .= '</select>'."\r\n";

$form_tpl = str_replace('{select_images}', $choose_images, $form_tpl);
	

/* status */

if($get_item['item_status'] == "public") {
	$sel_status1 = "selected";
} else {
	$sel_status2 = "selected";
}	
$select_status = "<select name='item_status' class='form-control custom-select'>";
$select_status .= '<option value="public" '.$sel_status1.'>'.$mod_lang['status_public'].'</option>';
$select_status .= '<option value="private" '.$sel_status2.'>'.$mod_lang['status_private'].'</option>';
$select_status .= '</select>';
$form_tpl = str_replace('{select_status}', $select_status, $form_tpl);

$form_action = '?tn=moduls&sub=jobsKit.mod&a=stock_edit&edit='.$item_type;


/* auto fill translations */
foreach($mod_lang as $k => $v) {
	$form_tpl = str_replace('{'.$k.'}', $mod_lang[$k], $form_tpl);
}

$form_tpl = str_replace('{item_keywords}', $item_keywords, $form_tpl);
$form_tpl = str_replace('{item_title}', $item_title, $form_tpl);
$form_tpl = str_replace('{item_description}', $item_description, $form_tpl);
$form_tpl = str_replace('{item_unit}', $item_unit, $form_tpl);
$form_tpl = str_replace('{item_quantity}', $item_quantity, $form_tpl);
$form_tpl = str_replace('{item_price_purchasing}', $item_price_purchasing, $form_tpl);
$form_tpl = str_replace('{item_tax}', $item_tax, $form_tpl);
$form_tpl = str_replace('{item_commission}', $item_commission, $form_tpl);

$form_tpl = str_replace('{select_categories}', $cat_check, $form_tpl);

$form_tpl = str_replace('{form_action}', $form_action, $form_tpl);
$form_tpl = str_replace('{btn_value}', $btn_save, $form_tpl);
$form_tpl = str_replace('{token}', $_SESSION['token'], $form_tpl);

echo $form_tpl;



?>