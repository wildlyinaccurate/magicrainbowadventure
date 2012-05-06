<section class="submit-entry">
	<?=Form::open_for_files('entry/submit')?>

        <div class="control-group">
            <?=Form::text('title', Input::old('title'), array('maxlength' => 1400, 'placeholder' => Lang::line('entry.title')))?>
            <?=$errors->first('title', '<span class="error">:message</span>')?>
        </div>

        <div class="control-group">
            <?=Form::textarea('description', Input::old('description'), array('placeholder' => Lang::line('entry.description')))?>
            <?=$errors->first('description', '<span class="error">:message</span>')?>
        </div>

        <div class="control-group">
            <p class="help-block">Images must be in GIF or JPEG format and smaller than <?=round($max_upload_size / 1024)?>MB</p>

            <?=Form::label('entry_image', Lang::line('entry.upload_file'))?>
            <?=Form::file('entry_image')?>
            <?=$errors->first('entry_image', '<span class="error">:message</span>')?>
        </div>

        <div class="control-group">
            <?=Form::text('image_url', Input::old('image_url'), array('placeholder' => Lang::line('entry.link_to_image')))?>
            <?=$errors->first('image_url', '<span class="error">:message</span>')?>
        </div>

		<div class="controls">
			<button type="submit" name="submit_entry" value="submit">Submit Entry</button>
		</div>

	<?=Form::close()?>
</section>
