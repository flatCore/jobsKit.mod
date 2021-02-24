<div class="card-deck mb-4">

		<div class="card ">
			<div class="card-header"><strong>Projekte</strong> {btn_add_job}</div>
				<div class="card-body">
					<table class="table">
						<tr>
							<td>In Arbeit</td>
							<td class="text-right">{cnt_active_projects}</td>
						</tr>
						<tr>
							<td>Erledigt</td>
							<td class="text-right">{cnt_done_projects}</td>
						</tr>
						<tr>
							<td>Alle</td>
							<td class="text-right">{cnt_all_projects}</td>
						</tr>
					</table>
  				</div>
			</div>	

		<div class="card ">
			<div class="card-header"><strong>Aufgaben</strong> {btn_add_task}</div>
				<div class="card-body">
					<table class="table">
						<tr>
							<td>In Arbeit</td>
							<td class="text-right">{cnt_active_tasks}</td>
						</tr>
						<tr>
							<td>Erledigt</td>
							<td class="text-right">{cnt_done_tasks}</td>
						</tr>
						<tr>
							<td>Alle</td>
							<td class="text-right">{cnt_all_tasks}</td>
						</tr>
					</table>
  				</div>
			</div>		



</div>

<div class="card-deck">
		<div class="card ">
			<div class="card-header"><strong>Aufgaben</strong> {btn_add_task}</div>
			<div class="card-body">
				<div class="scroll-container">
				{tasks_table}
				</div>
			</div>
		</div>

		<div class="card ">
			<div class="card-header"><strong>Aufträge</strong> {btn_add_job}</div>
			<div class="card-body">
				<div class="scroll-container">
				{jobs_table}
				</div>
			</div>
		</div>
		
		<div class="card ">
			<div class="card-header"><strong>Logfile</strong></div>
				<div class="card-body">
					<div class="scroll-container">
					{log_table}
					</div>
  			</div>
			</div>	

</div>