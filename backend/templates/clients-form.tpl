<div class="well">
	<form action="{form_action}" id="saveClient" method="post">
		
		<div class="row">
			<div class="col-md-3">
		   	<div class="form-group">
		   		<label>{label_client_nbr}</label>
		   		<input type="text" class="form-control" name="client_nbr" value="{val_client_nbr}">
		   	</div>
			</div>
			<div class="col-md-9">
		   	<div class="form-group">
		   		<label>{label_client_company}</label>
		   		<input type="text" class="form-control" name="client_company" value="{val_client_company}">
		   	</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>{label_client_firstname}</label>
							<input type="text" class="form-control" name="client_firstname" value="{val_client_firstname}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>{label_client_lastname}</label>
							<input type="text" class="form-control" name="client_lastname" value="{val_client_lastname}">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>{label_client_mail}</label>
							<input type="text" class="form-control" name="client_mail" value="{val_client_mail}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>{label_client_phone}</label>
							<input type="text" class="form-control" name="client_phone" value="{val_client_phone}">
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{label_client_adress}</label>
					<textarea class="form-control" name="client_adress" rows="5">{val_client_adress}</textarea>
				</div>
			</div>
		</div>
	
		<input type="submit" name="submitClient" id="submitClient" class="btn btn-save" value="{btn_value}">
		<input type="hidden" name="csrf_token" value="{token}">
		<input type="hidden" name="mode" value="{mode}">
	
	</form>
</div>