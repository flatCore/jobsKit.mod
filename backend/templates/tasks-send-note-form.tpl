<div class="well well-sm">
	<p><strong>{form_title}</strong> {form_intro}</p>
	<form action="{form_action}" method="POST">
		<div class="form-group">
			<textarea class="form-control" name="timer_note"></textarea>
		</div>
		<input type="submit" name="save_note_to_timer" value="Speichern" class="btn btn-success">
		<input type="hidden" name="timer_id" value="{timer_id}">
		<input type="hidden" name="csrf_token" value="{tokken}">
	</form>
</div>