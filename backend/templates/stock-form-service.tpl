<form action="{form_action}" method="POST">

	<div class="row">
		<div class="col-md-9">	
	
			<div class="card">
				<div class="card-header">
			<nav>
				<ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
					<li class="nav-item"><a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="true">Info</a></li>
					<li class="nav-item"><a class="nav-item nav-link" id="nav-images-tab" data-toggle="tab" href="#nav-images" role="tab" aria-controls="nav-images" aria-selected="false">{label_images}</a></li>
					<li class="nav-item"><a class="nav-item nav-link" id="nav-accounting-tab" data-toggle="tab" href="#nav-accounting" role="tab" aria-controls="nav-accounting" aria-selected="false">{label_accounting}</a></li>
				</ul>
			</nav>
				</div>
				<div class="card-body">
					
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
	
					<div class="form-group">
						<label>{label_item_title}</label>
						<input type="text" class="form-control" name="item_title" value="{item_title}">
				 	</div>
					<div class="form-group">
						<label>{label_item_description}</label>
						<textarea class="form-control mceEditor" autofocus="" placeholder="Projekt Notizen" name="item_description">{item_description}</textarea>
				 	</div>
					<div class="form-group">
						<label>{label_keywords}</label>
						<input type="text" class="form-control" name="item_keywords" value="{item_keywords}">
				 	</div>
 	
				</div>
				<div class="tab-pane fade" id="nav-images" role="tabpanel" aria-labelledby="nav-images-tab">
					<div class="images-list scroll-container">
					{select_images}
					</div>
				</div>				
				<div class="tab-pane fade" id="nav-accounting" role="tabpanel" aria-labelledby="nav-accounting-tab">
										
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>{label_item_hourly_rate}</label>
								<input type="text" class="form-control" name="item_hourly_rate" value="{item_hourly_rate}">
						 	</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>{label_item_tax}</label>
								{select_tax}
						 	</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>{label_item_time_estimated}</label>
								<input type="text" class="form-control" name="item_time_estimated" value="{item_time_estimated}">
						 	</div>
						</div>

					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>{label_item_price_net}</label>
								<input type="text" class="form-control" readonly value="{price_net}">
						 	</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>{label_item_price_gross}</label>
								<input type="text" class="form-control" readonly value="{price_gross}">
						 	</div>
						</div>
					</div>				
					
				</div>
				
			</div>
				</div>
			</div>

 	
		</div>
		<div class="col-md-3">
 	
			<div class="well">
				
				{select_status}
				
				<hr>
				
				<input type="submit" name="submitProduct" id="submitProduct" class="btn btn-block btn-success" value="{btn_value}">
				<input type="hidden" name="csrf_token" value="{token}">
				<input type="hidden" name="mode" value="{mode}">
				<input type="hidden" name="item_type" value="{item_type}">
			</div>
			
		</div>
	</div>
</form>