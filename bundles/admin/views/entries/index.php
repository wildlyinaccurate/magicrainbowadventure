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
				<td><img data-bind="attr: { src: thumbnail_url().medium }" /></td>
				<td class="status">
					<span data-bind="visible: moderated_by">
						<span class="verb" data-bind="text: status"></span> by <a href="#" data-bind="text: moderatorDisplayName"></a>
					</span>
					<span data-bind="visible: moderated_by() == null"><?=Lang::line('admin::entries.awaiting_moderation')?></span>
				</td>
				<td data-bind="text: title"></td>
				<td data-bind="text: description"></td>
				<td data-bind="text: created_date().date"></td>
				<td>
					<button class="btn btn-success" data-bind="visible: approved() == 0, click: toggleApproved"><?=Lang::line('admin::entries.approve')?></button>
					<button class="btn btn-danger" data-bind="visible: approved, click: toggleApproved"><?=Lang::line('admin::entries.decline')?></button>
					<button class="btn" data-bind="click: edit"><?=Lang::line('admin::entries.edit')?></button>
				</td>
			</tr>
		</tbody>
	</table>

	<?=$paginator->links(1)?>
</section>

<div class="entry-info modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 data-bind="text: title"></h3>
	</div>

	<form>
		<div class="modal-body">
			<img class="preview" data-bind="attr: { src: thumbnail_url().large }" />

			<div class="control-group">
				<?=Form::label('title', Lang::line('admin::entries.title'), array('class' => 'control-label'))?>

				<div class="controls">
					<?=Form::text('title', '', array('maxlength' => 140, 'data-bind' => 'value: title'))?>
				</div>
			</div>

			<div class="control-group">
				<?=Form::label('entry_image', Lang::line('admin::entries.description'), array('class' => 'control-label'))?>

				<div class="controls">
					<?=Form::textarea('description', '', array('data-bind' => 'value: description'))?>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button class="btn" data-dismiss="modal"><?=Lang::line('general.close')?></button>
			<button class="btn btn-primary" data-bind="click: save" data-dismiss="modal" data-bind="click: update"><?=Lang::line('admin::general.save')?></button>
		</div>
	</form>
</div>
