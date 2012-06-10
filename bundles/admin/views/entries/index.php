<section class="admin">

	<table class="table">
		<thead>
			<tr>
				<th><?=Lang::line('admin::entries.thumbnail')?></th>
				<th><?=Lang::line('admin::entries.title')?></th>
				<th><?=Lang::line('admin::entries.description')?></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($entries as $entry): ?>
			<tr class="entry" data-entry-id="<?=$entry->getId()?>">
				<td><img src="<?=$entry->getThumbnailUrl('medium')?>" /></td>
				<td><?=$entry->getTitle()?></td>
				<td><?=$entry->getDescription()?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</section>

<script type="text/javascript">
$(document).ready(function() {

	<?php foreach ($entries as $entry): ?>

		var entry = new $MRA.Models.Entry({
			id: <?=$entry->getId()?>,
			user_id: <?=$entry->getUser()->getId()?>,
			moderated_by_id: <?=$entry->getModeratedBy()->getId()?>,
			title: '<?=$entry->getTitle()?>',
			url_title: '<?=$entry->getUrlTitle()?>',
			file_path: '<?=$entry->getFilePath()?>',
			hash: '<?=$entry->getHash()?>',
			description: '<?=$entry->getDescription()?>',
			approved: Boolean(<?=(int) $entry->getApproved()?>),
			created_date: new Date('<?=$entry->getCreatedDate()->format('c')?>'),
			modified_date: new Date('<?=$entry->getModifiedDate()->format('c')?>'),
			type: '<?=$entry->getType()?>'
		});

	<?php endforeach; ?>

});
</script>
