<section class="submit-entry">
	<?=Form::open_for_files('entries/submit', 'POST', array('class' => 'form-horizontal')  )?>

        <div class="control-group">
            <?=Form::label('title', Lang::line('entries.label_title'), array('class' => 'control-label'))?>

            <div class="controls">
                <?=Form::text('title', Input::old('title'), array('maxlength' => 140, 'placeholder' => Lang::line('entries.title')))?>
                <?=$errors->first('title', '<span class="error">:message</span>')?>
            </div>
        </div>

        <div class="control-group">
            <?=Form::label('entry_image', Lang::line('entries.label_description'), array('class' => 'control-label'))?>

            <div class="controls">
                <?=Form::textarea('description', Input::old('description'), array(
                    'placeholder' => Lang::line('entries.description'),
                    'rows' => 4,
                ))?>
                <?=$errors->first('description', '<span class="error">:message</span>')?>
            </div>
        </div>

        <div class="control-group">
            <?=Form::label('entry_image', Lang::line('entries.label_upload_file'), array('class' => 'control-label'))?>

            <div class="controls">
                <?=Form::file('entry_image')?>
                <?=$errors->first('entry_image', '<span class="error">:message</span>')?>
                <span class="help-block">Images must be in GIF or JPEG format and smaller than <?=round($max_upload_size / 1024)?>MB</>
            </div>
        </div>

        <div class="control-group">
            <?=Form::label('image_url', Lang::line('entries.label_image_url'), array('class' => 'control-label'))?>

            <div class="controls">
                <?=Form::text('image_url', Input::old('image_url'), array('placeholder' => Lang::line('entries.link_to_image')))?>
                <?=$errors->first('image_url', '<span class="error">:message</span>')?>
            </div>
        </div>

		<div class="form-actions">
			<button type="submit" name="submit_entry" class="btn btn-primary">Submit Entry</button>
		</div>

	<?=Form::close()?>
</section>
