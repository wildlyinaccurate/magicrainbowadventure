<div id="entries">

<?php foreach ($entries as $index => $entry): ?>
	<div class="entry">
		<h3><?=anchor("{$entry->getId()}/{$entry->getUrlTitle()}", $entry->getTitle())?></h3>

		<a href="<?=site_url("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
			<img class="entry-image" src="<?=dropbox_public_url($entry)?>" alt="" />
		</a>

		<div class="entry-rating">
			<a href="<?=site_url("rate/cute/{$entry->getId()}?value=" . (int) ! $entry_ratings[$index]->getCute())?>" class="rate-button cute <?=($entry_ratings[$index]->getCute()) ? 'active' : ''?>">Cute!</a>
			<a href="<?=site_url("rate/funny/{$entry->getId()}?value=" . (int) ! $entry_ratings[$index]->getFunny())?>" class="rate-button funny <?=($entry_ratings[$index]->getFunny()) ? 'active' : ''?>">Funny!</a>
		</div>

		<p class="description"><?=nl2br($entry->getDescription())?></p>
		<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
	</div>
<?php endforeach; ?>

</div>

<div id="pagination">
	<h4>Continue the magic rainbow adventure!</h4>

	<div class="pagination-links">
		<?=$this->pagination->create_links()?>
	</div>
</div>