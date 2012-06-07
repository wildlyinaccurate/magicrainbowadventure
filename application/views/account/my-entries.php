<section class="my-entries">
	<p>You have submitted a total of <strong><?=count($entries)?></strong> entries.</p>

	<table class="table">
		<tbody>

		<?php foreach ($entries as $entry): ?>
			<tr>
				<td><img src="<?=$entry->getThumbnailUrl('medium')?>" /></td>
				<td><?=$entry->getTitle()?></td>
				<td><?=$entry->getDescription()?></td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>
</section>
