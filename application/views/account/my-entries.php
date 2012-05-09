<section class="my-entries">
	<p>You have submitted a total of <strong><?=count($entries)?></strong> entries.</p>

	<table class="table">
		<tbody>

		<?php foreach ($entries as $entry): ?>
			<tr>
				<td><img src="<?='data:image/jpeg;base64,' . $entry->getThumbnail('large')?>" /></td>
				<td><?=$entry->getTitle()?></td>
				<td><?=$entry->getDescription()?></td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>
</section>
