<p>Don't have an account? <?=HTML::link('account/signup', lang('sign_up'))?></p>

<?php if ($validate && ! $login): ?>
	<div class="error">The username / email and password you entered were incorrect.</div>
<?php endif; ?>

<?=form_open(($return = $this->input->get('return')) ? "account/login?return={$return}" : 'account/login', 'class="tabbed"')?>
<div>
		<label for="identifier">Username / Email</label>
		<input type="text" name="identifier" id="identifier" value="<?=set_value('identifier')?>" />
		<?=form_error('identifier')?>
	</div>

	<div>
		<label for="password">Password</label>
		<input type="password" name="password" id="password" />
		<?=form_error('password')?>
	</div>

	<div class="controls">
		<button type="submit" name="log-in" id="log-in" class="big">Log In</button>
	</div>
<?=form_close()?>
