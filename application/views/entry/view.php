<article class="entry">
	<img src="http://dl.dropbox.com/u/<?=Config::get('dropbox::config.access_token.uid')?>/<?=Config::get('magicrainbowadventure.dropbox_base_path')?>/<?=$entry->getPath()?>" alt="" />

    <div class="entry-rating">
        <a href="<?=URL::to("rate/cute/{$entry->getId()}")?>" class="rate-button cute <?=($entry_rating->getCute()) ? 'active' : ''?>">Cute!</a>
        <a href="<?=URL::to("rate/funny/{$entry->getId()}")?>" class="rate-button funny <?=($entry_rating->getFunny()) ? 'active' : ''?>">Funny!</a>
    </div>

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</article>
