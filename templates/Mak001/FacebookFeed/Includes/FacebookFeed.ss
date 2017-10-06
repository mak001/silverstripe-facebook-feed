<% if $FacebookFeed(2).Posts %>
	<% loop $FacebookFeed(2).Posts %>
		<div class="facebook-post">
			<p class="facebook-post-date"><em>$Date</em></p>
			<% if $Title %>
				<h3 class="facebook-post-title">
					<a href="$Link" target="_blank">$Title</a>
				</h3>
			<% end_if %>
			<% if $Image %>
				<img src="$Image" class="facebook-post-image" />
			<% end_if %>
			<p class="facebook-post-content">$Content.RAW</p>
			<a href="$Link" target="_blank">Read More</a>
		</div>
	<% end_loop %>

	<%-- TODO - remove? --%>
<% else_if $FacebookFeed.Error %>
	<p>$FacebookFeed.Error</p>
<% end_if %>
