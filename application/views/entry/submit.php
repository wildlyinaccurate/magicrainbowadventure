<?=Form::open_for_files('entry/submit')?>

<div>
	<?=Form::label('title', 'Title')?>
	<?=Form::text('title', Input::old('title'), array('maxlength' => 1400))?>
	<?=$errors->first('title', '<span class="error">:message</span>')?>
</div>

<div>
	<?=Form::label('description', 'Desription')?>
	<?=Form::textarea('description', Input::old('description'))?>
	<?=$errors->first('description', '<span class="error">:message</span>')?>
</div>

<p class="note">Images must be in GIF or JPEG format and smaller than <?=round($max_upload_size / 1024)?>MB</p>

<div>
	<?=Form::label('entry_image', 'Upload a file...')?>
	<?=Form::file('entry_image')?>
	<?=$errors->first('entry_image', '<span class="error">:message</span>')?>
</div>

<div>
	<?=Form::label('image_url', '...Or link to an image')?>
	<?=Form::text('image_url', Input::old('image_url'))?>
	<?=$errors->first('image_url', '<span class="error">:message</span>')?>
</div>

<div class="controls">
	<button type="submit" name="submit_entry" value="submit">Submit Entry</button>
</div>

<?=Form::close()?>
