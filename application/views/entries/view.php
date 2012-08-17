<section class="entry">
	<?=View::make('entries/_buttons', array('entry' => $entry))?>

	<a href="<?=$entry->getThumbnailUrl()?>">
		<?=HTML::image($entry->getThumbnailUrl('large'), $entry->getTitle())?>
	</a>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</section>
