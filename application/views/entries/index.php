<section class="entries">

<?php foreach ($entries as $index => $entry): ?>
	<article class="entry">
		<h3><?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}", $entry->getTitle())?></h3>

		<a href="<?=URL::to("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
			<img src="http://dl.dropbox.com/u/<?=Config::get('dropbox::config.access_token.uid')?>/<?=$entry->getFilePath()?>" alt="" />
		</a>

		<div class="entry-rating">
		</div>

		<p class="description"><?=nl2br($entry->getDescription())?></p>
		<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
	</article>
<?php endforeach; ?>

</section>

<a href="#load-more" class="btn btn-large btn-primary load-more">Load More</a>
<span class="btn btn-large btn-danger no-more-entries">You've seen it all, there's nothing else to load! :(</span>

