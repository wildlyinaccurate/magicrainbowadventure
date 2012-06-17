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
				<td>
					<span data-bind="visible: moderated_by">
						<span data-bind="text: status"></span> by <a href="#" data-bind="text: moderatorDisplayName"></a>
					</span>
					<span data-bind="visible: moderated_by() == null"><?=Lang::line('admin::entries.awaiting_moderation')?></span>
				</td>
				<td data-bind="text: title"></td>
				<td data-bind="text: description"></td>
				<td data-bind="text: created_date().date"></td>
				<td>
					<button class="btn btn-success" data-bind="visible: approved() == 0, click: toggleApproved"><?=Lang::line('admin::entries.approve')?></button>
					<button class="btn btn-danger" data-bind="visible: approved, click: toggleApproved"><?=Lang::line('admin::entries.decline')?></button>
					<button class="btn"><?=Lang::line('admin::entries.edit')?></button>
				</td>
			</tr>
		</tbody>
	</table>
</section>
