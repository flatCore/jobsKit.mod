<?php

/**
 * @modul	jobsKit.mod
 * backend
 */

error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

echo '<h3>'.$mod_name.' '.$mod_version.' <small>| '.$mod_lang['nav_categories'].'</small></h3>';

if($_POST['delete_cat']) {

	$id = (int) $_POST['id'];
	$dbh = new PDO("sqlite:$mod_db");
	$delete_sql = "DELETE FROM categories WHERE id = $id";
	$cnt_changes = $dbh->exec($delete_sql);

	if($cnt_changes > 0){
		$sys_message = '{OKAY} ' . $lang['db_changed'];
	} else {
		$sys_message = '{ERROR} ' . $lang['db_not_changed'];
	}
	$dbh = null;
}


/* Save new Category */
if($_POST['save_cat']) {

	$new_sql = "INSERT INTO categories	(
					cat_id, cat_title, cat_title_safe, cat_description, cat_sort, cat_images
					) VALUES (
					NULL, :cat_title, :cat_title_safe, :cat_description, :cat_sort, :cat_images
					) ";
	
	$cat_title_safe = clean_filename($_POST['cat_title']);
	
	$dbh = new PDO("sqlite:$mod_db");
	$sth = $dbh->prepare($new_sql);
	$sth->bindParam(':cat_title', $_POST['cat_title'], PDO::PARAM_STR);
	$sth->bindParam(':cat_title_safe', $cat_title_safe, PDO::PARAM_STR);
	$sth->bindParam(':cat_description', $_POST['cat_description'], PDO::PARAM_STR);
	$sth->bindParam(':cat_sort', $_POST['cat_sort'], PDO::PARAM_STR);
	$sth->bindParam(':cat_images', $_POST['cat_images'], PDO::PARAM_STR);

	$cnt_changes = $sth->execute();
	$dbh = null;

	if($cnt_changes == TRUE) {
		$sys_message = '{OKAY} ' . $lang['db_changed'];
	} else {
		$sys_message = '{ERROR} ' . $lang['db_not_changed'];
	}

}


/* Update Category */
if($_POST['update_cat']) {

	$editcat = (int) $_POST['id'];
	$cat_title_safe = clean_filename($_POST['cat_title']);
	
	$update_sql = "UPDATE categories
									SET cat_title = :cat_title,
										cat_title_safe = :cat_title_safe,
										cat_description = :cat_description,
										cat_sort = :cat_sort,
										cat_images = :cat_images
									WHERE cat_id = $editcat ";
									
	$dbh = new PDO("sqlite:$mod_db");							
	$sth = $dbh->prepare($update_sql);
	$sth->bindParam(':cat_title', $_POST['cat_title'], PDO::PARAM_STR);
	$sth->bindParam(':cat_title_safe', $cat_title_safe, PDO::PARAM_STR);
	$sth->bindParam(':cat_description', $_POST['cat_description'], PDO::PARAM_STR);
	$sth->bindParam(':cat_sort', $_POST['cat_sort'], PDO::PARAM_STR);
	$sth->bindParam(':cat_images', $_POST['cat_images'], PDO::PARAM_STR);

	$cnt_changes = $sth->execute();
	$dbh = null;

	if($cnt_changes == TRUE) {
		$sys_message = '{OKAY} ' . $lang['db_changed'];
	} else {
		$sys_message = '{ERROR} ' . $lang['db_not_changed'];
	}

	$_REQUEST['editcat'] = $editcat;

}




$submit_button = "<input type='submit' class='btn btn-save' name='save_cat' value='$lang[save]'>";
$delete_button = "";


if($_REQUEST['editcat'] != "") {

	$editcat = (int) $_REQUEST['editcat'];
	
	$submit_button = "<input type='submit' class='btn btn-save' name='update_cat' value='$lang[update]'>";
	$delete_button = "<input type='submit' class='btn btn-fc text-danger' name='delete_cat' value='$lang[delete]' onclick=\"return confirm('$lang[confirm_delete_data]')\">";
	$hidden_field = "<input type='hidden' name='id' value='$editcat'>";
	
	$dbh = new PDO("sqlite:$mod_db");
	$edit_sql = "SELECT * FROM categories WHERE cat_id = $editcat";
	
	$edit_cat = $dbh->query($edit_sql);
	$edit_cat = $edit_cat->fetch(PDO::FETCH_ASSOC);
	$dbh = null;
	
	$cat_title = stripslashes($edit_cat['cat_title']);
	$cat_description = stripslashes($edit_cat['cat_description']);
	$cat_sort = stripslashes($edit_cat['cat_sort']);
	$cat_images = stripslashes($edit_cat['cat_images']);
	$cat_title_safe = $edit_cat['cat_title_safe'];

}




/* MESSAGES */

if($sys_message != ""){
	print_sysmsg("$sys_message");
}












$get_cats = jk_get_stock_categories();
$cnt_get_cats = count($get_cats);

echo '<div class="row">';
echo '<div class="col-md-6">';

echo "<form action='?tn=moduls&sub=jobsKit.mod&a=$a' class='' method='POST'>";


echo '<div class="form-group">';
echo '<label>'.$mod_lang['label_item_title'].'</label>';
echo '<input type="text" class="form-control" name="cat_title" value="'.$cat_title.'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label>'.$mod_lang['label_sort'].'</label>';
echo '<input type="text" class="form-control" name="cat_sort" value="'.$cat_sort.'">';
echo '</div>';


$images = fc_scandir_rec('../'.FC_CONTENT_DIR.'/images');

/* avatar */
$choose_tmb = '<select class="form-control choose-thumb custom-select" name="cat_images">';
$choose_tmb .= '<option value="">Kein Bild ...</option>';
foreach($images as $img) {
	$selected = '';
	if($cat_images == $img) {$selected = 'selected';}
	$img = str_replace('../content/', '/content/', $img);
	$choose_tmb .= '<option '.$selected.' value='.$img.'>'.$img.'</option>';
}
$choose_tmb .= '</select>';

if($edit_cat['thumbnail'] == '') {
	$thumb_saved = '../modules/jobsKit.mod/backend/poster.jpg';
} else {
	$thumb_saved = $edit_cat['thumbnail'];
}

echo '<div class="form-group">';
echo '<label>'.$mod_lang['label_images'].'</label>';
echo '<div class="row">';
echo '<div class="col-md-2">';
echo '<img src="'.$thumb_saved.'" class="rounded img-fluid thumb-preview">';
echo '</div>';
echo '<div class="col-md-10">';
echo $choose_tmb;
echo '</div>';
echo '</div>';
echo '</div>';


echo '<div class="form-group">';
echo '<label>'.$mod_lang['label_item_description'].'</label>';
echo "<textarea class='form-control' rows='8' name='cat_description'>$cat_description</textarea>";
echo '</div>';



echo"<div class='formfooter'>";
echo"$hidden_field $delete_button $submit_button";
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo"</div>";

echo"</form>";




echo '</div>';
echo '<div class="col-md-6">';









echo '<div class="scroll-container">';

for($i=0;$i<$cnt_get_cats;$i++) {
	
	if($get_cats[$i]['cat_images'] == '') {
		$thumb_saved = '../modules/jobsKit.mod/backend/poster.jpg';
	} else {
		$thumb_saved = $get_cats[$i]['cat_images'];
	}
	
	echo '<a class="btn-categories" href="?tn=moduls&sub=jobsKit.mod&a=stock_categories&editcat='.$get_cats[$i]['cat_id'].'">';
	echo '<div class="row">';
	echo '<div class="col-sm-2">';
	echo '<img src="'.$thumb_saved.'" class="img-fluid">';
	echo '</div>';
	echo '<div class="col-sm-10">';
	echo '<p><code>'.$get_cats [$i]['cat_sort'].'</code> <strong>'.$get_cats[$i]['cat_title'].'</strong><br />'.$get_cats[$i]['cat_description'].'</p>';
	echo '<p>URL: <code>/'.$get_cats[$i]['cat_title_safe'].'/</code></p>';
	echo '</div>';
	echo '</div>';
	echo '</a>';
}
echo '</div>';


echo '</div>';
echo '</div>';




?>