<form class="edit_form" action="faculty/{{ fac.eid  }}/citation/{{ citation.id  }}/form" method="post">
	<label for="text"></label>
	<textarea class="revision span9" name="revised_text" rows="4">{{ citation.revised_text  }}</textarea>

	<p>
	<label class="year small" for="section_id"><a href="help/sections" class="help_link in_form">section:</a></label>
	<select name="section_id" class="span5">
		{# <option value="">select section header:</option> #}
		<option value="">[uncategorized]</option>
		{% for sh in sections %}
		<option {% if sh.id == citation.section_id %}selected{% endif %} value="{{ sh.id }}">{{ sh.text|slice(0,50) }}</option>
		{% endfor %}
	</select>
	</p>

	<p class="bottom">		
	<label class="small radio creative_work"><a href="help/creative_work" class="help_link in_form">creative work </a></label>
	{% if citation.is_creative %}
		<input type="radio" name="is_creative" value="1" checked="checked"> yes &nbsp;&nbsp;
		<input type="radio" name="is_creative" value="0"> no	
	{% else %}
		<input type="radio" name="is_creative" value="1"> yes &nbsp;&nbsp;
		<input type="radio" name="is_creative" value="0" checked="checked"> no
	{% endif %}
	<em class="note">"creative work" usually applies to the performing and fine arts</em>  
	</p>

	
	<div class="row-fluid"><div class="span12"><div class="row-fluid">
	<input type="hidden" name="redirect" value="{{ redirect }}">
	<input type="submit" class="btn btn-success" value="update">
	<input class="targetLine{{ citation.id }} btn" type="button" value="cancel">
	</div></div></div>
</form>
<form id="edit_form" action="faculty/{{ fac.eid }}/citation/{{ citation.id }}" method="delete">
	<input type="submit" class="btn btn-inverse offset4" value="delete citation">
</form>


