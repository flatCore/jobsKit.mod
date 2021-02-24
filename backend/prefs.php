<?php
	
if(!defined('FC_INC_DIR')) {
	die("No access");
}

include $mod_root.'backend/include.php';
echo '<h3>'.$mod_name.' <small>'.$mod_lang['title_preferences'].'</small></h3>';


if(isset($_POST['submitPrefs'])) {
	jk_update_preferences();
	$jk_prefs = jk_get_preferences();
}



$tpl = file_get_contents($mod_root.'backend/templates/prefs-form.tpl');
$tpl = str_replace('{form_action}', '?tn=moduls&sub=jobsKit.mod&a=prefs', $tpl);
$tpl = str_replace('{token}', $_SESSION['token'], $tpl);
$tpl = str_replace('{prefs_item_units}', $jk_prefs['prefs_item_units'], $tpl);
$tpl = str_replace('{prefs_item_commissions}', $jk_prefs['prefs_item_commissions'], $tpl);
$tpl = str_replace('{prefs_item_quantities}', $jk_prefs['prefs_item_quantities'], $tpl);

$tpl = str_replace('{prefs_tax_1}', $jk_prefs['prefs_tax_1'], $tpl);
$tpl = str_replace('{prefs_tax_2}', $jk_prefs['prefs_tax_2'], $tpl);
$tpl = str_replace('{prefs_tax_3}', $jk_prefs['prefs_tax_3'], $tpl);

$tpl = str_replace('{prefs_commission_1}', $jk_prefs['prefs_commission_1'], $tpl);
$tpl = str_replace('{prefs_commission_2}', $jk_prefs['prefs_commission_2'], $tpl);
$tpl = str_replace('{prefs_commission_3}', $jk_prefs['prefs_commission_3'], $tpl);


foreach($mod_lang as $k => $v) {
	$tpl = str_replace('{'.$k.'}', $mod_lang[$k], $tpl);
}


echo $tpl;

echo '<hr>';
echo '<pre>';
print_r($jk_prefs);
echo '</pre>';
?>