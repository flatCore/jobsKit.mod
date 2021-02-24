<?php

//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}


if(!isset($_GET['tid'])) {
	echo '<h3>jobsKit <small>'.$mod_lang['title_timer_new'].'</small></h3>';
} else {
	echo '<h3>jobsKit <small>'.$mod_lang['title_timer_edit'].'</small></h3>';
}


if(isset($_POST['submitTimer'])) {
	$save_timer = jk_save_timer($_POST);
	print_sysmsg("$save_timer");
	
	if($_POST['mode'] == 'new') {
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=timer_edit';
	} else {
		$timer_id = (int) $_POST['mode'];
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=timer_edit&tid='.$timer_id;		
	}}


/* tasks list */

$active_tasks = jk_get_tasks('0','500','NULL','1','NULL');
$select_tasks = '<select class="form-control custom-select" name="timer_task_hash">';
$select_tasks .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($active_tasks as $task) {
	$select_tasks .= '<option value="'.$task['task_hash'].'" {selected_hash_'.$task['task_hash'].'}>'.$task['task_title'].'</option>';
}
$select_tasks .= '</select>';


/* projects list */

$active_projects = jk_get_projects('0','100','','1','');
$select_projects = '<select class="form-control custom-select" name="timer_project_id">';
$select_projects .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($active_projects as $projects) {
	$select_projects .= '<option value="'.$projects['project_id'].'" {selected_id_'.$projects['project_id'].'}>'.$projects['project_nbr'].' | '.$projects['project_title'].'</option>';
}
$select_projects .= '</select>';


/* client list */

$clients = jk_get_clients();
$select_clients = '<select class="form-control custom-select" name="timer_client">';
$select_clients .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($clients as $client) {
	if($client['client_company'] != '') {
		$client_name = $client['client_company'];
	} else {
		$client_name = $client['client_firstname'].' '.$client['client_lastname'];
	}
	$select_clients .= '<option value="'.$client['client_id'].'" {selected_id_'.$client['client_id'].'}>'.$client['client_nbr'].' | '.$client_name.'</option>';
}
$select_clients .= '</select>';


/* user list */

$admins_array = get_all_admins();

$user_list = '<select class="form-control custom-select" name="timer_user">';
foreach($admins_array as $admins) {
	$user_list .= '<option value="'.$admins['user_nick'].'" {select_'.$admins['user_nick'].'}> '.$admins['user_nick'].'</option>';
}
$user_list .= '</select>';




$tpl_form = file_get_contents($mod_root.'backend/templates/reports-form.tpl');

if(isset($_GET['tid'])) {
	
	$get_timer = jk_get_timer_by_id($_GET['tid']);
		
	$select_tasks = str_replace("{selected_hash_$get_timer[timer_task_hash]}", 'selected', $select_tasks);
	$user_list = str_replace("{select_$get_timer[timer_user]}", 'selected', $user_list);
	$select_projects = str_replace("{selected_id_$get_timer[timer_project_id]}", 'selected', $select_projects);
	$select_clients = str_replace("{selected_id_$get_timer[timer_client]}", 'selected', $select_clients);
	
	$tpl_form = str_replace('{val_timer_start}', date("Y-m-d H:i",$get_timer['timer_start']), $tpl_form);
	$tpl_form = str_replace('{val_timer_end}', date("Y-m-d H:i",$get_timer['timer_end']), $tpl_form);
	
	$tpl_form = str_replace('{val_timer_notes}', $get_timer['timer_notes'], $tpl_form);

	$btn_save = $lang['update'];
	$tpl_form = str_replace('{mode}', $_GET['tid'], $tpl_form);
		
} else {
	/* new timer */
	
	$tpl_form = str_replace('{val_timer_start}', date("d.m.Y H:i",time()), $tpl_form);
	$tpl_form = str_replace('{val_timer_end}', date("d.m.Y H:i",time()), $tpl_form);
	$tpl_form = str_replace('{val_timer_notes}', '', $tpl_form);
	
	$tpl_form = str_replace('{mode}', 'new', $tpl_form);
	$btn_save = $lang['save'];
}

/* auto fill translations */
foreach($mod_lang as $k => $v) {
	$tpl_form = str_replace('{'.$k.'}', $mod_lang[$k], $tpl_form);
}

$tpl_form = str_replace('{task_list}', $select_tasks, $tpl_form);
$tpl_form = str_replace('{project_list}', $select_projects, $tpl_form);
$tpl_form = str_replace('{user_list}', $user_list, $tpl_form);
$tpl_form = str_replace('{client_list}', $select_clients, $tpl_form);

$tpl_form = str_replace('{form_action}', $form_action, $tpl_form);
$tpl_form = str_replace('{btn_value}', $btn_save, $tpl_form);
$tpl_form = str_replace('{token}', $_SESSION['token'], $tpl_form);

echo $tpl_form;


?>