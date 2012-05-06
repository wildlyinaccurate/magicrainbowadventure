<section class="account">
	<?=Session::get('message')?>

	<ul>
		<li><?=HTML::link('account/my-entries', Lang::line('general.my_entries'))?></li>
		<li><?=HTML::link('account/settings', Lang::line('general.settings'))?></li>
		<li><?=HTML::link('account/change-password', Lang::line('general.change_password'))?></li>
	</ul>
</section>
