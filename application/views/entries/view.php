<article class="entry">
	<img src="http://dl.dropbox.com/u/<?=Config::get('dropbox::config.access_token.uid')?>/<?=Config::get('magicrainbowadventure.dropbox_base_path')?>/<?=$entry->getFilePath()?>" alt="" />

	<p class="description"><?=nl2br($entry->getDescription())?></p>
	<p class="submitted-by">Submitted by <?=$entry->getUser()->getDisplayName()?> on <?=$entry->getCreatedDate()->format('d F Y')?></p>
</article>
