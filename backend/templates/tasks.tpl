<div class="row">
	<div class="col-md-9">
		{alert}
		{tasks_list}
		<hr>
		<a class="btn btn-link" role="button" data-toggle="collapse" href="#doneList" aria-expanded="false" aria-controls="collapseExample">{btn_collapse_tasks_done} ({cnt_done})</a>
		<div class="collapse" id="doneList">
			<div class="well">
				{tasks_list_done}
  		</div>
		</div>
		
	</div>
	<div class="col-md-3">
		<div class="well well-sm">
			<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=tasks_edit" class="btn btn-success btn-block">{lang_btn_new_task}</a>
			<hr class="shadow">

			<form action="{form_action}" method="POST">
			<fieldset>
				<legend>Kunden</legend>
				{select_clients}
			</fieldset>

			<fieldset>
				<legend>Projects</legend>
				{select_projects}
			</fieldset>
			
			<fieldset>
				<legend>User</legend>
				{select_user}
			</fieldset>
			

				<hr>
				<input type="submit" name="submitFilter" id="submitFilter" class="btn btn-success btn-block" value="{btn_value}">
				<input type="hidden" name="csrf_token" value="{csrf_token}">
			</form>
		</div>
	</div>
</div>