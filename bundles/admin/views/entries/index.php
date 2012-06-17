<section class="admin">

	<table class="table entries">
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
			<tr>
				<td><img data-bind="attr: { src: thumbnail_url }" /></td>
				<td data-bind="text: statusText"></td>
				<td data-bind="text: title"></td>
				<td data-bind="text: description"></td>
				<td data-bind="text: created_date().date"></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</section>
