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
			<tr class="entry">
				<td><img src="<?=$entry->getThumbnailUrl('medium')?>" /></td>
				<td><?=$entry->getTitle()?></td>
				<td><?=$entry->getDescription()?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</section>
