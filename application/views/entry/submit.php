<?php if ($upload_error): ?>
	<div class="error"><?=$upload_error?></div>
<?php endif; ?>

<p>Contribute to the magic rainbow adventure by submitting an image! We accept GIF and JPEG images that are less than <?=byte_format($max_upload_size, 0)?> in size.</p>

<?=form_open_multipart('entry/submit', 'method="post" class="tabbed"')?>

<div>
	<label for="title">Title</label>
	<input type="text" name="title" id="title" maxlength="255" value="<?=set_value('title')?>" />
	<?=form_error('title')?>
</div>

<div>
	<label for="description">Description</label>
	<textarea name="description" id="description"><?=set_value('description')?></textarea>
	<?=form_error('description')?>
</div>

<div>
	<label for="userfile">Upload an image...</label>
	<input type="file" name="userfile" id="userfile" />
</div>

<div>
	<label for="image_url">...Or link to an image</label>
	<input type="text" name="image_url" id="image_url" value="<?=set_value('image_url')?>" />
	<?=form_error('image_url')?>
</div>

<div class="controls">
	<button type="submit" name="submit_entry" value="submit">Submit Entry</button>
</div>

<?=form_close()?>