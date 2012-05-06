<section class="login">
    <p class="error"><?=$error?></p>

	<?=Form::open('account/login', 'POST', array('class' => 'form-inline'))?>

        <?=Form::hidden('referrer', Input::old('referrer', $referrer))?>
		<?=Form::text('identifier', Input::old('identifier'), array('placeholder' => 'Username / Email Address'))?>
		<?=Form::password('password', array('placeholder' => 'Password'))?>

		<button type="submit" name="login" class="btn btn-primary"><?=Lang::line('general.log_in')?></button>

	<?=Form::close()?>

	<p>Don't have an account? <?=HTML::link('account/signup', Lang::line('general.sign_up'))?></p>
</section>
