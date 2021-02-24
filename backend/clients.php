<?php


if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_clients'].'</small></h3>';


if(isset($_POST['submitClient'])) {
	
	$save_client = jk_save_client($_POST);
	print_sysmsg("$save_client");
	
	if($_POST['mode'] == 'new') {
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=clients';
	} else {
		$client_id = (int) $_POST['mode'];
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=clients&cid='.$client_id;		
	}
}


// Listing

$clients_array = jk_get_clients();

$clients_table = '<table class="table table-sm table-striped">';

$clients_table .= '<tr>';
$clients_table .= '<th>'.$mod_lang['label_client_nbr'].'</th>';
$clients_table .= '<th>'.$mod_lang['label_client_company'].'</th>';
$clients_table .= '<th>'.$mod_lang['label_client_lastname'].'</th>';
$clients_table .= '<th>'.$mod_lang['label_client_firstname'].'</th>';
$clients_table .= '<th>'.$mod_lang['label_client_contacts'].'</th>';
$clients_table .= '<th></th>';
$clients_table .= '</tr>';

foreach($clients_array as $client) {
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=clients&cid='.$client['client_id'].'" class="btn btn-fc text-success btn-sm">'.$lang['edit'].'</a>';

	$clients_table .= '<tr>';
	$clients_table .= '<td>'.$client['client_nbr'].'</td>';
	$clients_table .= '<td>'.$client['client_company'].'</td>';
	$clients_table .= '<td>'.$client['client_lastname'].'</td>';
	$clients_table .= '<td>'.$client['client_firstname'].'</td>';
	$clients_table .= '<td>'.$client_contacts.'</td>';
	$clients_table .= '<td>'.$edit_btn.'</td>';
	$clients_table .= '</tr>';
	
}

$clients_table .= '</table>';

$client_nbr = '';
$client_company = '';

$tpl_form = file_get_contents($mod_root.'backend/templates/clients-form.tpl');

if(isset($_GET['cid'])) {
	
	// get clients data
	$get_client = jk_get_client_by_id($_GET['cid']);
	$btn_save = $lang['update'];
	$tpl_form = str_replace('{mode}', $_GET['cid'], $tpl_form);
	
	$tpl_form = str_replace('{val_client_nbr}', $get_client['client_nbr'], $tpl_form);
	$tpl_form = str_replace('{val_client_company}', $get_client['client_company'], $tpl_form);
	$tpl_form = str_replace('{val_client_firstname}', $get_client['client_firstname'], $tpl_form);
	$tpl_form = str_replace('{val_client_lastname}', $get_client['client_lastname'], $tpl_form);
	$tpl_form = str_replace('{val_client_adress}', $get_client['client_adress'], $tpl_form);
	$tpl_form = str_replace('{val_client_mail}', $get_client['client_mail'], $tpl_form);
	$tpl_form = str_replace('{val_client_phone}', $get_client['client_telephone'], $tpl_form);

} else {
	
	// reset form
	$btn_save = $lang['save'];
	
	$tpl_form = str_replace('{val_client_nbr}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_company}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_lastname}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_firstname}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_adress}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_mail}', '', $tpl_form);
	$tpl_form = str_replace('{val_client_phone}', '', $tpl_form);
	$tpl_form = str_replace('{mode}', 'new', $tpl_form);


	
}

$tpl_form = str_replace('{label_client_nbr}', $mod_lang['label_client_nbr'], $tpl_form);
$tpl_form = str_replace('{label_client_company}', $mod_lang['label_client_company'], $tpl_form);
$tpl_form = str_replace('{label_client_lastname}', $mod_lang['label_client_lastname'], $tpl_form);
$tpl_form = str_replace('{label_client_firstname}', $mod_lang['label_client_firstname'], $tpl_form);
$tpl_form = str_replace('{label_client_adress}', $mod_lang['label_client_adress'], $tpl_form);
$tpl_form = str_replace('{label_client_phone}', $mod_lang['label_client_phone'], $tpl_form);
$tpl_form = str_replace('{label_client_website}', $mod_lang['label_client_website'], $tpl_form);
$tpl_form = str_replace('{label_client_mail}', $mod_lang['label_client_mail'], $tpl_form);



$tpl_form = str_replace('{form_action}', $form_action, $tpl_form);
$tpl_form = str_replace('{btn_value}', $btn_save, $tpl_form);
$tpl_form = str_replace('{token}', $_SESSION['token'], $tpl_form);


$tpl_form = str_replace('{btn_value}', $btn_save, $tpl_form);

echo $tpl_form;

echo '<hr>';

echo $clients_table;


?>