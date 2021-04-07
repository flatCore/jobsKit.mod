<?php

//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_projects'].'</small></h3>';


// all incoming data -> strip_tags
foreach($_REQUEST as $key => $val) {
	$$key = @strip_tags($val); 
}


/* status */
if($_SESSION['switch_status'] == '') {
	$_SESSION['switch_status'] = '0';
}

if(isset($_REQUEST['switch'])) {
	if($_REQUEST['switch'] == '0') {
		$_SESSION['switch_status'] = '0';
	} else if($_REQUEST['switch'] == '1') {
		$_SESSION['switch_status'] = '1';
	} else if($_REQUEST['switch'] == '2') {
		$_SESSION['switch_status'] = '2';
	}
}

/* change status of project */
if(isset($_GET['doneid'])) {
	jk_change_project_status($_GET['doneid']);
}

/* delete project */
if(isset($_GET['delid'])) {
	jk_delete_project_by_id($_GET['delid']);
}


/* filter */

if(isset($_POST['project_filter'])) {
	$_SESSION['project_filter'] = $_POST['project_filter'];
}

if(isset($_POST['filter_client'])) {
	$_SESSION['filter_client'] = $_POST['filter_client'];
}

$filter_client_id = $_SESSION['filter_client'];


$tpl_layout = file_get_contents($mod_root.'backend/templates/jobs.tpl');




if(empty($jk_alert)) {
	$tpl_layout = str_replace('{alert}', '', $tpl_layout);
} else {
	$tpl_layout = str_replace('{alert}', $jk_alert, $tpl_layout);
}

$clients = jk_get_clients();

$select_clients = '<select class="form-control custom-select" name="filter_client" onchange="this.form.submit()">';
$select_clients .= '<option value="NULL">'.$mod_lang['select_client'].'</option>';
foreach($clients as $client) {
	if($client['client_company'] != '') {
		$client_name = $client['client_company'];
	} else {
		$client_name = $client['client_firstname'].' '.$client['client_lastname'];
	}
	$select_clients .= '<option value="'.$client['client_id'].'" {selected_id_'.$client['client_id'].'}>'.$client['client_nbr'].' | '.$client_name.'</option>';
}
$select_clients .= '</select>';

$select_clients = str_replace("{selected_id_$filter_client_id}", 'selected', $select_clients);




$jobs_start = 0;
$jobs_limit = 25;

if((isset($_GET['jobs_start'])) && is_numeric($_GET['jobs_start'])) {
	$jobs_start = (int) $_GET['jobs_start'];
}

if((isset($_POST['setPage'])) && is_numeric($_POST['setPage'])) {
	$jobs_start = (int) $_POST['setPage'];
}

$projects_array = jk_get_projects($jobs_start,$jobs_limit,$_SESSION['project_filter'],$_SESSION['switch_status'],'','DESC',$filter_client_id);
$cnt_jobs = jk_cnt_jobs();
$cnt_filter_jobs = $projects_array[0]['cnt_jobs'];

$nextPage = $jobs_start+$jobs_limit;
$prevPage = $jobs_start-$jobs_limit;
$cnt_pages = ceil($cnt_filter_jobs / $jobs_limit);

if($prevPage < 0) {
	$prevPage_btn = '<a class="btn btn-fc w-100 disabled" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
} else {
	$prevPage_btn = '<a class="btn btn-fc w-100" href="?tn=moduls&sub=jobsKit.mod&a=jobs&jobs_start='.$prevPage.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
}

if($nextPage < ($cnt_filter_jobs-$jobs_limit)+$jobs_limit) {
	$nextPage_btn = '<a class="btn btn-fc w-100" href="?tn=moduls&sub=jobsKit.mod&a=jobs&jobs_start='.$nextPage.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
} else {
	$nextPage_btn = '<a class="btn btn-fc w-100 disabled" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
}

$pag_form = '<form action="?tn=moduls&sub=jobsKit.mod&a=jobs" method="POST">';
$pag_form .= '<select class="form-control custom-select" name="setPage" onchange="this.form.submit()">';
for($i=0;$i<$cnt_pages;$i++) {
	$x = $i+1;
	$thisPage = ($x*$jobs_limit)-$jobs_limit;
	$sel = '';
	if($thisPage == $jobs_start) {
		$sel = 'selected';
	}
	$pag_form .= '<option value="'.$thisPage.'" '.$sel.'>Seite '.$x.'</option>';
}
$pag_form .= '</select>';
$pag_form .= '</form>';


$projects_table = '<table class="table table-sm table-sm table-striped">';
$projects_table .= '<tr>';
$projects_table .= '<th>'.$mod_lang['label_project_nbr'].'</th>';
$projects_table .= '<th>'.$mod_lang['label_title'].'</th>';
$projects_table .= '<th>'.$mod_lang['label_tasks'].'</th>';
$projects_table .= '<th>'.$mod_lang['label_client'].'</th>';
$projects_table .= '<th>'.$mod_lang['label_time_recorded'].'</th>';
$projects_table .= '<th>'.$mod_lang['label_time_due'].'</th>';
$projects_table .= '<th></th>';
$projects_table .= '</tr>';

foreach($projects_array as $projects) {
	
	$btn_class_status = '';
	$tr_class_status = '';
	$cnt_projects_tasks = 0;
	$time_due = date('d.m.Y',$projects['project_time_due']);
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs_edit&jid='.$projects['project_id'].'" class="btn btn-fc">'.$icon['edit'].'</a>';
	$done_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs&doneid='.$projects['project_id'].'" class="btn btn-fc">'.$icon['check'].'</a>';
	$delete_btn = '<a onclick="return confirm(\''.$mod_lang['confirm_delete_data'].'\')" href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs&delid='.$projects['project_id'].'" class="btn btn-fc text-danger">'.$icon['trash_alt'].'</a>';
	
	if($projects['project_status'] == '2') {
		$tr_class_status = 'project-done';
	}
	if($projects['project_time_due'] < time() && $projects['project_status'] == '1') {
		$time_due = '<span style="color:red;">'.$time_due.'</span>';
	}
	
	$client_name = '';
	foreach($clients as $client) {
		if($client['client_id'] == $projects['project_client']) {
			if($client['client_company'] != '') {
				$client_name = $client['client_company'];
			} else {
				$client_name = $client['client_firstname'].' '.$client['client_lastname'];
			}
		}
	}
	
	/* tasks for this project */
	$projects_tasks = jk_get_tasks_by_job_id($projects['project_id']);
	$cnt_projects_tasks = count($projects_tasks);
	$task_str = '';
	if($cnt_projects_tasks > 0) {

		$cnt_checks = 0;
		foreach($projects_tasks as $task) {
			
			$check = '';
			if($task['task_status'] == 2) {
				$check = '[x] ';
				$cnt_checks++;
			} else {
				$check = '[ ] ';
			}
			
			$task_str .= ''.$check.$task['task_title'].'<br>';
		}
		
		$check_percent = round(($cnt_checks*100)/$cnt_projects_tasks,2).'%';
		
		$task_pop = '<a data-bs-toggle="popover" data-trigger="hover" data-html="true" data-content="'.$task_str.'">'.$cnt_projects_tasks.' ('.$check_percent.')</a>';

		
	} else {
		$task_pop = '0';
	}
	
	
	$projects_table .= '<tr class="'.$tr_class_status.'">';
	$projects_table .= '<td>'.$projects['project_nbr'].'</td>';
	$projects_table .= '<td>'.$projects['project_title'].'</td>';
	$projects_table .= '<td>'.$task_pop.'</td>';
	$projects_table .= '<td>'.$client_name.'</td>';
	$projects_table .= '<td>'.date('d.m.Y',$projects['project_time_recorded']).'</td>';
	if($projects['project_time_due'] != '') {
		$projects_table .= '<td>'.$time_due.'</td>';
	} else {
		$projects_table .= '<td></td>';
	}

	$projects_table .= '<td><div class="btn-group">'.$edit_btn.' '.$done_btn.'</div> '.$delete_btn.'</td>';
	$projects_table .= '</tr>';
}

$projects_table .= '</table>';

$tpl_layout = str_replace('{client_list}', $select_clients, $tpl_layout);
$tpl_layout = str_replace('{jobs_list}', $projects_table, $tpl_layout);
$tpl_layout = str_replace('{lang_btn_new_project}', $mod_lang['btn_new_project'], $tpl_layout);
$tpl_layout = str_replace('{btn_show_all_projects}', $mod_lang['btn_show_all_projects'], $tpl_layout);
$tpl_layout = str_replace('{btn_show_open_projects}', $mod_lang['btn_show_open_projects'], $tpl_layout);
$tpl_layout = str_replace('{btn_show_done_projects}', $mod_lang['btn_show_done_projects'], $tpl_layout);
$tpl_layout = str_replace('{label_filter}', $mod_lang['label_filter'], $tpl_layout);
$tpl_layout = str_replace('{val_filter}', $_SESSION['project_filter'], $tpl_layout);

$tpl_layout = str_replace('{prev_btn}', $prevPage_btn, $tpl_layout);
$tpl_layout = str_replace('{next_btn}', $nextPage_btn, $tpl_layout);
$tpl_layout = str_replace('{select_page}', $pag_form, $tpl_layout);

$tpl_layout = str_replace("{selected_job_status$_SESSION[switch_status]}", 'active', $tpl_layout);

echo $tpl_layout;

?>