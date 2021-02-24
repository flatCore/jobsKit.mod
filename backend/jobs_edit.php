<?php
	
//error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

if(!isset($_GET['jid'])) {
	echo '<h3>jobsKit <small>Auftrag erstellen</small></h3>';
} else {
	echo '<h3>jobsKit <small>Auftrag bearbeiten</small></h3>';
}

$get_job_id = '';


if(isset($_POST['submitProject'])) {

	$save_project = jk_save_projects($_POST);
	
	if(is_numeric($save_project[0])) {
		$get_job_id = (int) $save_project[0];
	}

}




/* client list */

$clients = jk_get_clients();
$select_clients = '<select class="form-control custom-select" name="project_client">';
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
	$user_list .= '<label><input type="checkbox" name="project_users[]" value="'.$admins['user_nick'].'" {select_'.$admins['user_nick'].'}> '.$admins['user_nick'].'</label>';
	$user_list .= '</div>';
}



$project_title = '';
$project_text = '';
$project_nbr = '';
$btn_save = $lang['save'];
$tpl_form = file_get_contents($mod_root.'backend/templates/jobs-form.tpl');

if(isset($_GET['jid'])) {
	$get_job_id = (int) $_GET['jid'];
}

if(is_int($get_job_id)) {
	

	$project_id = $get_job_id;	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM projects WHERE project_id = $project_id";
	$get_project = $dbh->query($sql);
	$get_project = $get_project->fetch(PDO::FETCH_ASSOC);
	$dbh = null;
	
	
	$project_title = $get_project['project_title'];
	$project_text = $get_project['project_text'];
	$project_nbr = $get_project['project_nbr'];
	$project_client_id = $get_project['project_client'];	
	$select_clients = str_replace("{selected_id_$project_client_id}", 'selected', $select_clients);
	$project_budget = $get_project['project_budget'];
	
	if($get_project['project_time_due'] != ''){
		$time_due = date('d.m.Y',$get_project['project_time_due']);
	}
	if($get_project['project_time_recorded'] != ''){
		$project_time_recorded = date('d.m.Y',$get_project['project_time_recorded']);
	}	
	

	$project_users = explode(',',$get_project['project_users']);
	foreach($project_users as $user) {
		$user_list = str_replace("{select_$user}", 'checked', $user_list);
	}

	if($get_project['project_status'] == '1') {
		$tpl_form = str_replace('{checked_project_open}', 'checked', $tpl_form);
		$tpl_form = str_replace('{class_open_active}', 'active', $tpl_form);
	} else {
		$tpl_form = str_replace('{checked_project_done}', 'checked', $tpl_form);
		$tpl_form = str_replace('{class_done_active}', 'active', $tpl_form);
	}
	
	
	/* tasks for this project */
	$projects_tasks = jk_get_tasks_by_job_id($get_project['project_id']);
	$cnt_projects_tasks = count($projects_tasks);
	$task_str = '';
	if($cnt_projects_tasks > 0) {

		$cnt_checks = 0;
		$task_table = '<table class="table table-sm">';
		foreach($projects_tasks as $task) {
			$task_table .= '<tr>';
			$check = '';
			if($task['task_status'] == 2) {
				$check = $icon['check_circle'];
				$cnt_checks++;
			} else {
				$check = $icon['dot_circle'];
			}
			
			$task_table .= '<td>'.$check.'</td><td>'.$task['task_title'].'<br>'.$task['task_notes'].'</td>';
			$task_table .= '<td class="text-right"><a class="btn btn-sm btn-fc" href="?tn=moduls&sub=jobsKit.mod&a=tasks_edit&tid='.$task['task_id'].'">'.$icon['edit'].'</a></td>';
			$task_table .= '<tr>';
		}
		$task_table .= '</table>';
		
		
		$task_list = $task_table;

		
	} else {
		$task_list = '';
	}
	
	$tpl_form = str_replace('{tasks_list}', $task_list, $tpl_form);
	
	$btn_save = $lang['update'];
	$tpl_form = str_replace('{mode}', $project_id, $tpl_form);

	
} else {
	$tpl_form = str_replace('{mode}', 'new', $tpl_form);
	$tpl_form = str_replace('{tasks_list}', '', $tpl_form);
	$tpl_form = str_replace('{checked_project_open}', 'checked', $tpl_form);
	$tpl_form = str_replace('{class_open_active}', 'active', $tpl_form);
	$project_nbr = jk_get_last_project();
	$project_nbr = $project_nbr+1;
	$project_time_recorded = date('Y-m-d H:i',time());
}



/* image select */

$images = fc_scandir_rec('../'.FC_CONTENT_DIR.'/images');

foreach($images as $img) {
	$filemtime = date ("Y", filemtime("$img"));
	$all_images[] = array('name' => $img, 'dateY' => $filemtime);
}

foreach ($all_images as $key => $row) {
	$date[$key]  = $row['dateY'];
	$name[$key] = $row['name'];
}

array_multisort($date, SORT_DESC, $name, SORT_ASC, $all_images);
$array_images = explode("<->", $get_project['project_images']);

$choose_images = '<select multiple="multiple" class="image-picker show-html" name="post_images[]">';

/* if we have selected images, show them first */
if(count($array_images)>1) {
	$choose_images .= '<optgroup label="'.$mod_lang['label_image_selected'].'">';
	foreach($array_images as $sel_images) {
		if(is_file("$sel_images")) {
			$choose_images .= '<option data-img-src="'.$sel_images.'" value="'.$sel_images.'" selected>'.basename($sel_images).'</option>'."\r\n";
		}
	}
	$choose_images .= '</optgroup>'."\r\n";
}

for($i=0;$i<count($all_images);$i++) {
	
	$img_filename = basename($all_images[$i]['name']);
	$image_name = $all_images[$i]['name'];
	$imgsrc = "../$img_path/$all_images[$i][name]";	
	$filemtime = $all_images[$i]['dateY'];
	
	if($ft_prefs_image_prefix != "") {
		if((strpos($image_name, $ft_prefs_image_prefix)) === false) {
			continue;
		}
	}
	/* new label for each year */
	if($all_images[$i-1]['dateY'] != $filemtime) {	
		if($i == 0) {
			$choose_images .= '<optgroup label="'.$filemtime.'">'."\r\n";
		} else {
			$choose_images .= '</optgroup><optgroup label="'.$filemtime.'">'."\r\n";
		}
	}
	
	if(!in_array($image_name, $array_images)) {
		$choose_images .= '<option data-img-src="'.$image_name.'" value="'.$image_name.'">'.$img_filename.'</option>'."\r\n";
	}
	
}
$choose_images .= '</optgroup>'."\r\n";
$choose_images .= '</select>'."\r\n";

$tpl_form = str_replace('{select_images}', $choose_images, $tpl_form);
	




$tpl_form = str_replace('{label_status}', $mod_lang['label_status'], $tpl_form);
$tpl_form = str_replace('{label_deadline}', $mod_lang['label_time_due'], $tpl_form);
$tpl_form = str_replace('{label_user}', $mod_lang['label_user'], $tpl_form);
$tpl_form = str_replace('{label_client}', $mod_lang['label_client'], $tpl_form);

$tpl_form = str_replace('{client_list}', $select_clients, $tpl_form);
$tpl_form = str_replace('{user_list}', $user_list, $tpl_form);

$tpl_form = str_replace('{val_project_client}', $project_client, $tpl_form);
$tpl_form = str_replace('{val_project_nbr}', $project_nbr, $tpl_form);
$tpl_form = str_replace('{val_project_title}', $project_title, $tpl_form);
$tpl_form = str_replace('{val_project_text}', $project_text, $tpl_form);
$tpl_form = str_replace('{val_project_budget}', $project_budget, $tpl_form);

$tpl_form = str_replace('{val_project_due}', $time_due, $tpl_form);
$tpl_form = str_replace('{val_project_entrydate}', $project_time_recorded, $tpl_form);

$tpl_form = str_replace('{form_action}', $form_action, $tpl_form);
$tpl_form = str_replace('{btn_value}', $btn_save, $tpl_form);
$tpl_form = str_replace('{btn_project_open}', $mod_lang['btn_task_open'], $tpl_form);
$tpl_form = str_replace('{btn_project_done}', $mod_lang['btn_task_done'], $tpl_form);
$tpl_form = str_replace('{btn_reset_value}', $lang['discard_changes'], $tpl_form);
$tpl_form = str_replace('{token}', $_SESSION['token'], $tpl_form);

foreach($mod_lang as $k => $v) {
	$tpl_form = str_replace('{'.$k.'}', $mod_lang[$k], $tpl_form);
}


echo $tpl_form;


?>