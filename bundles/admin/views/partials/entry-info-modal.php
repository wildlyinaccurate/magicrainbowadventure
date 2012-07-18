<div class="entry-info modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>
			<span data-bind="text: title"></span>
			<a class="view-link" data-bind="attr: { href: '/' + id() + '/' + url_title() }">&raquo; View</a>
		</h3>
	</div>

	<form data-bind="attr: { class: status }">
		<div class="modal-body">
			<img class="preview" data-bind="attr: { src: thumbnail_url().preview }" />

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

			<ul class="meta control-group">
				<li>
					<span data-bind="if: moderated_by() !== null" class="status">
						<span class="verb" data-bind="text: status"></span> by <a href="#" class="text-overflow" data-bind="text: moderated_by().getDisplayName(), click: moderated_by().edit"></a>
					</span>
					<span data-bind="visible: moderated_by() === null"><?=Lang::line('admin::entries.awaiting_moderation')?></span>
				</li>

				<li>
					Created: <span data-bind="text: created_date().date"></span>
					by <a href="#" class="text-overflow" data-bind="text: user().getDisplayName(), click: user().edit"></a>
				</li>

				<li>
					Last modified: <span data-bind="text: modified_date().date"></span>
				</li>
			</ul>
		</div>

		<div class="modal-footer">
			<div class="left-buttons">
				<button class="btn btn-success" data-bind="attr: { disabled: (approved() == true) }, click: setApproved.bind($data, true)"><?=Lang::line('admin::entries.approve')?></button>
				<button class="btn btn-danger" data-bind="attr: { disabled: (approved() == false) }, click: setApproved.bind($data, false)"><?=Lang::line('admin::entries.decline')?></button>
			</div>

			<button class="btn" data-dismiss="modal"><?=Lang::line('general.close')?></button>
			<button class="btn btn-primary" data-bind="click: save" data-dismiss="modal"><?=Lang::line('admin::general.save')?></button>
		</div>
	</form>
</div>
