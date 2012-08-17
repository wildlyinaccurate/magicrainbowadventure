<?php $active = (Auth::check() && Auth::user()->getFavourites()->contains($entry)); ?>

<?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}/favourite?favourite=" . (int) ! $active, $entry->getFavouritedBy()->count(), array(
	'class' => ($active) ? 'active favourite button' : 'favourite button',
	'title' => Lang::line('entries.button_favourite'),
))?>
