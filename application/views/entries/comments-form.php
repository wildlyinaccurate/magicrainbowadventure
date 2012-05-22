<?=Form::open('account/settings', 'POST', array('class' => 'form-horizontal'))?>

	<?=Form::textarea('comment', Input::old('comment'), array('placeholder' => Lang::line('entries.label_comment')))?>

	<div class="form-actions">
		<button type="submit" name="submit_entry" class="btn btn-primary"><?=Lang::line('entries.label_submit')?></button>
	</div>

<?=Form::close()?>
