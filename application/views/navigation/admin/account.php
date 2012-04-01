<ul>
	<li>
        <?php if ($entries_to_moderate > 0): ?>
            <?=anchor('account', "My Account ({$user->getUsername()})" . ' <span class="notification"> ' . $entries_to_moderate . '</span>')?>
        <?php else: ?>
            <?=anchor('account', "My Account ({$user->getUsername()})")?>
        <?php endif; ?>
    </li>
	<li><?=anchor('account/logout', 'Logout')?></li>
</ul>