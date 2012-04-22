<section id="entries">

<?php foreach ($entries as $index => $entry): ?>
	<div class="entry">
		<h3><?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}", $entry->getTitle())?></h3>

		<a href="<?=URL::to("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
			<img class="entry-image" src="" alt="" />
		</a>

		<div class="entry-rating">
		</div>

		<p class="description"><?=nl2br($entry->getDescription())?></p>
		<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
	</div>
<?php endforeach; ?>

</section> 
