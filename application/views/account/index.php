<?=Session::get('message')?>

<nav>
	<ul>
		<li><?=HTML::link('account/my-entries', Lang::line('account.my_entries'))?></li>
		<li><?=HTML::link('account/settings', Lang::line('account.settings'))?></li>
	</ul>
</nav>
