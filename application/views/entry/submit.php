<?=Form::open_for_files('entry/submit')?>

<div>
	<?=Form::label('title', 'Title')?>
	<?=Form::text('title', Input::get('title'), array('maxlength' => 255))?>
</div>

<div>
	<?=Form::label('description', 'Desription')?>
	<?=Form::textarea('description', Input::get('description'), array('id' => 'description'))?>
</div>

<div>
	<?=Form::label('entry_image', 'Upload a file...')?>
	<?=Form::file('entry_image')?>
</div>

<div>
	<?=Form::label('image_url', '...Or link to an image')?>
	<?=Form::text('image_url', Input::get('image_url'), array('id' => 'image_url'))?>
</div>

<div class="controls">
	<button type="submit" name="submit_entry" value="submit">Submit Entry</button>
</div>

<?=Form::close()?>
