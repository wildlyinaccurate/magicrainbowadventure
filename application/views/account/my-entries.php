<p>You have submitted a total of <strong><?=count($entries)?></strong> entries.</p>

<div id="entries" class="my-entries">

<?php foreach ($entries as $entry): ?>
	<div class="entry">
		<h3><?=HTML::link("{$entry->getId()}/{$entry->getUrlTitle()}", $entry->getTitle())?></h3>

		<?php if ( ! $entry->isApproved() && ! $entry->getModeratedBy()): ?>
            <p class="entry-status">This entry is awaiting moderation.</p>
		<?php elseif ( ! $entry->isApproved()): ?>
            <p class="entry-status">This entry has been declined.</p>
		<?php endif; ?>

		<a href="<?=URL::to("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
			<img class="thumbnail" src="<?=URL::to("entry/thumbnail/{$entry->getId()}")?>" alt="" />
		</a>

		<p class="description"><?=nl2br($entry->getDescription())?></p>
	</div>
<?php endforeach; ?>

</div>
