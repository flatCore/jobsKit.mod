<?php
/**
 * jobsKit | flatCore Modul
 * Configuration File
 */

if(FC_SOURCE == 'backend') {
	$mod_root = '../modules/jobsKit.mod/';
} else {
	$mod_root = 'modules/jobsKit.mod/';
}

include $mod_root.'lang/de.php';

if(is_file($mod_root.'lang/'.$languagePack.'.php')) {
	include $mod_root.'lang/'.$languagePack.'.php';
}



$mod['name'] = 'jobsKit';
$mod['version'] = '0.2.0.4';
$mod['author'] = 'flatCore DevTeam';
$mod['description'] = 'Organise your Jobs, getting Things done and track working time';
$mod['database'] = "content/SQLite/jobsKit.sqlite3";


/* acp */

$modnav[] = array('link' => $mod_lang['nav_dashboard'], 'title' => '', 'file' => "start");
$modnav[] = array('link' => $mod_lang['nav_jobs'], 'title' => '', 'file' => "jobs");
$modnav[] = array('link' => $mod_lang['nav_tasks'], 'title' => '', 'file' => "tasks");
$modnav[] = array('link' => $mod_lang['nav_stock'], 'title' => '', 'file' => "stock");
$modnav[] = array('link' => $mod_lang['nav_clients'], 'title' => '', 'file' => "clients");
$modnav[] = array('link' => $mod_lang['nav_reports'], 'title' => '', 'file' => "reports");
$modnav[] = array('link' => $mod_lang['nav_preferences'], 'title' => '', 'file' => "prefs");

?>