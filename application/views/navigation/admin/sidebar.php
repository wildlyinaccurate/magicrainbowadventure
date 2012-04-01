<ul>
	<li><?=anchor('admin', 'Dashboard')?></li>

	<li class="new-section">
		<?=anchor('admin/entries/moderate', 'New Entries')?>
		<?php if ($entries_to_moderate > 0): ?>
			<span class="notification"><?=anchor('admin/entries/moderate', $entries_to_moderate)?></span>
		<?php endif; ?>
	</li>
	<li><?=anchor('admin/entries/all', 'All Entries')?></li>
</ul>