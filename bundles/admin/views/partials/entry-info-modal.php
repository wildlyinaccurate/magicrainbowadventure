<div class="entry-info modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 data-bind="text: title"></h3>
	</div>

	<form>
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
		</div>

		<div class="modal-footer">
			<button class="btn" data-dismiss="modal"><?=Lang::line('general.close')?></button>
			<button class="btn btn-primary" data-bind="click: save" data-dismiss="modal"><?=Lang::line('admin::general.save')?></button>
		</div>
	</form>
</div>
