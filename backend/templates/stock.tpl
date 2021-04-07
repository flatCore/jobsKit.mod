<div class="row">
	<div class="col-md-9">
		<div class="">Einträge gesamt: {cnt_all}</div>
		<hr>
		{stock_list}
	</div>
	<div class="col-md-3">
		<div class="well well-sm">
			<div class="d-flex btn-group">
				<a href="?tn=moduls&sub=jobsKit.mod&a=stock_edit&edit=p" class="btn btn-success w-100">Neues Produkt</a>
				<a href="?tn=moduls&sub=jobsKit.mod&a=stock_edit&edit=s" class="btn btn-success w-100">Neue Dienstleistung</a>
			</div>
			<hr>
			<a href="?tn=moduls&sub=jobsKit.mod&a=stock_categories" class="btn btn-fc w-100">Rubriken</a>
			
			<hr>
			
			<fieldset>
				<legend>Filter</legend>
				
				<p>Status:</p>
				{status_switch}
				
				<p>Typ:</p>
				{type_switch}
				
				<p>Rubriken:</p>
				{choose_categories}
				
			</fieldset>
			
			<div class="row">
				<div class="col">
					{prev_btn}
				</div>
				<div class="col">
					<form action="?tn=moduls&sub=jobsKit.mod&a=stock" method="POST">
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