<article class="entry">
	<p class="description"><?=$entry->getDescription()?></p>

    <?php if ( ! $entry->getModeratedBy()): ?>
        <p class="entry-status">This entry is awaiting moderation.</p>
    <?php else: ?>
        <p class="entry-status">This entry has been declined.</p>
    <?php endif; ?>
</article>
