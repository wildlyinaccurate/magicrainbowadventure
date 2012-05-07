<?php if ( ! $entry): ?>
	<p>
		Usually this page is reserved for people who submit entries to us.
		Since you haven't submitted anything, we're not really sure why you're here but hey, thanks anyway!
	</p>
<?php else: ?>
	<p>
		Your entry has been submitted and is awaiting moderation.
		You can view the status of your entry <?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}", 'here')?>.
	</p>
<?php endif; ?>
