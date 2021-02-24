<?php
//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

include $mod_root.'backend/include.php';

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_tasks'].'</small></h3>';


$tpl_layout = file_get_contents($mod_root.'backend/templates/tasks.tpl');

if(empty($jk_alert)) {
	$tpl_layout = str_replace('{alert}', '', $tpl_layout);
} else {
	$tpl_layout = str_replace('{alert}', $jk_alert, $tpl_layout);
}

if(isset($_GET['del']) && is_numeric($_GET['del'])) {
	jk_delete_task_by_id($_GET['del']);
}

if($_SESSION['sort'] == '') {
	$_SESSION['sort'] = 'date';
	$class_date = 'active';
	$class_user = '';
	$class_priority = '';
}

if($_SESSION['dir'] == '') {
	$_SESSION['dir'] = 'ASC';
}

if(isset($_GET['sort'])) {
	
	if($_SESSION['sort'] == $_GET['sort']) {
		if($_SESSION['dir'] == 'ASC') {
			$_SESSION['dir'] = 'DESC';
		} else {
			$_SESSION['dir'] = 'ASC';
		}
	}
	$_SESSION['sort'] = $_GET['sort'];
}

if($_SESSION['sort'] == 'date') {
	$class_date = 'active';
	$class_user = '';
	$class_priority = '';	
}

if($_SESSION['sort'] == 'user') {
	$class_date = '';
	$class_user = 'active';
	$class_priority = '';	
}

if($_SESSION['sort'] == 'priority') {
	$class_date = '';
	$class_user = '';
	$class_priority = 'active';	
}

if($_SESSION['dir'] == 'ASC') {
	$dir_icon = $icon['angle_up'];
} else {
	$dir_icon = $icon['angle_down'];
}



if($_POST['task_client_id']) {
	$_SESSION['task_client_id'] = $_POST['task_client_id'];
}

if($_POST['task_project_id']) {
	$_SESSION['task_project_id'] = $_POST['task_project_id'];
}

if(!isset($_SESSION['task_client_id'])) {
	$_SESSION['task_client_id'] = 'NULL';
}

if(!isset($_SESSION['task_project_id'])) {
	$_SESSION['task_project_id'] = 'NULL';
}




/* client list */

$clients = jk_get_clients();
$select_clients = '<select class="form-control custom-select" name="task_client_id">';
$select_clients .= '<option value="NULL">'.$mod_lang['label_without'].'</option>';
foreach($clients as $client) {
	if($client['client_company'] != '') {
		$client_name = $client['client_company'];
	} else {
		$client_name = $client['client_firstname'].' '.$client['client_lastname'];
	}
	
	$selected = '';
	if($client['client_id'] == $_SESSION['task_client_id']) {
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
	if($projects['project_id'] == $_SESSION['task_project_id']) {
		$selected = 'selected';
	}
	
	$select_projects .= '<option value="'.$projects['project_id'].'" '.$selected.'>'.$projects['project_nbr'].' | '.$projects['project_title'].'</option>';
}
$select_projects .= '</select>';


$admins_array = get_all_admins();

if(!isset($_SESSION['task_users'])) {
	$_SESSION['task_users'] = 'NULL';
}

if($_POST['task_users']) {
	$_SESSION['task_users'] = implode(',', $_POST['task_users']);
}

/* user list */

$user_list .= '<div class="checkbox">';
if(stripos($_SESSION['task_users'], 'NULL') !== false) {
	$selected_all = 'checked';
}	
$user_list .= '<label><input type="checkbox" name="task_users[]" value="NULL" '.$selected_all.'> '.$mod_lang['label_all'].'</label>';
$user_list .= '</div><hr>';

foreach($admins_array as $admins) {
	$user_list .= '<div class="checkbox">';


	
	$selected = '';
	if(stripos($_SESSION['task_users'], $admins['user_nick']) !== false) {
		$selected = 'checked';
	}
	$user_list .= '<label><input type="checkbox" name="task_users[]" value="'.$admins['user_nick'].'" '.$selected.'> '.$admins['user_nick'].'</label>';
	$user_list .= '</div>';
}




$tasks_array = jk_get_tasks('0','500','NULL','1','NULL');

if(count($tasks_array)<1) {
	echo '<div class="alert alert-info">'.$mod_lang['msg_no_data_yet'].'</div>';
}

$tasks_table = '<table class="table table-sm table-striped">';
$tasks_table .= '<tr>';
$tasks_table .= '<th></th>';
$tasks_table .= '<th><a href="?tn=moduls&sub=jobsKit.mod&a=tasks&sort=priority">'.$icon['star'].'</a> '.$mod_lang['label_title'].'</th>';
$tasks_table .= '<th><a href="?tn=moduls&sub=jobsKit.mod&a=tasks&sort=date">'.$mod_lang['label_time_due'].'</a></th>';
$tasks_table .= '<th><a href="?tn=moduls&sub=jobsKit.mod&a=tasks&sort=user">'.$mod_lang['label_user'].'</a></th>';
$tasks_table .= '<th>€/h</th>';
$tasks_table .= '<th></th>';
$tasks_table .= '</tr>';

foreach($tasks_array as $tasks) {
	
	$btn_class_status = '';
	$tr_class_status = '';
	$time_due = date('d.m.Y',$tasks['task_time_due']);
	
	if($tasks['task_time_due'] < time()) {
		$time_due = '<span style="color:red;">'.$time_due.'</span>';
	}
	
	if($tasks['task_status'] == '2') {
		$btn_class_status = 'active';
		$tr_class_status = 'task-done';
	}
	
	if($tasks['task_users'] == 'all') {
		$task_users = '';
	} else {
		$task_users = '';
		$task_users_array = explode(',',$tasks['task_users']);
		foreach($task_users_array as $user) {
			$user_avatar_path = '../'. FC_CONTENT_DIR . '/avatars/' . md5($user) . '.png';
			if(is_file($user_avatar_path)) {
				$task_users .= '<img src="'.$user_avatar_path.'" width="35" height="35" class="rounded-circle" title="'.$user.'"> ';
			} else {
				$task_users .= '<img src="/acp/images/avatar.png" width="35" height="35" class="rounded-circle" title="'.$user.'"> ';
			}
			
		}
		
		
	}
	
	$repeat_msg = '';
	if($tasks['task_repeat'] != 'never') {
		$msg = $mod_lang["label_repeat_$tasks[task_repeat]"];
		$repeat_msg = '<p class="small text-muted" style="white-space: nowrap;">'.$icon['sync_alt'].' '.$msg.'</p>';
	}
	
	$star = '';
	if($tasks['task_priority'] == '1') {
		$star = '<span class="glyphicon glyphicon-star text-primary"></span> ';
	}

	$client = '<p class="small text-muted">'.$tasks['task_client_name'].' '.$tasks['task_project_nbr'].' '.$tasks['task_project_title'].'</p>';
	
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$tasks['task_id'].'" class="btn btn-fc">'.$icon['edit'].'</a>';
	$done_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&change_task_status='.$tasks['task_id'].'" class="btn btn-fc '.$btn_class_status.'">'.$icon['check'].'</a>';
	$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&timer='.$tasks['task_id'].'" class="btn btn-fc">'.$icon['clock'].'</a>';
	$delete_btn = '<a onclick="return confirm(\''.$mod_lang['confirm_delete_data'].'\')" href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&del='.$tasks['task_id'].'" class="btn btn-danger">'.$icon['trash_alt'].'</a>';
	
	// check if this task has a running timer
	if(in_array($tasks['task_hash'], $my_running_timers)) {
			$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&stimer='.$tasks['task_hash'].'" class="btn btn-success">'.$icon['clock'].'</a>';

	}

	$tasks_table .= '<tr class="'.$tr_class_status.'">';
	$tasks_table .= '<td>'.$done_btn.'</td>';
	$tasks_table .= '<td>'.$star.$tasks['task_title'].$client.'</td>';
	$tasks_table .= '<td>'.$time_due.$repeat_msg.'</td>';
	$tasks_table .= '<td>'.$task_users.'</td>';
	$tasks_table .= '<td>'.$tasks['task_hourly_wage'].'</td>';
	$tasks_table .= '<td style="width: 150px;"><div class="btn-toolbar" role="group"><div class="btn-group" role="group">'.$edit_btn.$time_btn.'</div><div class="btn-group pull-right" role="group">'.$delete_btn.'</div></div></td>';
	$tasks_table .= '</tr>';
	
}

$tasks_table .= '</table>';


$tpl_layout = str_replace('{tasks_list}', $tasks_table, $tpl_layout);
$tpl_layout = str_replace('{lang_btn_new_task}', $mod_lang['btn_new_task'], $tpl_layout);

$tpl_layout = str_replace('{form_action}', '?tn=moduls&sub=jobsKit.mod&a=tasks', $tpl_layout);
$tpl_layout = str_replace('{csrf_token}', $_SESSION['token'], $tpl_layout);
$tpl_layout = str_replace('{btn_value}', $mod_lang['btn_send'], $tpl_layout);

/* done Tasks */


$tasks_done_array = jk_get_tasks('0','500','NULL','2','NULL');

$cnt_tasks_done = count($tasks_done_array);

$tasks_table = '<table class="table table-sm table-striped">';
$tasks_table .= '<tr>';
$tasks_table .= '<th>'.$mod_lang['label_title'].'</th>';
$tasks_table .= '<th>'.$mod_lang['label_time_due'].'</th>';
$tasks_table .= '<th>'.$mod_lang['label_user'].'</th>';
$tasks_table .= '<th></th>';
$tasks_table .= '</tr>';

foreach($tasks_done_array as $tasks) {
	
	$btn_class_status = '';
	$tr_class_status = '';
	$time_due = date('d.m.Y',$tasks['task_time_due']);
	
	if($tasks['task_time_due'] < time()) {
		$time_due = '<span style="color:red;">'.$time_due.'</span>';
	}
	
	if($tasks['task_status'] == '2') {
		$btn_class_status = 'active';
		$tr_class_status = 'task-done';
	}
	
	if($tasks['task_users'] == 'all') {
		$task_users = '';
	} else {
		$task_users = '';
		$task_users_array = explode(',',$tasks['task_users']);
		foreach($task_users_array as $user) {
			$user_avatar_path = '../'. FC_CONTENT_DIR . '/avatars/' . md5($user) . '.png';
			$task_users .= '<img src="'.$user_avatar_path.'" width="35" height="35" class="rounded-circle" title="'.$user.'"> ';
		}
		
		
	}
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$tasks['task_id'].'" class="btn btn-fc btn-sm">'.$icon['edit'].'</a>';
	$done_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&change_task_status='.$tasks['task_id'].'" class="btn btn-fc btn-sm '.$btn_class_status.'">'.$icon['check'].'</a>';
	$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&timer='.$tasks['task_id'].'" class="btn btn-fc btn-sm">'.$icon['clock'].'</a>';
	$delete_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&del='.$tasks['task_id'].'" class="btn btn-danger btn-sm">'.$icon['trash_alt'].'</a>';
	
	// check if this task has a running timer
	if(in_array($tasks['task_hash'], $my_running_timers)) {
			$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&stimer='.$tasks['task_hash'].'" class="btn btn-success btn-sm">'.$icon['clock'].'</a>';

	}

	$tasks_table .= '<tr class="'.$tr_class_status.'">';
	$tasks_table .= '<td>'.$tasks['task_title'].'</td>';
	$tasks_table .= '<td>'.$time_due.'</td>';
	$tasks_table .= '<td>'.$task_users.'</td>';
	$tasks_table .= '<td style="width: 150px;"><div class="btn-group btn-group-sm" role="group">'.$edit_btn.$done_btn.$time_btn.$delete_btn.'</div></td>';
	$tasks_table .= '</tr>';
	
}

$tasks_table .= '</table>';

$tpl_layout = str_replace('{btn_collapse_tasks_done}', $mod_lang['btn_toggle_done'], $tpl_layout);
$tpl_layout = str_replace('{label_sort}', $mod_lang['label_sort'], $tpl_layout);

$tpl_layout = str_replace('{cnt_done}', $cnt_tasks_done, $tpl_layout);
$tpl_layout = str_replace('{tasks_list_done}', $tasks_table, $tpl_layout);


$tpl_layout = str_replace('{select_user}', $user_list, $tpl_layout);
$tpl_layout = str_replace('{select_projects}', $select_projects, $tpl_layout);
$tpl_layout = str_replace('{select_clients}', $select_clients, $tpl_layout);

echo $tpl_layout;

?>