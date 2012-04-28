<?=Form::open('account/settings', 'method="post" class="tabbed')?>

<div>
	<label for="username">Your username</label>
	<input type="text" name="username" id="username" maxlength="32" value="<?=$user->getUsername()?>" disabled />
	<?=form_error('username')?>
</div>

<div>
	<label for="email">Your email address</label>
	<input type="text" name="email" id="email" maxlength="255" value="<?=Input::old('email', $user->getEmail())?>" />
	<?=form_error('email')?>
</div>

<div>
	<label for="display_name">Display name</label>
	<input type="text" name="display_name" id="display_name" maxlength="160" value="<?=Input::old('display_name', $user->getDisplayName())?>" />
	<span class="note">Optional</span>
	<?=form_error('display_name')?>
</div>

<div>
	<label for="country">Country</label>
	<select name="country" id="country">
		<?php foreach ($countries as $key => $country): ?>
		<option value="<?=$country->getIso()?>"<?=set_select('country', $country->getIso(), $country->getIso() == $user->getCountry()->getIso())?>><?=$country->getName()?></option>
		<?php endforeach; ?>
	</select>
	<?=form_error('country')?>
</div>

<div>
	<label for="language">Preferred language</label>
	<select name="language" id="language">
		<?php foreach ($languages as $iso => $language): ?>
		<option value="<?=$iso?>"<?=set_select('language', $iso, $iso == $user->getLanguage())?>><?=$language['name']?></option>
		<?php endforeach; ?>
	</select>
	<?=form_error('country')?>
</div>

<div id="controls">
	<?=HTML::link('account', Lang::line('general.cancel'), 'class="big negative button"')?>
	<button type="submit" name="save_settings" id="save_settings" class="big button">Save Settings</button>
</div>
<?=Form::close()?>
