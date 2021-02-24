<div class="row">
	<div class="col-md-9">
		{alert}
		{jobs_list}
	</div>
	<div class="col-md-3">
		<div class="well well-sm">
			<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs_edit" class="btn btn-fc text-success btn-block">{lang_btn_new_project}</a>
			<hr>
			
			<fieldset>
				<legend>{label_filter}</legend>
				
				<form action="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs" method="POST">
					{client_list}
				</form>
				
				<hr>
				
			<form action="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs" method="POST">
				<input type="text" class="form-control" placeholder="Filter" name="project_filter" value="{val_filter}">
			</form>
			<hr>
			<div class="btn-group d-flex" role="group">
				<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs&switch=0" class="btn btn-fc w-100 {selected_job_status0}">{btn_show_all_projects}</a>
				<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs&switch=1" class="btn btn-fc w-100 {selected_job_status1}">{btn_show_open_projects}</a>
				<a href="acp.php?tn=moduls&sub=jobsKit.mod&a=jobs&switch=2" class="btn btn-fc w-100 {selected_job_status2}">{btn_show_done_projects}</a>
			</div>
			</fieldset>

			<div class="row">
				<div class="col">
					{prev_btn}
				</div>
				<div class="col">
					<form action="?tn=moduls&sub=jobsKit.mod&a=jobs" method="POST">
						{select_page}
					</form>
				</div>
				<div class="col">
					{next_btn}
				</div>			
			</div>


			
		</div>
	</div>
</div>