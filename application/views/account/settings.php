<section class="account-settings">
	<?=Form::open('account/settings', 'POST', array('class' => 'form-horizontal'))?>

		<div class="control-group">
			<?=Form::label('username', Lang::line('account.label_username'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('username', Input::old('username', $user->getUsername()), array('maxlength' => 32))?>
				<?=$errors->first('username', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('email', Lang::line('account.label_email'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('email', Input::old('email', $user->getEmail()), array('maxlength' => 255))?>
				<?=$errors->first('email', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="control-group">
			<?=Form::label('display_name', Lang::line('account.label_display_name'), array('class' => 'control-label'))?>

			<div class="controls">
				<?=Form::text('display_name', Input::old('display_name', $user->getDisplayName(false)), array('maxlength' => 40))?>
				<?=$errors->first('display_name', '<span class="error">:message</span>')?>
			</div>
		</div>

		<div class="form-actions">
			<?=HTML::link('account', Lang::line('general.cancel'), array('class' => 'btn'))?>
			<button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>

			<?php if (Session::has('success_message')): ?>
				<span class="success"><?=Session::get('success_message')?></span>
			<?php endif; ?>
		</div>


	<?=Form::close()?>
</section>
