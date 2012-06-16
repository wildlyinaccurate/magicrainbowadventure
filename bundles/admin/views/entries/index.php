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
	</table>
</section>

<script type="text/html" class="entry-template">
	<% var status = (approved ? 'approved' : (moderated_by ? 'declined' : 'awaiting')) %>

	<tr class="<%= status %>">
		<td><img src="<%= thumbnail_url %>" /></td>
		<td>
			<% if (moderated_by) { %>
				<%= _(status).capitalize() + ' by ' + moderated_by %>
			<% } else { %>
				Awaiting Moderation
			<% } %>
		</td>
		<td><%= title %></td>
		<td><%= description %></td>
		<td><%= created_date.date %></td>
		<td>
			<div class="btn-group">
				<button class="btn dropdown-toggle edit" data-toggle="dropdown">Edit <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<% if (approved) { %>
						<li><a href="#" class="decline">Decline</a></li>
					<% } else { %>
						<li><a href="#" class="approve">Approve</a></li>
					<% } %>
				</ul>
			</div>
		</td>
	</tr>
</script>
