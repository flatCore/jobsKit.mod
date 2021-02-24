
<form action="{form_action}" id="savePrefs" method="post">
	
	<div class="row">
		<div class="col-md-4">

				<div class="form-group">
					<label>{label_item_units}</label>
					<input type="text" class="form-control" name="prefs_item_units" value="{prefs_item_units}">
 				</div>
 				
		</div>
		<div class="col-md-4">
			
			<div class="form-group">
				<label>{label_item_quantities}</label>
				<input type="text" class="form-control" name="prefs_item_quantities" value="{prefs_item_quantities}">
			</div>
			
	  </div>   
		<div class="col-md-4">
			
			<div class="form-group">
				<label>{label_item_commissions}</label>
				<input type="text" class="form-control" name="prefs_item_commissions" value="{prefs_item_commissions}">
	   	</div>   	   				
		
		</div>
	</div>
	
	
	<fieldset>
		<legend>{label_taxes}</legend>
			<div class="row">
				<div class="col-md-4">
						<div class="form-group">
							<label>{label_tax} #1</label>
							<input type="text" class="form-control" name="prefs_tax_1" value="{prefs_tax_1}">
		 				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>{label_tax} #2</label>
						<input type="text" class="form-control" name="prefs_tax_2" value="{prefs_tax_2}">
					</div>
			  </div>   
				<div class="col-md-4">
					<div class="form-group">
						<label>{label_tax} #3</label>
						<input type="text" class="form-control" name="prefs_tax_3" value="{prefs_tax_3}">
			   	</div>
				</div>
			</div>
	</fieldset>

	<fieldset>
		<legend>{label_commissions}</legend>
			<div class="row">
				<div class="col-md-4">
						<div class="form-group">
							<label>{label_commission} #1</label>
							<input type="text" class="form-control" name="prefs_commission_1" value="{prefs_commission_1}">
		 				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>{label_commission} #2</label>
						<input type="text" class="form-control" name="prefs_commission_2" value="{prefs_commission_2}">
					</div>
			  </div>   
				<div class="col-md-4">
					<div class="form-group">
						<label>{label_commission} #3</label>
						<input type="text" class="form-control" name="prefs_commission_3" value="{prefs_commission_3}">
			   	</div>
				</div>
			</div>
	</fieldset>
	
	
	<input type="submit" name="submitPrefs" id="submitPrefs" class="btn btn-success" value="{btn_value}">
	<input type="hidden" name="csrf_token" value="{token}">
	
</form>
