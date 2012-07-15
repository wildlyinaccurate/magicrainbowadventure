<div class="user-info modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 data-bind="text: display_name"></h3>
	</div>

	<form>
		<div class="modal-body">
			<p>
				<strong><?=Lang::line('admin::users.username')?>:</strong>
				<span data-bind="text: username"></span>
			</p>

			<p>
				<strong><?=Lang::line('admin::users.display_name')?>:</strong>
				<span data-bind="text: display_name"></span>
			</p>

			<p>
				<strong><?=Lang::line('admin::users.email')?>:</strong>
				<span data-bind="text: email"></span>
			</p>
		</div>

		<div class="modal-footer">
			<button class="btn" data-dismiss="modal"><?=Lang::line('general.close')?></button>
		</div>
	</form>
</div>
