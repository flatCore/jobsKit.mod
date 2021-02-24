<?php

/**
 * frontend
 * @modul jobsKit
 *
 */
 
 
 
include "modules/jobsKit.mod/info.inc.php";
require "modules/jobsKit.mod/backend/functions.php";

$mod_db = $mod['database'];

$my_running_timers = jk_get_my_running_timers();

$modul_content = "<p class='alert alert-info'>Es sind noch keine Einträge gespeichert ...</p>";


include 'modules/jobsKit.mod/frontend/tasks.php';

$tasks_array_test = print_r($tasks_array,true);

$modul_content = $return_tasks;

?>