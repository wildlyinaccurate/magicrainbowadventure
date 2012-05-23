<?=Form::open("entries/{$entry->getId()}/comment")?>

	<div class="control-group">
		<?=Form::textarea('comment', Input::old('comment'), array(
			'placeholder' => Lang::line('entries.label_comment'),
			'rows' => 3,
		))?>
	</div>

	<button type="submit" name="submit_entry" class="btn btn-primary"><?=Lang::line('entries.label_submit_comment')?></button>

<?=Form::close()?>
