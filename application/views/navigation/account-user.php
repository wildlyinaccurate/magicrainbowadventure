<ul>
	<li>
        <?php if ($entries_to_moderate > 0): ?>
            <?=HTML::link('account', "My Account ({$user->getUsername()})" . ' <span class="notification"> ' . $entries_to_moderate . '</span>')?>
        <?php else: ?>
            <?=HTML::link('account', "My Account ({$user->getUsername()})")?>
        <?php endif; ?>
    </li>
	<li><?=HTML::link('account/logout', 'Logout')?></li>
</ul>
