<section class="entry">
	<a href="<?=$entry->getDropboxUrl()?>">
		<?=HTML::image($entry->getThumbnailUrl('xl'), $entry->getTitle())?>
	</a>

	<div class="buttons">
		<?php $active = (Auth::check() && Auth::user()->getFavourites()->contains($entry)); ?>
		<?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}/favourite?favourite=" . (int) ! $active, $entry->getFavouritedBy()->count(), array(
		'class' => ($active) ? 'favourite active' : 'favourite',
		'title' => Lang::line('entries.button_favourite'),
		'data-placement' => 'left',
	))?>
	</div>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</section>
