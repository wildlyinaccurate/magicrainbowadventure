<article class="entry">
	<a href="http://dl.dropbox.com/u/<?=Config::get('dropbox::config.access_token.uid')?>/<?=$entry->getFilePath()?>">
		<?=HTML::image($entry->getThumbnailUrl('xl'), $entry->getTitle())?>
	</a>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</article>
