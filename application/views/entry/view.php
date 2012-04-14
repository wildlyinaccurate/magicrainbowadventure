<div class="entry">
	<img class="entry-image" src="<?=dropbox_public_url($entry)?>" alt="" />

    <div class="entry-rating">
        <a href="<?=URL::to("rate/cute/{$entry->getId()}")?>" class="rate-button cute <?=($entry_rating->getCute()) ? 'active' : ''?>">Cute!</a>
        <a href="<?=URL::to("rate/funny/{$entry->getId()}")?>" class="rate-button funny <?=($entry_rating->getFunny()) ? 'active' : ''?>">Funny!</a>
    </div>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</div>
