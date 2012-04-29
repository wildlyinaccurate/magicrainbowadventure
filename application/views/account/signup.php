<section class="signup">
	<p>Already have an account? <?=HTML::link('account/login', Lang::line('general.log_in'))?></p>

	<?=Form::open('account/signup', 'POST')?>

		<div class="control-group">
			<?=Form::text('username', Input::old('title'), array('maxlength' => 32, 'placeholder' => Lang::line('account.label_username')))?>
			<?=$errors->first('username', '<span class="error">:message</span>')?>
		</div>

		<div class="control-group">
			<?=Form::password('password', array('placeholder' => Lang::line('account.label_password')))?>
			<?=$errors->first('password', '<span class="error">:message</span>')?>
		</div>

		<div class="control-group">
			<?=Form::password('password_confirm', array('placeholder' => Lang::line('account.label_password_confirm')))?>
			<?=$errors->first('password_confirm', '<span class="error">:message</span>')?>
		</div>

		<div class="control-group">
			<?=Form::text('email', Input::old('email'), array('maxlength' => 255, 'placeholder' => Lang::line('account.label_email')))?>
			<?=$errors->first('email', '<span class="error">:message</span>')?>
		</div>

		<div class="control-group">
			<?=Form::text('display_name', Input::old('display_name'), array('maxlength' => 160, 'placeholder' => Lang::line('account.label_display_name')))?>
			<?=$errors->first('display_name', '<span class="error">:message</span>')?>
		</div>

		<button type="submit" name="sign_up" class="btn btn-primary">Sign Up</button>

	<?=Form::close()?>
</section>
