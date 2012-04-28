<p>
	<strong><?=random_greeting($user->getUsername())?></strong><br />
	This is your account page. Pretty nifty huh?
</p>

<ul>
	<?php if ($user->isAdmin()): ?>
		<li>
            <?=HTML::link('admin', 'Admin Dashboard')?>
    
            <?php if ($entries_to_moderate > 0): ?>
                <span class="notification"><?=HTML::link('admin/entries/moderate', $entries_to_moderate)?></span>
            <?php endif; ?>
        </li>
	<?php endif; ?>

	<li><?=HTML::link('account/my-entries', Lang::line('general.my_entries'))?></li>
	<li><?=HTML::link('account/settings', Lang::line('general.settings'))?></li>
	<li><?=HTML::link('account/change-password', Lang::line('general.change_password'))?></li>
</ul>
