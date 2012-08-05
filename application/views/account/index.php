<section class="account">
	<?=Session::get('message')?>

	<nav>
		<ul>
			<?php if ($user->isAdmin()): ?>
				<li><?=HTML::link('admin', Lang::line('account.admin_dashboard'))?>
			<?php endif; ?>

			<li><?=HTML::link('account/my-entries', Lang::line('account.my_entries'))?></li>
			<li><?=HTML::link('account/settings', Lang::line('account.settings'))?></li>
		</ul>
	</nav>
</section>
