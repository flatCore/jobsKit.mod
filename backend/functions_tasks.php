<?php
//error_reporting(E_ALL ^E_NOTICE);


/* task and timer functions */


if(!defined('FC_INC_DIR')) {
	die("No access");
}


/**
 * save or update task
 */

function jk_save_task($data) {
	
	global $mod_db;
	global $mod_lang;

	$task_time_recorded = time();
	$task_hash = md5($task_time_recorded.$data['task_title']);
	$task_status = $data['task_status'];
	
	if(ctype_digit($data['task_time_due'])) {
		$task_time_due = $data['task_time_due'];
	} else {
		$task_time_due = strtotime($data['task_time_due']);
	}
	
	$task_users = '';
	$task_users = @implode(',',$data['task_users']);
	$task_text = $data['task_text'];
	$task_client = $data['task_client'];
	$task_title = $data['task_title'];
	$task_project_hash = $data['task_project_hash'];
	$task_author = $_SESSION['user_nick'];
	
	$poject = jk_get_project_by_id($data['task_project_id']);
	if($poject['project_client'] != '') {
		$task_client = $poject['project_client'];
	}
	
	if($task_users == '') {
		$task_users = 'all';
	}
	
	$dbh = new PDO("sqlite:$mod_db");
	
	/**
	 * $_POST['mode'] = new or integer (task_id)
	 */
	
	if($data['mode'] == 'new') {
		
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit';

		$sql = "INSERT INTO tasks (
			task_id, task_hash, task_author, task_client, task_project_id, task_title,
			task_project_hash, task_time_recorded, task_time_due, task_users, task_notes, task_status,
			task_repeat, task_priority, task_hourly_wage
		) VALUES (
			NULL, :task_hash, :task_author, :task_client, :task_project_id, :task_title,
			:task_project_hash, :task_time_recorded, :task_time_due, :task_users, :task_notes, :task_status,
			:task_repeat, :task_priority, :task_hourly_wage
		)";
		
		try {
			$sth = $dbh->prepare($sql);
			
			$sth->bindParam(':task_hash', $task_hash, PDO::PARAM_STR);
			$sth->bindParam(':task_author', $task_author, PDO::PARAM_STR);
			$sth->bindParam(':task_client', $task_client, PDO::PARAM_STR);
			$sth->bindParam(':task_project_id', $task_project_id, PDO::PARAM_STR);
			$sth->bindParam(':task_project_hash', $task_project_hash, PDO::PARAM_STR);
			$sth->bindParam(':task_time_recorded', $task_time_recorded, PDO::PARAM_STR);
			$sth->bindParam(':task_time_due', $task_time_due, PDO::PARAM_STR);
			$sth->bindParam(':task_users', $task_users, PDO::PARAM_STR);
			$sth->bindParam(':task_status', $task_status, PDO::PARAM_STR);
			$sth->bindParam(':task_title', $task_title, PDO::PARAM_STR);
			$sth->bindParam(':task_notes', $task_text, PDO::PARAM_STR);
			$sth->bindParam(':task_priority', $data['task_priority'], PDO::PARAM_STR);
			$sth->bindParam(':task_repeat', $data['task_repeat'], PDO::PARAM_STR);
			$sth->bindParam(':task_project_id', $data['task_project_id'], PDO::PARAM_STR);
			$sth->bindParam(':task_hourly_wage', $data['task_hourly_wage'], PDO::PARAM_STR);
			
		} catch(PDOException $e) {
			 $message = $e->getMessage();
			 print_r($message);
		}
			
	} else {
		
		$task_id = (int) $data['mode'];
		$form_action = 'acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$task_id;
		
		$sql = "UPDATE tasks
						SET task_title = :task_title,
						task_notes = :task_notes,
						task_status = :task_status,
						task_client = :task_client,
						task_time_due = :task_time_due,
						task_repeat = :task_repeat,
						task_project_id = :task_project_id,
						task_project_hash = :task_project_hash,
						task_users = :task_users,
						task_priority = :task_priority,
						task_hourly_wage = :task_hourly_wage
						WHERE task_id = :task_id ";
		
		$sth = $dbh->prepare($sql);
				
		$sth->bindParam(':task_id', $task_id, PDO::PARAM_INT);
		$sth->bindParam(':task_title', $task_title, PDO::PARAM_STR);
		$sth->bindParam(':task_notes', $task_text, PDO::PARAM_STR);
		$sth->bindParam(':task_status', $task_status, PDO::PARAM_STR);
		$sth->bindParam(':task_client', $task_client, PDO::PARAM_STR);
		$sth->bindParam(':task_time_due', $task_time_due, PDO::PARAM_STR);
		$sth->bindParam(':task_repeat', $data['task_repeat'], PDO::PARAM_STR);
		$sth->bindParam(':task_project_id', $data['task_project_id'], PDO::PARAM_STR);
		$sth->bindParam(':task_project_hash', $task_project_hash, PDO::PARAM_STR);
		$sth->bindParam(':task_users', $task_users, PDO::PARAM_STR);
		$sth->bindParam(':task_priority', $data['task_priority'], PDO::PARAM_STR);
		$sth->bindParam(':task_hourly_wage', $data['task_hourly_wage'], PDO::PARAM_STR);
		
	}
	
	$cnt_changes = $sth->execute();
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $mod_lang['msg_task_saved'];
		jk_record_log('update tasks','tasks');
	} else {
		$sys_message = '{ERROR} ' . $mod_lang['msg_task_saved_error'];
	}
	
	return $sys_message;
	
}









/**
 * get tasks
 */
 
function jk_get_tasks($start='0',$nbr='100',$filter='',$status='',$order='') {
	
	global $mod_db;
	global $clients_array;
	$start = (int) $start;
	$nbr = (int) $nbr;
	
	$where = "WHERE task_id IS NOT NULL";

	
	if(is_numeric($_SESSION['task_client_id'])) {
		$where .= ' AND (task_client = :task_client)';
		$client_id = $_SESSION['task_client_id'];
	}
	
	if(is_numeric($_SESSION['task_project_id'])) {
		$where .= ' AND (task_project_id = :task_project_id)';
		$project_id = $_SESSION['task_project_id'];
	}
	
	if(stripos($_SESSION['task_users'], 'NULL') === false) {
		if($_SESSION['task_users'] != '') {
			$users = explode(',', $_SESSION['task_users']);
			$where .= ' AND (';
			foreach($users as $user) {
				$where .= "task_users LIKE '$user' OR ";
			}
			$where = substr($where, 0,-3);
			$where .= ')';
		}
	}
	
	
	if($order == 'NULL') {
		$order = 'task_id';
	}
	
	if($_SESSION['sort'] == 'date') {
		$order = 'task_time_due';
	} else if($_SESSION['sort'] == 'user') {
		$order = 'task_users';
	} else if($_SESSION['sort'] == 'priority') {
		$order = 'task_priority';
	}

	if($_SESSION['dir'] == 'ASC') {
		$direction = 'ASC';
	} else {
		$direction = 'DESC';
	}
	
	if($status != 'NULL') {
		$status = (int) $status;
		$where .= " AND (task_status = $status)";
	}	

	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM tasks $where ORDER BY $order $direction LIMIT $start, $nbr";
	$sth = $dbh->prepare($sql);

	if(is_numeric($_SESSION['task_client_id'])) {
		$sth->bindParam(':task_client', $_SESSION['task_client_id'], PDO::PARAM_STR);
	}
	if(is_numeric($_SESSION['task_project_id'])) {
		$sth->bindParam(':task_project_id', $_SESSION['task_project_id'], PDO::PARAM_STR);
	}

	$sth->execute();
	$entries = $sth->fetchAll(PDO::FETCH_ASSOC);

	$dbh = null;
	 
	 
	 /* we add the real client name to the array */
	 $x = 0;
	 foreach($entries as $tasks) {
		 $client_name = '';
		 if($tasks['task_client'] != '') {
			 $client_id = $tasks['task_client'];
			 $client_name = $clients_array[$client_id][0]['client_company'];
			 if($client_name == '') {
			 		$client_name = $clients_array[$client_id][0]['client_firstname'] .' '. $clients_array[$client_id][0]['client_lastname'];
				}
				$entries[$x]['task_client_name'] = $client_name;
				
		 } else {
			 $entries[$x]['task_client_name'] = '';
		 }
		
		$x++; 
	 }
	 
	 /* add the real job title an number */
	 $x = 0;
	 foreach($entries as $tasks) {
		 if($tasks['task_project_id'] != '') {
			 $poject = jk_get_project_by_id($tasks['task_project_id']);
			 $entries[$x]['task_project_title'] = $poject['project_title'];
			 $entries[$x]['task_project_nbr'] = $poject['project_nbr'];
		 }
		 $x++;
	 }
	 
	 
	 return $entries;

}


/**
 * get task by id
 */
 
function jk_get_task_by_id($task_id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM tasks WHERE task_id = $task_id";
	$get_task = $dbh->query($sql);
	$get_task = $get_task->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_task;

}

/**
 * get task by hash
 */
 
function jk_get_task_by_hash($hash) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM tasks WHERE task_hash LIKE '$hash' ";
	$get_task = $dbh->query($sql);
	$get_task = $get_task->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_task;

}


/**
 * get tasks by job id
 */
 
function jk_get_tasks_by_job_id($id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM tasks WHERE task_project_id = $id ";
	$get_task = $dbh->query($sql);
	$get_task = $get_task->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_task;

}



/**
 * delete task by id
 */
 
function jk_delete_task_by_id($task_id) {

	global $mod_db;
	$dbh = new PDO("sqlite:$mod_db");
	$delete = (int) $task_id;
	$sql = "DELETE FROM tasks WHERE task_id = $delete";
	$cnt_changes = $dbh->exec($sql);
	$dbh = null;
}


/**
 * change status of a task
 * open = 1, done = 2
 * if task_repeat != NULL repeat it
 */

function jk_change_status($id) {
	
	global $mod_db;
	$task_id = (int) $id;

	$dbh = new PDO("sqlite:$mod_db");
	
	$sql = "SELECT * FROM tasks WHERE task_id = $task_id";	
	$get_task = $dbh->query($sql);
	$get_task = $get_task->fetch(PDO::FETCH_ASSOC);
	
	if($get_task['task_status'] == '1') {
		$new_status = 2;
	} else {
		$new_status = 1;
	}
	
	$get_task['task_users'] = @explode(',',$get_task['task_users']);
	
	/* duplicate if there is a repeat */
	if($get_task['task_repeat'] == 'daily' && $get_task['task_status'] == '1') {
		$get_task['mode'] = 'new';
		$get_task['task_time_due'] = strtotime('+1 day', $get_task['task_time_due']);
		jk_save_task($get_task);
	}
	if($get_task['task_repeat'] == 'weekly' && $get_task['task_status'] == '1') {
		$get_task['mode'] = 'new';
		$get_task['task_time_due'] = strtotime('+7 days', $get_task['task_time_due']);
		jk_save_task($get_task);
	}
	if($get_task['task_repeat'] == 'monthly' && $get_task['task_status'] == '1') {
		$get_task['mode'] = 'new';
		$get_task['task_time_due'] = strtotime('+1 month', $get_task['task_time_due']);
		jk_save_task($get_task);
	}
	if($get_task['task_repeat'] == 'yearly' && $get_task['task_status'] == '1') {
		$get_task['mode'] = 'new';
		$get_task['task_time_due'] = strtotime('+1 year', $get_task['task_time_due']);
		jk_save_task($get_task);
	}
		
	$sql = "UPDATE tasks
				SET task_status = :task_status
				WHERE task_id = :task_id ";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':task_id', $task_id, PDO::PARAM_INT);
	$sth->bindParam(':task_status', $new_status, PDO::PARAM_STR);
	$cnt_changes = $sth->execute();
	$dbh = null;
	
}



/**
 * get timer by id
 */
 
function jk_get_timer_by_id($timer_id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM timers WHERE timer_id = $timer_id";
	$get_timer = $dbh->query($sql);
	$get_timer = $get_timer->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_timer;

}


 

/**
 * get timers
 * filter by time, tasks, projects and clients
 */

function jk_get_timers($start='',$end='',$filter='',$task='',$project='',$client='') {

	global $mod_db;

	$now = time();
	$first_day_of_month = strtotime( 'first day of ' . date( 'F Y'));
	$last_day_of_month = strtotime( 'last day of ' . date( 'F Y'));
	
	$where = "WHERE timer_id IS NOT NULL";
	
	if($start == '') {
		$where .= ' AND (timer_start > :timer_start AND timer_end < :timer_end)';
	}
	
	if(is_numeric($_SESSION['report_client_id'])) {
		$where .= ' AND (timer_client = :report_client_id)';
		$client_id = $_SESSION['report_client_id'];
	} else {
		$client_id = '';
		$where .= ' AND (timer_client != :report_client_id)';
	}
	
	if(is_numeric($_SESSION['report_project_id'])) {
		$where .= ' AND (timer_project_id = :timer_project_id)';
		$project_id = $_SESSION['report_project_id'];
	} else {
		$project_id = '';
		$where .= ' AND (timer_project_id IS NOT :timer_project_id)';
	}
	
	if($_SESSION['report_users'] != '') {
		$users = explode(',', $_SESSION['report_users']);
		$where .= ' AND (';
		foreach($users as $user) {
			$where .= "timer_user LIKE '$user' OR ";
		}
		$where = substr($where, 0,-3);
		$where .= ')';
	}
	
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM timers $where ORDER BY timer_id ASC";
	$sth = $dbh->prepare($sql);

	$sth->bindParam(':timer_start', $_SESSION['report_start_time'], PDO::PARAM_STR);
	$sth->bindParam(':timer_end', $_SESSION['report_end_time'], PDO::PARAM_STR);
	$sth->bindParam(':report_client_id', $client_id, PDO::PARAM_STR);
	$sth->bindParam(':timer_project_id', $project_id, PDO::PARAM_STR);

	$sth->execute();
	$timer_data = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	return $timer_data;
	
}



/**
 * start new timer
 * if there is another running timer from this user, stop it
 */
 
function jk_start_timer($task_id) {
	
	global $mod_db;

	jk_stop_my_timers();
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM tasks WHERE task_id LIKE :task_id";	
	$sth = $dbh->prepare($sql);
	$sth->bindValue(':task_id', $task_id, PDO::PARAM_STR);
	$sth->execute();
	$task = $sth->fetch(PDO::FETCH_ASSOC);
	
	$timer_user = $_SESSION['user_nick'];
	$timer_task_hash = $task['task_hash'];
	$timer_task_client = $task['task_client'];
	$timer_project_id = $task['task_project_id'];
	$timer_project_hash = $task['task_project_hash'];
	$timer_start = time();
	$timer_hash = md5($timer_start.$timer_user);
	$timer_status = 'running';
	
	$sql_insert = "INSERT INTO timers (
		timer_id, timer_hash, timer_task_hash, timer_project_id, timer_project_hash, timer_start, timer_user, timer_status, timer_client
	) VALUES (
		NULL, :timer_hash, :timer_task_hash, :timer_project_id, :timer_project_hash, :timer_start, :timer_user, :timer_status, :timer_client
	)";
	
	$sth = $dbh->prepare($sql_insert);
	$sth->bindParam(':timer_hash', $timer_hash, PDO::PARAM_STR);
	$sth->bindParam(':timer_task_hash', $timer_task_hash, PDO::PARAM_STR);
	$sth->bindParam(':timer_project_hash', $timer_project_hash, PDO::PARAM_STR);
	$sth->bindParam(':timer_project_id', $timer_project_id, PDO::PARAM_STR);
	$sth->bindParam(':timer_start', $timer_start, PDO::PARAM_STR);
	$sth->bindParam(':timer_user', $timer_user, PDO::PARAM_STR);
	$sth->bindParam(':timer_client', $timer_task_client, PDO::PARAM_STR);
	$sth->bindParam(':timer_status', $timer_status, PDO::PARAM_STR);
	
	$cnt_changes = $sth->execute();

	if($cnt_changes == TRUE) {
		$jk_system_message = '<div class="alert alert-success alert-auto-close">Timer wurde gestartet</div>';
	} else {
		$jk_system_message = '<div class="alert alert-danger alert-auto-close">ERROR</div>';
	}
	
	return $jk_system_message;
		
}

/**
 * stop all my timers
 */

function jk_stop_my_timers() {
	
	global $mod_db;
	$time = time();
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql_update = "UPDATE timers SET timer_end = :timer_end, timer_status = NULL WHERE (timer_user = :timer_user AND timer_status LIKE 'running') ";
	$sth = $dbh->prepare($sql_update);
	$sth->bindValue(':timer_end', $time, PDO::PARAM_STR);
	$sth->bindValue(':timer_user', $_SESSION['user_nick'], PDO::PARAM_STR);
	$sth->execute();
	$dbh = null;
	
}


/**
 * stop timers by timer_task_hash
 */

function jk_stop_timer($timer_task_hash) {
	
	global $mod_db;
	$time = time();
	
	$dbh = new PDO("sqlite:$mod_db");
	
	$sql_get_id = "SELECT * FROM timers WHERE (timer_user = :timer_user AND timer_status LIKE 'running' AND timer_task_hash = :timer_task_hash)";
	$stmt = $dbh->prepare($sql_get_id);
	$stmt->bindValue(':timer_task_hash', $timer_task_hash, PDO::PARAM_STR);
	$stmt->bindValue(':timer_user', $_SESSION['user_nick'], PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$timer_id = $row['timer_id'];
	
	$sql_update = "UPDATE timers SET timer_end = :timer_end, timer_status = NULL WHERE (timer_user = :timer_user AND timer_status LIKE 'running' AND timer_task_hash = :timer_task_hash) ";
	$sth = $dbh->prepare($sql_update);
	$sth->bindValue(':timer_end', $time, PDO::PARAM_STR);
	$sth->bindValue(':timer_task_hash', $timer_task_hash, PDO::PARAM_STR);
	$sth->bindValue(':timer_user', $_SESSION['user_nick'], PDO::PARAM_STR);
	$sth->execute();
	$dbh = null;
		
	return $timer_id;
	
}

/**
 * save or update timer
 */

function jk_save_timer($data) {
	
	global $mod_db;
	global $mod_lang;

	$data['timer_start'] = str_replace(' Uhr', '', $data['timer_start']);
	$data['timer_end'] = str_replace(' Uhr', '', $data['timer_end']);
	$timer_start = strtotime($data['timer_start']);
	$timer_end = strtotime($data['timer_end']);


	$dbh = new PDO("sqlite:$mod_db");
	
	/**
	 * $_POST['mode'] = new or integer (task_id)
	 */
	
	if($data['mode'] == 'new') {
		
		$sql = "INSERT INTO timers (
			timer_id, timer_hash, timer_task_hash, timer_project_id, timer_start,
			timer_end, timer_user, timer_client, timer_notes
		) VALUES (
			NULL, :timer_hash, :timer_task_hash, :timer_project_id, :timer_start,
			:timer_end, :timer_user, :timer_client, :timer_notes
		)";
		
		try {
			$sth = $dbh->prepare($sql);
			
		$sth->bindParam(':timer_start', $timer_start, PDO::PARAM_STR);
		$sth->bindParam(':timer_end', $timer_end, PDO::PARAM_STR);
		$sth->bindParam(':timer_notes', $data['timer_notes'], PDO::PARAM_STR);
		$sth->bindParam(':timer_task_hash', $data['timer_task_hash'], PDO::PARAM_STR);
		$sth->bindParam(':timer_client', $data['timer_client'], PDO::PARAM_STR);
		$sth->bindParam(':timer_project_id', $data['timer_project_id'], PDO::PARAM_STR);
		$sth->bindParam(':timer_user', $data['timer_user'], PDO::PARAM_STR);
			
		} catch(PDOException $e) {
			 $message = $e->getMessage();
			 print_r($message);
		}
			
	} else {
		
		$timer_id = (int) $data['mode'];
		
		$sql = "UPDATE timers
						SET timer_start = :timer_start,
						timer_end = :timer_end,
						timer_notes = :timer_notes,
						timer_task_hash = :timer_task_hash,
						timer_client = :timer_client,
						timer_project_id = :timer_project_id,
						timer_user = :timer_user
						WHERE timer_id = :timer_id ";
		
		$sth = $dbh->prepare($sql);
				
		$sth->bindParam(':timer_id', $timer_id, PDO::PARAM_INT);
		$sth->bindParam(':timer_start', $timer_start, PDO::PARAM_STR);
		$sth->bindParam(':timer_end', $timer_end, PDO::PARAM_STR);
		$sth->bindParam(':timer_notes', $data['timer_notes'], PDO::PARAM_STR);
		$sth->bindParam(':timer_task_hash', $data['timer_task_hash'], PDO::PARAM_STR);
		$sth->bindParam(':timer_client', $data['timer_client'], PDO::PARAM_STR);
		$sth->bindParam(':timer_project_id', $data['timer_project_id'], PDO::PARAM_STR);
		$sth->bindParam(':timer_user', $data['timer_user'], PDO::PARAM_STR);
	}
	
	$cnt_changes = $sth->execute();
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $mod_lang['msg_task_saved'];
		record_log("$_SESSION[user_nick]","New Timer <i>$timer_title</i>","0");
	} else {
		$sys_message = '{ERROR} ' . $mod_lang['msg_task_saved_error'];
	}
	
	
	return $sys_message;
	
}

/**
 * add a note to stored timer
 * identify by timer_id
 */
 
function save_note_to_timer($timer_id,$text) {
	
	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "UPDATE timers
						SET timer_notes = :timer_notes
						WHERE timer_id = :timer_id ";	
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':timer_notes', $text, PDO::PARAM_STR);
	$sth->bindParam(':timer_id', $timer_id, PDO::PARAM_INT);
	$cnt_changes = $sth->execute();
	
}




/**
 * get all running timers by $_SESSION['user_nick'] and status = 'running'
 *
 * @return array timer_id, timer_task_hash
 */
 
function jk_get_my_running_timers() {

	global $mod_db;
	$timer_data = array();

	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT timer_id, timer_task_hash FROM timers WHERE timer_user = :timer_user AND timer_status = 'running' ";
	$sth = $dbh->prepare($sql);
	$sth->bindValue(':timer_user', $_SESSION['user_nick'], PDO::PARAM_STR);
	$sth->execute();
	$timer_data = $sth->fetch(PDO::FETCH_ASSOC);
	
	if(is_array($timer_data)) {
		return $timer_data;
	}
}


/**
 * delete timer by id
 */
 
function jk_delete_timer_by_id($timer_id) {

	global $mod_db;
	global $mod_lang;
	
	$dbh = new PDO("sqlite:$mod_db");
	$delete = (int) $timer_id;
	$sql = "DELETE FROM timers WHERE timer_id = $delete";
	$cnt_changes = $dbh->exec($sql);
	$dbh = null;
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $mod_lang['msg_timer_deleted'];
		record_log("$_SESSION[user_nick]","Timer deleted <i>$timer_id</i>","0");
	} else {
		$sys_message = '{ERROR} ' . $mod_lang['msg_database_error'];
	}
	
	return $sys_message;
}




?>