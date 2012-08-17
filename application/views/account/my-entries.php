<section class="my-entries">
	<p>You have submitted a total of <strong><?=count($entries)?></strong> entries.</p>

	<table class="table">
		<tbody>
		<?php foreach ($entries as $entry): ?>
			<tr>
				<td><img src="<?=$entry->getThumbnailUrl('thumbnail')?>" /></td>
				<td><h4><?=$entry->getTitle()?><h4></td>
				<td><?=$entry->getDescription()?></td>
				<td>
				<?php if ($entry->getFavouritedBy()->count() > 0): ?>
					<?=sprintf(
						Lang::line('entries.favourited_by'),
						'<strong>' . $entry->getFavouritedBy()->count() . '</strong>',
						Str::plural(Lang::line('entries.favourited_by_subject')->get(), $entry->getFavouritedBy()->count())
					)?>
				<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</section>
