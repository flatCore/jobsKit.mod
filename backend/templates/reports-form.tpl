<form action="{form_action}" id="saveTask" method="post">
	
	<div class="row">
		<div class="col-md-8">
			
			<div class="row">
				<div class="col-md-6">
						<fieldset>
							<legend>{label_timer_start}</legend>
				 			<div class="input-group date">
					 			<input type="text" class="form-control dp" name="timer_start" value="{val_timer_start}">
					 		</div>
						</fieldset>
				</div>
				<div class="col-md-6">
						<fieldset>
							<legend>{label_timer_end}</legend>
				 			<div class="input-group date">
					 			<input type="text" class="form-control dp" name="timer_end" value="{val_timer_end}">
					 		</div>
						</fieldset>
				</div>
			</div>
			
			<fieldset>
				<legend>{label_notes}</legend>
				<textarea class="form-control" rows="10" autofocus="" name="timer_notes">{val_timer_notes}</textarea>
			</fieldset>
		
		</div>
		<div class="col-md-4">
					<fieldset>
						<legend>{label_tasks}</legend>
						{task_list}
					</fieldset>		
					<fieldset>
						<legend>{label_client}</legend>
						{client_list}
					</fieldset>
					<fieldset>
						<legend>{label_project}</legend>
						{project_list}
					</fieldset>
				<fieldset>
					<legend>{label_user}</legend>
					{user_list}
				</fieldset>
				<hr class="shadow">
				<input type="submit" name="submitTimer" id="submitTimer" class="btn btn-success btn-block" value="{btn_value}">
				</div>
			</div>
	

	
	<input type="hidden" name="csrf_token" value="{token}">
	<input type="hidden" name="mode" value="{mode}">
</form>