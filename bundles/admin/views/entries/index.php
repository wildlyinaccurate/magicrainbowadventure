<section class="admin">
	<table class="entries table table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th><?=Lang::line('admin::entries.status')?></th>
				<th><?=Lang::line('admin::entries.title')?></th>
				<th><?=Lang::line('admin::entries.description')?></th>
				<th><?=Lang::line('admin::entries.date_created')?></th>
				<th><?=Lang::line('admin::entries.actions')?></th>
			</tr>
		</thead>
		<tbody data-bind="foreach: entries">
			<tr data-bind="attr: { class: status }">
				<td>
					<a href="#" data-bind="click: edit">
						<img data-bind="attr: { src: thumbnail_url().thumbnail, title: '<?=Lang::line('admin::entries.original_size')?>' + originalSize() }" />
					</a>
				</td>
				<td class="status">
					<span data-bind="if: moderated_by() !== null">
						<span class="verb" data-bind="text: status"></span> by <a href="#" class="text-overflow" data-bind="text: moderated_by().getDisplayName(), click: moderated_by().edit"></a>
					</span>
					<span data-bind="visible: moderated_by() === null"><?=Lang::line('admin::entries.awaiting_moderation')?></span>
				</td>
				<td data-bind="text: title"></td>
				<td data-bind="text: description"></td>
				<td>
					<span data-bind="text: created_date().date"></span> by <a href="#" class="text-overflow" data-bind="text: user().getDisplayName(), click: user().edit"></a>
				</td>
				<td class="actions">
					<button class="btn btn-success" data-bind="visible: moderated_by() === null, click: toggleApproved"><?=Lang::line('admin::entries.approve')?></button>
					<button class="btn btn-danger" data-bind="visible: moderated_by() === null, click: toggleApproved"><?=Lang::line('admin::entries.decline')?></button>
					<button class="btn" data-bind="click: edit"><?=Lang::line('admin::entries.edit')?></button>
				</td>
			</tr>
		</tbody>
	</table>

	<?=$paginator->links()?>
</section>

<script type="text/javascript">
	MagicRainbowAdmin_API_perPage = <?=$per_page?>;
</script>

<?=View::make('admin::partials/entry-info-modal')?>
<?=View::make('admin::partials/user-info-modal')?>
