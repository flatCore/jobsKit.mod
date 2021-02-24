<?php

//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}


if(!isset($_GET['tid'])) {
	echo '<h3>jobsKit <small>'.$mod_lang['title_task_edit'].'</small></h3>';
} else {
	echo '<h3>jobsKit <small>'.$mod_lang['title_task_new'].'</small></h3>';
}

if(isset($_POST['submitTask'])) {
	
	$save_task = jk_save_task($_POST);
	print_sysmsg("$save_task");
	
	if($_POST['mode'] == 'new') {
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit';
	} else {
		$task_id = (int) $_POST['mode'];
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$task_id;		
	}
}

/* projects list */

$active_projects = jk_get_projects('0','100','','1','');
$select_projects = '<select class="form-control custom-select" name="task_project_id">';
$select_projects .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($active_projects as $projects) {
	$select_projects .= '<option value="'.$projects['project_id'].'" {selected_id_'.$projects['project_id'].'}>'.$projects['project_nbr'].' | '.$projects['project_title'].'</option>';
}
$select_projects .= '</select>';


/* client list */

$clients = jk_get_clients();
$select_clients = '<select class="form-control custom-select" name="task_client">';
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

foreach($admins_array as $admins) {
	$user_list .= '<div class="checkbox">';
	$user_list .= '<label><input type="checkbox" name="task_users[]" value="'.$admins['user_nick'].'" {select_'.$admins['user_nick'].'}> '.$admins['user_nick'].'</label>';
	$user_list .= '</div>';
}

$task_title = '';
$task_text = '';
$time_due = '';
$btn_save = $lang['save'];
$tpl_form = file_get_contents($mod_root.'backend/templates/tasks-form.tpl');

if(isset($_GET['tid'])) {

	$get_task = jk_get_task_by_id($_GET['tid']);
	
	$task_title = $get_task['task_title'];
	$task_text = $get_task['task_notes'];
	$task_project_id = $get_task['task_project_id'];
	$task_client_id = $get_task['task_client'];
	$task_hourly_wage = $get_task['task_hourly_wage'];
	
	if($get_task['task_time_due'] != ''){
		$time_due = date('d.m.Y',$get_task['task_time_due']);
	}
	
	if($get_task['task_priority'] == '1') {
		$tpl_form = str_replace('{checked_priority}', 'checked', $tpl_form);
	}
	
	$btn_save = $lang['update'];
	$tpl_form = str_replace('{mode}', $_GET['tid'], $tpl_form);
	$select_projects = str_replace("{selected_id_$task_project_id}", 'selected', $select_projects);
	$select_clients = str_replace("{selected_id_$task_client_id}", 'selected', $select_clients);
	
	$task_users = explode(',',$get_task['task_users']);
	foreach($task_users as $user) {
		$user_list = str_replace("{select_$user}", 'checked', $user_list);
	}
	
	$tpl_form = str_replace('{val_task_hourly_wage}', $task_hourly_wage, $tpl_form);
	
	
	if($get_task['task_repeat'] == 'daily') {
		$tpl_form = str_replace('{checked_repeat_daily}', 'checked', $tpl_form);
	} else if($get_task['task_repeat'] == 'weekly') {
		$tpl_form = str_replace('{checked_repeat_weekly}', 'checked', $tpl_form);
	} else if($get_task['task_repeat'] == 'monthly') {
		$tpl_form = str_replace('{checked_repeat_monthly}', 'checked', $tpl_form);
	} else if($get_task['task_repeat'] == 'yearly') {
		$tpl_form = str_replace('{checked_repeat_yearly}', 'checked', $tpl_form);
	} else {
		$tpl_form = str_replace('{checked_repeat_never}', 'checked', $tpl_form);
	}
	
	if($get_task['task_status'] == '1') {
		$tpl_form = str_replace('{checked_task_open}', 'checked', $tpl_form);
		$tpl_form = str_replace('{class_open_active}', 'active', $tpl_form);
	} else {
		$tpl_form = str_replace('{checked_task_done}', 'checked', $tpl_form);
		$tpl_form = str_replace('{class_done_active}', 'active', $tpl_form);
	}

	$tpl_form = str_replace('{checked_repeat_never}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_daily}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_weekly}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_monthly}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_yearly}', '', $tpl_form);	
	
} else {
	
	/*new task */
	
	$tpl_form = str_replace('{mode}', 'new', $tpl_form);
	
	$tpl_form = str_replace('{checked_repeat_never}', 'checked', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_daily}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_weekly}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_monthly}', '', $tpl_form);
	$tpl_form = str_replace('{checked_repeat_yearly}', '', $tpl_form);
	$tpl_form = str_replace('{checked_task_open}', 'checked', $tpl_form);
	$tpl_form = str_replace('{class_open_active}', 'active', $tpl_form);
	$tpl_form = str_replace('{val_task_hourly_wage}', '', $tpl_form);
	
	
}


$tpl_form = str_replace('{label_repeat}', $mod_lang['label_repeat'], $tpl_form);
$tpl_form = str_replace('{label_repeat_never}', $mod_lang['label_repeat_never'], $tpl_form);
$tpl_form = str_replace('{label_repeat_daily}', $mod_lang['label_repeat_daily'], $tpl_form);
$tpl_form = str_replace('{label_repeat_weekly}', $mod_lang['label_repeat_weekly'], $tpl_form);
$tpl_form = str_replace('{label_repeat_monthly}', $mod_lang['label_repeat_monthly'], $tpl_form);
$tpl_form = str_replace('{label_repeat_yearly}', $mod_lang['label_repeat_yearly'], $tpl_form);
$tpl_form = str_replace('{label_project}', $mod_lang['label_project'], $tpl_form);
$tpl_form = str_replace('{label_user}', $mod_lang['label_user'], $tpl_form);
$tpl_form = str_replace('{label_client}', $mod_lang['label_client'], $tpl_form);
$tpl_form = str_replace('{label_deadline}', $mod_lang['label_time_due'], $tpl_form);
$tpl_form = str_replace('{label_hourly_wage}', $mod_lang['label_hourly_wage'], $tpl_form);
$tpl_form = str_replace('{label_status}', $mod_lang['label_status'], $tpl_form);
$tpl_form = str_replace('{repeat_list}', $repeat_list, $tpl_form);

$tpl_form = str_replace('{project_list}', $select_projects, $tpl_form);
$tpl_form = str_replace('{user_list}', $user_list, $tpl_form);
$tpl_form = str_replace('{client_list}', $select_clients, $tpl_form);

$tpl_form = str_replace('{val_task_due}', $time_due, $tpl_form);
$tpl_form = str_replace('{val_task_title}', $task_title, $tpl_form);
$tpl_form = str_replace('{val_task_text}', $task_text, $tpl_form);

$tpl_form = str_replace('{form_action}', $form_action, $tpl_form);
$tpl_form = str_replace('{btn_value}', $btn_save, $tpl_form);
$tpl_form = str_replace('{btn_task_open}', $mod_lang['btn_task_open'], $tpl_form);
$tpl_form = str_replace('{btn_task_done}', $mod_lang['btn_task_done'], $tpl_form);
$tpl_form = str_replace('{token}', $_SESSION['token'], $tpl_form);

echo $tpl_form;

?>