{% extends "framework/bootstrap.tpl" %} 

{% block headlinks %}
<link rel="stylesheet" href="www/css/colorbox.css">
{% endblock %}

{% block headjs %}
<script src="www/js/textinputs_jquery.js"></script>
<script src="www/js/script.js"></script>
{{ parent() }}
{% endblock %}


{% block content %}
<div class="inner-cont"> 
<div id="file_upload">
<h1>Select citations from uploaded file</h1>
<a href="#instructions" id="inline">view instructions</a>
<a id="lines" class="offset6 btn btn-info" href="file/{{ fac.eid }}/lines/{{ file.id }}/">add citations to record</a>

    <div class="file_inst">
	file name: <strong>{{ file.orig_name }}</strong>
	<div class="controls">
	  <a id="revert" href="file/{{ fac.eid }}/rawtext/{{ file.id }}/">revert to original text</a> |
	  <a href="file/{{ fac.eid }}/file/{{ file.id }}">download original file</a> | 
	  <a href="faculty/{{ fac.eid }}/upload">return to my record</a>
	</div>
    </div>

<div id="ajaxMessage" class="hide"></div>
<form id="text_form" action="file/{{ fac.eid }}/text/{{ file.id }}">
	{% if file.edited_text %}
	<textarea class="cv span11">{{ file.edited_text }}</textarea>
	{% else %}
	<textarea class="cv span11">{{ file.rawtext }}</textarea>
	{% endif %}
</form>

	<div class="hide" id="instructions">
	<h1>Instructions</h1>
	<p>Please note: The text field will auto-update as you type.</p>
	<ol>
		<li>Remove all text except the citations you want to add to your record</li>
		<li>Place at least one blank line between citations</li>
		<li>When finished, click on "add citations to record"</li>
		<li>Imported citations will appear in your record in the "uncategorized" section</li>
	</ol>
	</div><!-- instructions --> 
</div><!-- file_upload -->
</div><!-- inner-cont -->
{% endblock content %}
