<section class="admin">

	<table class="table entries">
		<thead>
			<tr>
				<th><?=Lang::line('admin::entries.thumbnail')?></th>
				<th><?=Lang::line('admin::entries.title')?></th>
				<th><?=Lang::line('admin::entries.description')?></th>
			</tr>
		</thead>
	</table>
</section>

<script type="text/html" class="entry-template">
	<tr>
		<td><img src="<%= thumbnail_url %>" /></td>
		<td><%= title %></td>
		<td><%= description %></td>
	</tr>
</script>
