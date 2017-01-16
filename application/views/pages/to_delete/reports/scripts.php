<script type="text/template" id="tmpUserClientRep">
	<tr>
		<td><%= user %></td>
		<td><%= client %></td>
		<td><%= project_code %></td>
		<td><%= time %></td>
	</tr>
</script>

<script type="text/template" id="tmpClientUserRep">
	<tr>
		<td><%= client %></td>
		<td><%= project_code %></td>
		<td><%= user %></td>
		<td><%= time %></td>
	</tr>
</script>

<script type="text/template" id="tmpMatrix">
	<tr>
		<td><%= time_row.user_name %></td>
		<% 
			delete time_row.user_name
			
		%>
		<% 
		var user_row_time_total = 0;
		_.each(time_row,function(item){ 
		item = +item
		user_row_time_total= user_row_time_total + item;
		%>
			<td> <%= item %> </td>
		<% }); %>
		<td><%= user_row_time_total %></td>
	</tr>
</script>