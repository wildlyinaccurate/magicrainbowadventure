<article class="entry">
	<a href="http://dl.dropbox.com/u/<?=Config::get('dropbox::config.access_token.uid')?>/<?=$entry->getFilePath()?>">
		<?=HTML::image($entry->getThumbnailUrl('xl'), $entry->getTitle())?>
	</a>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</article

<section class="comments">
	<h3><?=Lang::line('entries.comments')?></h3>

	<?php if ($entry->getComments()->count() > 0): ?>

	<?php else: ?>
		<p><?=Lang::line('entries.no_comments')?></p>
	<?php endif; ?>
</section>
