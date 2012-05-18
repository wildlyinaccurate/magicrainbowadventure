<section class="signup">
	<p>Already have an account? <?=HTML::link('account/login', Lang::line('general.log_in'))?></p>

	<?=Form::open('account/signup', 'POST', array('class' => 'form-horizontal'))?>

		<div class="control-group">
			<?=Form::label('username', Lang::line('account.label_username'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('username', Input::old('username'), array('maxlength' => 32))?>
				<?=$errors->first('username', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('password', Lang::line('account.label_password'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::password('password')?>
				<?=$errors->first('password', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('password_confirm', Lang::line('account.label_password_confirm'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::password('password_confirm')?>
				<?=$errors->first('password_confirm', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('email', Lang::line('account.label_email'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('email', Input::old('email'), array('maxlength' => 255))?>
				<?=$errors->first('email', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('display_name', Lang::line('account.label_display_name'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('display_name', Input::old('display_name'), array('maxlength' => 160))?>
				<?=$errors->first('display_name', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" name="sign_up" class="btn btn-primary">Sign Up</button>
		</div>

	<?=Form::close()?>
</section>
