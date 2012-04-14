<table class="entries">

	<thead>
		<tr>
			<th class="file">Preview</th>
			<th class="title">Title</th>
			<th class="description">Description</th>
			<th class="author">Author</th>
			<th class="date">Date</th>
			<th class="actions">Approve / Decline</th>
		</tr>
	</thead>

	<tbody>

	<?php if (count($entries) == 0): ?>
		<tr>
			<td colspan="6">There are no entries to display.</td>
		</tr>
	<?php else: ?>
		<?php foreach ($entries as $entry): ?>
			<tr id="entry-<?=$entry->getId()?>">
				<td class="file">
					<a href="<?=URL::to("{$entry->getId()}/{$entry->getUrlTitle()}")?>">
						<img class="thumbnail" src="<?=URL::to("entry/thumbnail/{$entry->getId()}")?>" width="<?=$thumb_width?>" height="<?=$thumb_height?>" alt="" />
					</a>
				</td>
				<td class="title editable"><?=$entry->getTitle()?></td>
				<td class="description editable"><?=word_limiter($entry->getDescription())?></td>
                <td class="author"><?=$entry->getUser()->getUsername()?></td>
				<td class="date"><?=$entry->getCreatedDate()->format('d M H:i')?></td>
				<td class="actions">
					<a class="positive moderate button" href="<?=URL::to("admin/entries/approve/{$entry->getId()}")?>"><span class="check icon"></span>Approve</a>
					<a class="negative moderate button" href="<?=URL::to("admin/entries/decline/{$entry->getId()}")?>"><span class="cross icon"></span>Decline</a>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>

	</tbody>

</table>

<?php if (isset($this->pagination) && $this->pagination->create_links() != ''): ?>
	<div id="pagination">
		<?=$this->pagination->create_links()?>
	</div>
<?php endif; ?>
