<section class="entry">
	<a href="<?=$entry->getDropboxUrl()?>">
		<?=HTML::image($entry->getThumbnailUrl('xl'), $entry->getTitle())?>
	</a>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</section>

<section class="comments">
	<h3><?=Lang::line('entries.comments')?></h3>

	<?php if ($entry->getComments()->count() > 0): ?>

	<?php else: ?>
		<p><?=Lang::line('entries.no_comments')?></p>
	<?php endif; ?>

	<?=View::make('entries/comments-form')->with(array(
		'entry' => $entry,
	))?>
))
</section>
