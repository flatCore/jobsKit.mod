<?php

include $mod_root.'install/installer.php';

//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_dashboard'].'</small></h3>';


// all incoming data -> strip_tags
foreach($_REQUEST as $key => $val) {
	$$key = @strip_tags($val); 
}


$clients = jk_get_clients();
$projects_array = jk_get_projects('0','10','','1','','DESC','');
$tasks_array = jk_get_tasks('0','10','NULL','1','NULL');


/* statistics */

$sql_stat_projects = "
SELECT count(*) AS 'all',
(SELECT count(*) FROM projects WHERE project_status = 1 ) AS 'open', 
(SELECT count(*) FROM projects WHERE project_status = 2 ) AS 'done'
FROM projects
";

$sql_stat_tasks = "
SELECT count(*) AS 'all',
(SELECT count(*) FROM tasks WHERE task_status = 1 ) AS 'open', 
(SELECT count(*) FROM tasks WHERE task_status = 2 ) AS 'done'
FROM tasks
";



$dbh = new PDO("sqlite:$mod_db");
$stat_projects = $dbh->query("$sql_stat_projects")->fetch(PDO::FETCH_ASSOC);
$stat_tasks = $dbh->query("$sql_stat_tasks")->fetch(PDO::FETCH_ASSOC);


$sql_get_logs = "SELECT * FROM activities ORDER BY activity_id DESC";
$get_logs = $dbh->query("$sql_get_logs")->fetchAll(PDO::FETCH_ASSOC);

$log_table = '<table class="table table-sm table-striped">';
foreach($get_logs as $log) {
	$log_table .= '<tr>';
	$log_table .= '<td><small>'.date('d.m. H:i:s',$log['activity_time']).'<br>'.$log['activity_user'].'</small></td>';
	$log_table .= '<td><small>'.$log['activity_text'].'</small></td>';
	$log_table .= '<td><small>'.$log['activity_module'].'</small></td>';
	$log_table .= '</tr>';
}

$log_table .= '</table>';


$tpl = file_get_contents($mod_root.'backend/templates/dashboard.tpl');

$tpl = str_replace('{log_table}', $log_table, $tpl);

$tpl = str_replace('{cnt_active_projects}', $stat_projects['open'], $tpl);
$tpl = str_replace('{cnt_done_projects}', $stat_projects['done'], $tpl);
$tpl = str_replace('{cnt_all_projects}', $stat_projects['all'], $tpl);

$tpl = str_replace('{cnt_active_tasks}', $stat_tasks['open'], $tpl);
$tpl = str_replace('{cnt_done_tasks}', $stat_tasks['done'], $tpl);
$tpl = str_replace('{cnt_all_tasks}', $stat_tasks['all'], $tpl);

$tpl = str_replace('{lang_btn_new_task}', $mod_lang['btn_new_task'], $tpl);
$tpl = str_replace('{lang_btn_new_project}', $mod_lang['btn_new_project'], $tpl);


$btn_add_task = '<a href="?tn=moduls&sub=jobsKit.mod&a=tasks_edit" class="btn btn-success btn-sm float-right">'.$icon['plus'].'</a>';
$tpl = str_replace('{btn_add_task}', $btn_add_task, $tpl);

$tasks_table = '<table class="table table-sm">';
foreach($tasks_array as $tasks) {
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$tasks['task_id'].'" class="btn btn-fc">'.$icon['edit'].'</a>';
	
	$tasks_table .= '<tr>';
	$tasks_table .= '<td>'.$tasks['task_title'].'</td>';
	$tasks_table .= '<td class="text-right">'.$edit_btn.'</td>';
	$tasks_table .= '</tr>';
}
$tasks_table .= '</table>';

$tpl = str_replace('{tasks_table}', $tasks_table, $tpl);


$btn_add_job = '<a href="?tn=moduls&sub=jobsKit.mod&a=jobs_edit" class="btn btn-success btn-sm float-right">'.$icon['plus'].'</a>';
$tpl = str_replace('{btn_add_job}', $btn_add_job, $tpl);

$jobs_table = '<table class="table table-sm">';
foreach($projects_array as $job) {
	
	$client_name = '';
	foreach($clients as $client) {
		if($client['client_id'] == $job['project_client']) {
			if($client['client_company'] != '') {
				$client_name = $client['client_company'];
			} else {
				$client_name = $client['client_firstname'].' '.$client['client_lastname'];
			}
		}
	}
	
	$edit_btn = '<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs_edit&jid='.$job['project_id'].'" class="btn btn-fc">'.$icon['edit'].'</a>';
	
	$jobs_table .= '<tr>';
	$jobs_table .= '<td>'.$job['project_nbr'].'</td>';
	$jobs_table .= '<td>'.$job['project_title'].'</td>';
	$jobs_table .= '<td>'.$client_name.'</td>';
	$jobs_table .= '<td class="text-right">'.$edit_btn.'</td>';
	$jobs_table .= '</tr>';
}
$jobs_table .= '</table>';

$tpl = str_replace('{jobs_table}', $jobs_table, $tpl);



echo $tpl;


?>