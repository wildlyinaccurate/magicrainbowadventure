<ul>
	<li><?=HTML::link('admin', 'Dashboard')?></li>

	<li class="new-section">
		<?=HTML::link('admin/entries/moderate', 'New Entries')?>
		<?php if ($entries_to_moderate > 0): ?>
			<span class="notification"><?=HTML::link('admin/entries/moderate', $entries_to_moderate)?></span>
		<?php endif; ?>
	</li>
	<li><?=HTML::link('admin/entries/all', 'All Entries')?></li>
</ul>
