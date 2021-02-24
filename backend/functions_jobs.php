<?php

/* jobs functions */

if(!defined('FC_INC_DIR')) {
	die("No access");
}


/**
 * get projects
 * filter - title and text
 * status - all 0 / active 1 / done 2
 */
 
function jk_get_projects($start='0',$nbr='25',$filter='',$status='',$order='',$direction='',$client_id='') {

	global $mod_db;
	
	$start = (int) $start;
	$nbr = (int) $nbr;
	
	$where = "WHERE project_id IS NOT NULL";
	
		
	if($filter != '') {
		$where .= " AND (project_title LIKE '%$filter%' OR project_text LIKE '%$filter%')";
	}
	
	if($order == '') {
		$order = 'project_nbr';
	}

	if($direction == '') {
		$direction = 'DESC';
	}
	
	if($status != '' AND $status != '0') {
		$status = (int) $status;
		$where .= " AND (project_status = $status)";
	}
	
	if(is_numeric($client_id)) {
		$client_id = (int) $client_id;
		$where .= " AND (project_client = $client_id)";
	}
	
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM projects $where ORDER BY $order $direction LIMIT $start, $nbr";

   foreach ($dbh->query($sql) as $row) {
     $entries[] = $row;
   }
   
	$sql_cnt = "SELECT count(*) AS 'A', (SELECT count(*) FROM projects $where) AS 'F'";
	$stat = $dbh->query("$sql_cnt")->fetch(PDO::FETCH_ASSOC);

	 $dbh = null;
	 
	/* number of items that match the filter */
	$entries[0]['cnt_jobs'] = $stat['F'];
	 
	return $entries;

	
}



/**
 * count all jobs from proejects
 */
 
function jk_cnt_jobs() {
	
	global $mod_db;
	global $mod;
	
	if(FC_SOURCE == 'frontend') {
		$mod_db = $mod['database'];
	}
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "
	SELECT count(*) AS 'all',
	(SELECT count(*) FROM projects WHERE project_status = 1 ) AS 'open', 
	(SELECT count(*) FROM projects WHERE project_status = 2 ) AS 'active'
	FROM projects
	";
	$stats = $dbh->query("$sql")->fetch(PDO::FETCH_ASSOC);
	return $stats;
}




/**
 * get last project nbr
 * return project_nbr
 */
 
function jk_get_last_project($mode='id') {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = 'SELECT project_nbr FROM projects ORDER BY project_nbr DESC LIMIT 0, 1';
	$get_project = $dbh->query($sql);
	$get_project = $get_project->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_project['project_nbr'];

}


/**
 * get project by id
 */
 
function jk_get_project_by_id($id) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM projects WHERE project_id = $id";
	$get_project = $dbh->query($sql);
	$get_project = $get_project->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_project;

}

/**
 * get project by hash
 */
 
function jk_get_project_by_hash($hash) {

	global $mod_db;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM projects WHERE project_hash LIKE '$hash' ";
	$get_project = $dbh->query($sql);
	$get_project = $get_project->fetch(PDO::FETCH_ASSOC);
	$dbh = null;

	return $get_project;

}



/**
 * change status of a project
 * open = 1, done = 2
 */

function jk_change_project_status($id) {
	
	global $mod_db;
	$job_id = (int) $id;

	$dbh = new PDO("sqlite:$mod_db");
	
	$sql = "SELECT * FROM projects WHERE project_id = $job_id";	
	$get_job = $dbh->query($sql);
	$get_job = $get_job->fetch(PDO::FETCH_ASSOC);
	
	if($get_job['project_status'] == '1') {
		$new_status = 2;
	} else {
		$new_status = 1;
	}
	
	$sql = "UPDATE projects
				SET project_status = :project_status
				WHERE project_id = :project_id ";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':project_id', $job_id, PDO::PARAM_INT);
	$sth->bindParam(':project_status', $new_status, PDO::PARAM_STR);
	$cnt_changes = $sth->execute();
	$dbh = null;	
	
	
}

/**
 * save or update project
 */
 
function jk_save_projects($data) {
	
	global $mod_db;
	
	if($data['project_entrydate'] == '') {
		$project_time_recorded = time();
	} else {
		$project_time_recorded = strtotime($data['project_entrydate']);
	}
	
	$project_hash = md5($project_time_recorded.$data['project_nbr']);
	$project_status = $_POST['project_status'];
	$project_time_due = strtotime($data['project_due_date']);
	$project_users = @implode(',',$data['project_users']);
	$project_text = $data['project_text'];
	$project_client = $data['project_client'];
	$project_nbr = $data['project_nbr'];
	$project_title = $data['project_title'];
	$project_budget = $data['project_budget'];
	$project_author = $_SESSION['user_nick'];
	
	if($project_users == '') {
		$project_users = 'all';
	}
	
	$images_string = @implode("<->", $data['post_images']);
	$images_string = "<->$images_string<->";

	
	$dbh = new PDO("sqlite:$mod_db");
	
	/**
	 * $_POST['mode'] = new or integer (project_id)
	 */
	
	if($_POST['mode'] == 'new') {
		
		$sql = "INSERT INTO projects (
			project_id, project_hash, project_nbr, project_title, project_time_recorded,
			project_time_due, project_users, project_text, project_client, project_status, project_author, project_budget, project_images
		) VALUES (
			NULL, :project_hash, :project_nbr, :project_title, :project_time_recorded,
			:project_time_due, :project_users, :project_text, :project_client, :project_status, :project_author, :project_budget, :project_images
		)";
		
		$sth = $dbh->prepare($sql);
		
		$sth->bindParam(':project_hash', $project_hash, PDO::PARAM_STR);
		$sth->bindParam(':project_nbr', $project_nbr, PDO::PARAM_INT);
		$sth->bindParam(':project_title', $project_title, PDO::PARAM_STR);
		$sth->bindParam(':project_time_recorded', $project_time_recorded, PDO::PARAM_STR);
		$sth->bindParam(':project_time_due', $project_time_due, PDO::PARAM_STR);
		$sth->bindParam(':project_users', $project_users, PDO::PARAM_STR);
		$sth->bindParam(':project_text', $project_text, PDO::PARAM_STR);
		$sth->bindParam(':project_client', $project_client, PDO::PARAM_STR);
		$sth->bindParam(':project_status', $project_status, PDO::PARAM_STR);
		$sth->bindParam(':project_author', $project_author, PDO::PARAM_STR);
		$sth->bindParam(':project_budget', $project_budget, PDO::PARAM_STR);
		$sth->bindParam(':project_images', $images_string, PDO::PARAM_STR);
		
		$cnt_changes = $sth->execute();
		$last_insert_id = $dbh->lastInsertId();
			
	} else {
		
		$project_id = (int) $data['mode'];
		
		$sql = "UPDATE projects
						SET project_title = :project_title,
						project_text = :project_text,
						project_status = :project_status,
						project_client = :project_client,
						project_users = :project_users,
						project_nbr = :project_nbr,
						project_time_recorded = :project_time_recorded,
						project_time_due = :project_time_due,
						project_budget = :project_budget,
						project_images = :project_images
						WHERE project_id = :project_id ";
		
		$sth = $dbh->prepare($sql);
				
		$sth->bindParam(':project_id', $project_id, PDO::PARAM_INT);
		$sth->bindParam(':project_title', $project_title, PDO::PARAM_STR);
		$sth->bindParam(':project_text', $project_text, PDO::PARAM_STR);
		$sth->bindParam(':project_status', $project_status, PDO::PARAM_STR);
		$sth->bindParam(':project_client', $project_client, PDO::PARAM_STR);
		$sth->bindParam(':project_users', $project_users, PDO::PARAM_STR);
		$sth->bindParam(':project_nbr', $project_nbr, PDO::PARAM_INT);
		$sth->bindParam(':project_time_due', $project_time_due, PDO::PARAM_STR);
		$sth->bindParam(':project_time_recorded', $project_time_recorded, PDO::PARAM_STR);
		$sth->bindParam(':project_budget', $project_budget, PDO::PARAM_STR);
		$sth->bindParam(':project_images', $images_string, PDO::PARAM_STR);
		
		$cnt_changes = $sth->execute();
		$last_insert_id = $project_id;

	}
	
	if($cnt_changes == TRUE){
		$sys_message = '{OKAY} ' . $lang['entry_saved'];
		record_log("$_SESSION[user_nick]","New Project <i>$project_title</i>","0");
	} else {
		$sys_message = '{ERROR} ' . $lang['entry_saved_error'];
	}
	
	$return = array("$last_insert_id","$sys_message");

	return $return;

}




/**
 * delete project by id
 */
 
function jk_delete_project_by_id($id) {

	global $mod_db;
	$dbh = new PDO("sqlite:$mod_db");
	$delete = (int) $id;
	$sql = "DELETE FROM projects WHERE project_id = $delete";
	$cnt_changes = $dbh->exec($sql);
	$dbh = null;
}


 




?>