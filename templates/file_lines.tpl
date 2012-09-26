<form method="post" action="faculty/{{ fac.eid }}/lines/file/{{ file.id }}">
	<div class="controls">
		<input type="submit" value="Import Checked Citations">
		<input id="closeColorboxButton" type="button" value="cancel">
	</div>
	<h2>{{ lines|length }} headers & citations</h2>
	<div class="clear"></div>
	<input id="check_uncheck_lines" type="checkbox" checked> <span class="check_uncheck_lines">check/uncheck all</span>
	<ul class="lines">
		{% for i,line in lines %}
		<li>
		<span class="num">{{ i+1 }}</span>
		<input type="checkbox" checked name="lines[]" value="{{ i }}">
		{{ line }}
		</li>
		{% endfor %}
	</ul>
</form>

