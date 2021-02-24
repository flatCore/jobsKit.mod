<?php
	
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
	$dir_icon = '<span class="glyphicon glyphicon-chevron-up"></span>';
} else {
	$dir_icon = '<span class="glyphicon glyphicon-chevron-down"></span>';
}


$btn_filter_date = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&sort=date&dir='.$dir.'" class="list-group-item '.$class_date.'">'.$mod_lang['btn_sort_date'].'</a>';
$btn_filter_user = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&sort=user&dir='.$dir.'" class="list-group-item '.$class_user.'">'.$mod_lang['btn_sort_user'].'</a>';
$btn_filter_priority = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&sort=priority&dir='.$dir.'" class="list-group-item '.$class_priority.'">'.$mod_lang['btn_sort_priority'].'</a>';


$tasks_array = jk_get_tasks('0','500','NULL','1','NULL');

$tasks_table = '';

foreach($tasks_array as $tasks) {
	
	$btn_class_status = '';
	$tr_class_status = '';
	$time_due = '';
	if($tasks['task_time_due'] != '') {
		$time_due = date('d.m.Y',$tasks['task_time_due']);
	}
	
	
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
			$task_users .= '<img src="'.$user_avatar_path.'" width="25" height="25" class="rounded-circle" title="'.$user.'"> ';
		}
		
		
	}
	
	$repeat_msg = '';
	if($tasks['task_repeat'] != 'never') {
		$msg = $mod_lang["label_repeat_$tasks[task_repeat]"];
		$repeat_msg = '<p class="small text-muted" style="white-space: nowrap;"><span class="glyphicon glyphicon-repeat"></span> '.$msg.'</p>';
	}
	
	$star = '';
	if($tasks['task_priority'] == '1') {
		$star = '<span class="glyphicon glyphicon-star text-primary"></span> ';
	}
	
	$client = '';
	if($tasks['task_client'] != '') {
		$client_id = $tasks['task_client'];
		$client_name = $clients_array[$client_id][0]['client_company'];
		if($client_name == '') {
			$client_name = $clients_array[$client_id][0]['client_firstname'] .' '. $clients_array[$client_id][0]['client_lastname'];
		}
		
		$client = '<p class="small text-muted">'.$client_name.'</p>';
	}
	
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$tasks['task_id'].'" class="btn btn-dark"><span class="glyphicon glyphicon-pencil"></span></a>';
	$done_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&change_task_status='.$tasks['task_id'].'" class="btn btn-dark btn-sm '.$btn_class_status.'"><span class="glyphicon glyphicon-ok"></span></a>';
	$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&timer='.$tasks['task_id'].'" class="btn btn-dark btn-sm"><span class="glyphicon glyphicon-time"></span></a>';
	$delete_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&del='.$tasks['task_id'].'" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';
	
	// check if this task has a running timer
	if(in_array($tasks['task_hash'], $my_running_timers)) {
			$time_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks&stimer='.$tasks['task_hash'].'" class="btn btn-success"><span class="glyphicon glyphicon-time"></span></a>';

	}

	$tasks_table .= '<div class="well well-sm" style="margin-bottom:3px;">';
	
	$tasks_table .= '<div class="row">';
	$tasks_table .= '<div class="col-xs-1">'.$done_btn.'</div>';
	$tasks_table .= '<div class="col-xs-10">';
	$tasks_table .= '<a class="" data-toggle="collapse" href="#collapseTask'.$tasks['task_id'].'" style="display:block;">';
	$tasks_table .= '<h5>'.$star.$tasks['task_title'].$client.'</h5>';
	$tasks_table .= '<span class="pull-right">'.$task_users.'</span>';
	$tasks_table .= '<p>'.$time_due.$repeat_msg.'</p>';
	$tasks_table .= '</a>';
	$tasks_table .= '</div>';
	$tasks_table .= '<div class="col-xs-1">';
	$tasks_table .= ''.$time_btn.'';
	$tasks_table .= '</div>';
	
	
	$tasks_table .= '</div>'; // row
	
	
	$tasks_table .= '<div class="collapse" id="collapseTask'.$tasks['task_id'].'">';
	$tasks_table .= '<p>'.$edit_btn.'</p>';
	$tasks_table .= '</div>';
	
	$tasks_table .= '</div>'; // well
	
}




$tpl_layout = file_get_contents('modules/jobsKit.mod/styles/default/tasks.tpl');

$tpl_layout = str_replace('{tasks_list}', $tasks_table, $tpl_layout);
$tpl_layout = str_replace('{lang_btn_new_task}', $mod_lang['btn_new_task'], $tpl_layout);

$return_tasks = $tpl_layout;

?>