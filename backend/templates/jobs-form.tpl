
<form action="{form_action}" id="saveProject" method="post">
	<div class="row">
		<div class="col-md-9">
			
			<div class="card">
				<div class="card-header">
			<nav>
				<ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
					<li class="nav-item"><a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="true">Info</a></li>
					<li class="nav-item"><a class="nav-item nav-link" id="nav-images-tab" data-toggle="tab" href="#nav-images" role="tab" aria-controls="nav-images" aria-selected="false">{label_images}</a></li>
					<li class="nav-item"><a class="nav-item nav-link" id="nav-tasks-tab" data-toggle="tab" href="#nav-tasks" role="tab" aria-controls="nav-tasks" aria-selected="false">{label_tasks}</a></li>
					<li class="nav-item"><a class="nav-item nav-link" id="nav-accounting-tab" data-toggle="tab" href="#nav-accounting" role="tab" aria-controls="nav-accounting" aria-selected="false">{label_accounting}</a></li>
				</ul>
			</nav>
				</div>
				<div class="card-body">
			
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
	

				<fieldset>
					<legend>{label_client}</legend>
					{client_list}
				</fieldset>
					
			<div class="row">
	   	<div class="col-md-3">
	   		<div class="form-group">
	   			<label>Projekt-Nr.</label>
	   			<input type="text" class="form-control" name="project_nbr" value="{val_project_nbr}">
	   		</div>
	   	</div>
	   	<div class="col-md-9">
	   		<div class="form-group">
	   			<label>Titel</label>
	   			<input type="text" class="form-control" name="project_title" value="{val_project_title}">
	   		</div>
	   	</div>   
	   </div>
	   
	   <div class="row">
	   	<div class="col-md-12">
			 	<div class="form-group">
					<label>Beschreibung/Notizen</label>
					<textarea class="form-control mceEditor" autofocus="" placeholder="Projekt Notizen" name="project_text">{val_project_text}</textarea>
			 	</div>
			</div>
	   </div>
	   
				</div>
				<div class="tab-pane fade" id="nav-images" role="tabpanel" aria-labelledby="nav-images-tab">
					<div class="images-list scroll-container">
					{select_images}
					</div>
				</div>
				<div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">
					{tasks_list}
				</div>
					<div class="tab-pane fade" id="nav-accounting" role="tabpanel" aria-labelledby="nav-accounting-tab">

				<fieldset>
					<legend>{label_budget}</legend>
						<div class="input-group">
				 			<input type="text" class="form-control" name="project_budget" value="{val_project_budget}">
				 			<span class="input-group-addon"><span class="glyphicon-eur glyphicon"></span></span>
			 			</div>
				</fieldset>	

				</div>   
	   
			</div>
			</div>
			</div>
	   
	   
			
	
		</div>
		<div class="col-md-3">
			<div class="well well-sm">

				<fieldset>
					<legend>{label_user}</legend>
					{user_list}
				</fieldset>
		        
				<fieldset>
					<legend>{label_date}</legend>
			 		<div class="input-group">
				 		<input type="text" class="form-control dp" name="project_entrydate" value="{val_project_entrydate}">
			 		</div>
				</fieldset>
				<fieldset>
					<legend>{label_deadline}</legend>
			 		<div class="input-group" >
				 		<input type="text" class="form-control dp" name="project_due_date" value="{val_project_due}">
			 		</div>
				</fieldset>
			
				<fieldset>
					<legend>{label_status}</legend>
	   			<div class="input-group">
				 		<div class="btn-group btn-group-toggle" data-toggle="buttons">
					 		<label class="btn btn-fc btn-sm {class_open_active}">
					 			<input type="radio" name="project_status" class="" value="1" {checked_project_open}> {btn_project_open}
					 		</label>
					 		<label class="btn btn-fc btn-sm {class_done_active}">
					 			<input type="radio" name="project_status" class="" value="2" {checked_project_done}> {btn_project_done}
					 		</label>
					 	</div>
	   			</div>
				</fieldset>
				
				

		   		
				<input type="submit" name="submitProject" id="submitProject" class="btn btn-save btn-block" value="{btn_value}">
		   	
		   	<input type="submit" name="reset" class="btn btn-fc" value="{btn_reset_value}">
				<input type="hidden" name="csrf_token" value="{token}">
				<input type="hidden" name="mode" value="{mode}">
								   	

			</div>
		</div>
	</div>
</form>
