<p>Already have an account? <?=HTML::link('account/login', Lang::line('general.log_in'))?></p>

<?=Form::open('account/signup', 'method="post" class="tabbed')?>
<div>
	<label for="username">Pick a username</label>
	<input type="text" name="username" id="username" maxlength="32" value="<?=Input::old('username')?>" />
	<?=form_error('username')?>
</div>

<div>
	<label for="password">Choose your password</label>
	<input type="password" name="password" id="password" />
	<?=form_error('password')?>
</div>

<div>
	<label for="password_confirm">Confirm your password</label>
	<input type="password" name="password_confirm" id="password_confirm" />
</div>

<div>
	<label for="email">Your email address</label>
	<input type="text" name="email" id="email" maxlength="255" value="<?=Input::old('email')?>" />
	<?=form_error('email')?>
</div>

<div>
	<label for="display_name">Display name</label>
	<input type="text" name="display_name" id="display_name" maxlength="160" value="<?=Input::old('display_name')?>" />
	<span class="note">Optional</span>
	<?=form_error('display_name')?>
</div>

<div>
	<label for="country">Country</label>
	<select name="country" id="country">
		<?php foreach ($countries as $key => $country): ?>
		<option value="<?=$country->getIso()?>"<?=$selected_country == $country->getIso() ? ' selected="selected"' : ''?>><?=$country->getName()?></option>
		<?php endforeach; ?>
	</select>
	<?=form_error('country')?>
</div>

<div class="controls">
	<button type="submit" name="sign_up" id="sign_up" class="big">Sign Up</button>
</div>
<?=Form::close()?>
