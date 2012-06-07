<section class="entry">
	<a href="<?=$entry->getDropboxUrl()?>">
		<?=HTML::image($entry->getThumbnailUrl('xl'), $entry->getTitle())?>
	</a>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</section>
