<p>Don't have an account? <?=HTML::link('account/signup', Lang::line('general.sign_up'))?></p>

<?=Form::open(($return = Input::get('return')) ? "account/login?return={$return}" : 'account/login', 'class="tabbed"')?>

	<?=Form::text('identifier', Input::old('identifier'), array('placeholder' => 'Username / Email Address'))?>
	<?=$errors->first('identifier')?>

	<?=Form::password('password', array('placeholder' => 'Password'))?>
	<?=$errors->first('password')?>

	<div class="controls">
		<button type="submit" name="log-in" id="log-in" class="big"><?=Lang::line('general.log_in')?></button>
	</div>

<?=Form::close()?>
