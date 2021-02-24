<?php

//error_reporting(E_ALL);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_reports'].'</small></h3>';

$now = time();
$first_day_of_month = strtotime('first day of ' . date('F Y'));
$last_day_of_month = strtotime('last day of ' . date('F Y'));

$show_date_start = date('Y-m-d',$first_day_of_month);
$show_date_end = date('Y-m-d',$last_day_of_month);
$report_client_id = 'NULL';

if(isset($_GET['del']) && is_numeric($_GET['del'])) {
	$delete_timer = jk_delete_timer_by_id($_GET['del']);
	
	print_sysmsg("$delete_timer");
}


if($_POST['report_start']) {
	$_SESSION['show_date_start'] = $_POST['report_start'];
}

if($_POST['report_end']) {
	$_SESSION['show_date_end'] = $_POST['report_end'];
}

if($_POST['report_client']) {
	$_SESSION['report_client_id'] = $_POST['report_client'];
}

if($_POST['task_project_id']) {
	$_SESSION['report_project_id'] = $_POST['task_project_id'];
}


if(!isset($_SESSION['show_date_start'])) {
	$_SESSION['show_date_start'] = $show_date_start;
}

if(!isset($_SESSION['show_date_end'])) {
	$_SESSION['show_date_end'] = $show_date_end;
}

if(!isset($_SESSION['report_client_id'])) {
	$_SESSION['report_client_id'] = 'NULL';
}

if(!isset($_SESSION['report_project_id'])) {
	$_SESSION['report_project_id'] = 'NULL';
}

$admins_array = get_all_admins();

if(!isset($_SESSION['report_users'])) {
	foreach($admins_array as $admins) {
		$_SESSION['report_users'] .= $admins['user_nick'].',';
	}
	
}

if($_POST['report_users']) {
	$_SESSION['report_users'] = implode(',', $_POST['report_users']);
}

$_SESSION['report_start_time'] = strtotime($_SESSION['show_date_start']);
$_SESSION['report_end_time'] = strtotime($_SESSION['show_date_end']);

$timer_data = jk_get_timers();


/* client list */

$clients = jk_get_clients();
$select_clients = '<select class="form-control custom-select" name="report_client">';
$select_clients .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($clients as $client) {
	if($client['client_company'] != '') {
		$client_name = $client['client_company'];
	} else {
		$client_name = $client['client_firstname'].' '.$client['client_lastname'];
	}
	
	$selected = '';
	if($client['client_id'] == $_SESSION['report_client_id']) {
		$selected = 'selected';
	}
	
	$select_clients .= '<option value="'.$client['client_id'].'" '.$selected.'>'.$client['client_nbr'].' | '.$client_name.'</option>';
}
$select_clients .= '</select>';



/* projects list */

$active_projects = jk_get_projects();
$select_projects = '<select class="form-control custom-select" name="task_project_id">';
$select_projects .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($active_projects as $projects) {
	
	$selected = '';
	if($projects['project_id'] == $_SESSION['report_project_id']) {
		$selected = 'selected';
	}
	
	$select_projects .= '<option value="'.$projects['project_id'].'" '.$selected.'>'.$projects['project_nbr'].' | '.$projects['project_title'].'</option>';
}
$select_projects .= '</select>';


/* user list */

foreach($admins_array as $admins) {
	$user_list .= '<div class="checkbox">';
	$selected = '';
	if(stripos($_SESSION['report_users'], $admins['user_nick']) !== false) {
		$selected = 'checked';
	}
	$user_list .= '<label><input type="checkbox" name="report_users[]" value="'.$admins['user_nick'].'" '.$selected.'> '.$admins['user_nick'].'</label>';
	$user_list .= '</div>';
}


echo '<div class="row">';
echo '<div class="col-md-8">';

echo '<div id="section-to-export">';

$timer_table = '<table class="table table-sm table-striped">';
$timer_table .= '<tr>';
$timer_table .= '<th>'.$mod_lang['label_times'].'</th>';
$timer_table .= '<th>'.$mod_lang['label_times_sum'].'</th>';
$timer_table .= '<th>'.$mod_lang['label_user'].'</th>';
$timer_table .= '<th>'.$mod_lang['label_task_title'].'</th>';
$timer_table .= '<th></th>';
$timer_table .= '</tr>';

foreach($timer_data as $timer) {
	
	$task_info = jk_get_task_by_hash($timer['timer_task_hash']);
	
	$start = date('d.m.Y H:i:s',$timer['timer_start']);
	$end = date('d.m.Y H:i:s',$timer['timer_end']);
	
	$diff = $timer['timer_end']-$timer['timer_start'];
	$hours = round($diff/3600,2);
	
	$task_hourly_wage = $task_info['task_hourly_wage'];
	$wage = round($hours*$task_hourly_wage,2);
	
	//$wages[] = array('wage' => $task_hourly_wage, 'time' => $hours);
	$wages[$task_hourly_wage][] = $hours;
	$wage_complete = $wage_complete+$wage;
	
	$info = $task_info['task_title'].'<br>';
	if($timer['timer_notes'] != '') {
		$info .= '<small>'.$timer['timer_notes'].'</small><br>';
	}
	$info .= '<small>'.$hours.' h | '.$task_hourly_wage. ' €/h | ' .$wage.' €</small>';
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=reports_edit&tid='.$timer['timer_id'].'" class="btn btn-fc">'.$icon['edit'].'</a>';
	$delete_btn = '<a onclick="return confirm(\''.$mod_lang['confirm_delete_data'].'\')" href="acp.php?tn=moduls&sub=jobsKit.mod&a=reports&del='.$timer['timer_id'].'" class="btn btn-danger">'.$icon['trash_alt'].'</a>';


	$timer_table .= '<tr>';
	$timer_table .= '<td>S: '.$start.'<br>E: '.$end.'</td>';
	$timer_table .= '<td>'.$diff.'s</td>';
	$timer_table .= '<td>'.$timer['timer_user'].'</td>';
	$timer_table .= '<td>'.$info.'</td>';
	$timer_table .= '<td class="text-right" nowrap>'.$edit_btn.' '.$delete_btn.'</td>';
	$timer_table .= '</tr>';
	
}

$timer_table .= '</table>';


echo $timer_table;

echo '<div class="well well-sm">';
echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<table class="table table-sm">';
echo '<th>Stundensatz</th><th>Zeit (h)</th><th>€</th>';
foreach($wages as $k => $v) {
	echo '<tr>';
	unset($g);
	if($k == '') {
		continue;
	}
	echo '<td>'.$k . '</td>';
	foreach($v as $h) {
		$g = $g+$h;
	}
	echo '<td>'.$g.'</td>';
	echo '<td>'.$k*$g.'</td>';
	echo '<tr>';
}

echo '</table>';



echo '</div>';
echo '<div class="col-md-6">';
echo '<hr><p class="h1 text-center">€ '.$wage_complete.'</p>';
echo '</div>';
echo '</div>';
echo '</div>'; // well

echo '</div>'; // print/export area

echo '</div>';
echo '<div class="col-md-4">';
echo '<div class="well well-sm">';

echo '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=reports_edit" class="btn btn-block btn-success">'.$mod_lang['title_timer_new'].'</a>';
echo '<hr class="shadow">';

echo '<form action="acp.php?tn=moduls&sub=jobsKit.mod&a=reports" method="POST">';

echo '<fieldset>';
echo '<legend>Zeitraum</legend>';

echo '<div class="input-group date">';
echo '<input type="text" class="form-control dp" name="report_start" value="'.$_SESSION['show_date_start'].'">';
echo '</div>';

echo '<div class="input-group date">';
echo '<input type="text" class="form-control dp" name="report_end" value="'.$_SESSION['show_date_end'].'">';
echo '</div>';

echo '</fieldset>';

echo '<fieldset>';
echo '<legend>Kunde</legend>';
echo $select_clients;
echo '</fieldset>';

echo '<fieldset>';
echo '<legend>Auftrag</legend>';
echo $select_projects;
echo '</fieldset>';

echo '<fieldset>';
echo '<legend>Benutzer</legend>';
echo $user_list;
echo '</fieldset>';


echo '<input type="submit" class="btn btn-fc" value="'.$mod_lang['btn_send'].'">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</form>';
echo '</div>';
echo '<fieldset>';
echo '<legend>Export</legend>';
echo '<a href="javascript:toPDF()" class="btn btn-fc">PDF</a> ';
echo '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=reports&export=csv" class="btn btn-fc">CSV</a>';
echo '</fieldset>';
echo '</div>';
echo '</div>';





?>