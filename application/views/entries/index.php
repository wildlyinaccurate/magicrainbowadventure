<section class="entries">

<?php foreach ($entries as $index => $entry): ?>
	<article class="entry">
		<h3><?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}", $entry->getTitle())?></h3>

		<div class="buttons">
			<?php $active = (Auth::check() && Auth::user()->getFavourites()->contains($entry)); ?>
			<?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}/favourite?favourite=" . (int) ! $active, $entry->getFavouritedBy()->count(), array(
				'class' => ($active) ? 'favourite active' : 'favourite',
				'title' => Lang::line('entries.button_favourite'),
			))?>
		</div>

		<a href="<?=URL::to("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
			<?=HTML::image($entry->getThumbnailUrl('medium'), $entry->getTitle())?>
		</a>

		<p class="description"><?=nl2br($entry->getDescription())?></p>
		<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
	</article>
<?php endforeach; ?>

</section>

<a href="#load-more" class="btn btn-large btn-primary load-more">Load More</a>
<span class="btn btn-large btn-danger no-more-entries">You've seen it all, there's nothing else to load! :(</span>

