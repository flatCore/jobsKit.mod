<?php
//error_reporting(E_ALL ^E_NOTICE);

$jk_prefs = jk_get_preferences();

if(isset($_REQUEST['change_task_status'])) {
	jk_change_status($_REQUEST['change_task_status']);
}

if(isset($_REQUEST['stimer'])) {
	$timer_id = jk_stop_timer($_REQUEST['stimer']);
	$tasks_send_note_form = file_get_contents($mod_root.'backend/templates/tasks-send-note-form.tpl');
	$tasks_send_note_form = str_replace('{form_title}', $mod_lang['msg_timer_stopped'], $tasks_send_note_form);
	$tasks_send_note_form = str_replace('{form_intro}', $mod_lang['msg_timer_stopped_add_note'], $tasks_send_note_form);
	$tasks_send_note_form = str_replace('{timer_id}', $timer_id, $tasks_send_note_form);
	$tasks_send_note_form = str_replace('{tokken}', $_SESSION['token'], $tasks_send_note_form);
	$tasks_send_note_form = str_replace('{form_action}', "?tn=moduls&sub=jobsKit.mod&a=$a", $tasks_send_note_form);
	echo $tasks_send_note_form;
}

if(isset($_REQUEST['timer'])) {
	jk_start_timer($_REQUEST['timer']);
}

if(isset($_POST['save_note_to_timer'])) {
	save_note_to_timer($_POST['timer_id'],$_POST['timer_note']);
}

$clients_array = jk_get_all_clients();
$all_tasks_array = jk_get_tasks('0','500','NULL','1','NULL');
$my_running_timers = jk_get_my_running_timers();




?>