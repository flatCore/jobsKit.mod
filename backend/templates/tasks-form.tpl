
<form action="{form_action}" id="saveTask" method="post">

	<div class="row">
		<div class="col-md-6">
				
				<div class="form-group">
					<label>Titel</label>
					<div class="input-group">
						<input type="text" class="form-control" name="task_title" value="{val_task_title}">
						<div class="input-group-append">
							<span class="input-group-text"><input type="checkbox" name="task_priority" value="1" class="mr-2" {checked_priority}> wichtig</label></span>
						</div>
	   			</div>
	   		</div>

			 	<div class="form-group">
					<label>Beschreibung/Notizen</label>
					<textarea class="form-control" rows="10" autofocus="" name="task_text">{val_task_text}</textarea>
			 	</div>
			 			   
			
	
		</div>
		<div class="col-md-6">
			<div class="well well-sm">
				<div class="row">
					<div class="col-md-6">
				<fieldset>
					<legend>{label_user}</legend>
					{user_list}
				</fieldset>
					</div>
					<div class="col-md-6">
						<fieldset>
							<legend>{label_hourly_wage}</legend>
							<input type="text" class="form-control" name="task_hourly_wage" value="{val_task_hourly_wage}">
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<fieldset>
							<legend>{label_project}</legend>
							{project_list}
						</fieldset>
					</div>
					<div class="col-md-6">
						<fieldset>
							<legend>{label_client}</legend>
							{client_list}
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
				<fieldset>
					<legend>{label_deadline}</legend>
		 			<div class="input-group">
			 			<input type="text" class="form-control dp" name="task_time_due" value="{val_task_due}">
			 		</div>
				</fieldset>
					</div>
					<div class="col-md-6">
						
				<fieldset>
					<legend>{label_repeat}</legend>
	   	<div class="radio">
			   	<label><input type="radio" name="task_repeat" value="never" {checked_repeat_never}> {label_repeat_never}</label>
		   	</div>
			   	<div class="radio">
				   	<label><input type="radio" name="task_repeat" value="daily" {checked_repeat_daily}> {label_repeat_daily}</label>
			   	</div>
			   	<div class="radio">
				   	<label><input type="radio" name="task_repeat" value="weekly" {checked_repeat_weekly}> {label_repeat_weekly}</label>
			   	</div>
			   	<div class="radio">
				   	<label><input type="radio" name="task_repeat" value="monthly" {checked_repeat_monthly}> {label_repeat_monthly}</label>
			   	</div>
			   	<div class="radio">
				   	<label><input type="radio" name="task_repeat" value="yearly" {checked_repeat_yearly}> {label_repeat_yearly}</label>
			   	</div>
				</fieldset>
					</div>
				</div>
				<fieldset>
					<legend>{label_status}</legend>
	   			<div class="input-group">
				 		<div class="btn-group btn-group-toggle" data-bs-toggle="buttons">
					 		<label class="btn btn-fc btn-sm active {class_open_active}"><input type="radio" name="task_status" value="1" {checked_task_open}> {btn_task_open} </label>
					 		<label class="btn btn-fc btn-sm {class_done_active}"><input type="radio" name="task_status" value="2" {checked_task_done}> {btn_task_done} </label>
					 	</div>
	   			</div>
				</fieldset>
	   		

	   		
					<input type="submit" name="submitTask" id="submitTask" class="btn btn-save w-100" value="{btn_value}">
					<input type="hidden" name="csrf_token" value="{token}">
					<input type="hidden" name="mode" value="{mode}">
			</div>
		</div>
	</div>
</form>
