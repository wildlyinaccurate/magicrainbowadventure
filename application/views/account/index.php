<p>
	<strong><?=random_greeting($user->getUsername())?></strong><br />
	This is your account page. Pretty nifty huh?
</p>

<ul>
	<?php if ($user->isAdmin()): ?>
		<li>
            <?=anchor('admin', 'Admin Dashboard')?>
    
            <?php if ($entries_to_moderate > 0): ?>
                <span class="notification"><?=anchor('admin/entries/moderate', $entries_to_moderate)?></span>
            <?php endif; ?>
        </li>
	<?php endif; ?>

	<li><?=anchor('account/my-entries', lang('my_entries'))?></li>
	<li><?=anchor('account/settings', lang('settings'))?></li>
	<li><?=anchor('account/change-password', lang('change_password'))?></li>
</ul>