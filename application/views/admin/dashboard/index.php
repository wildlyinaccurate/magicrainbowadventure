<ul>
	<?php if ($entries_to_moderate > 0): ?>
		<li><?=HTML::link('admin/entries/moderate', "{$entries_to_moderate} entries awaiting moderation")?></li>
	<?php endif; ?>
</ul>
