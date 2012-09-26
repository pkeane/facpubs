{% extends "framework/bootstrap.tpl" %} 

{% block content %}

<h1>Help Files</h1>
<ul id="help_files" class="help_files">
	{% for file in files %}
	<li><a href="help/{{ file }}" class="help">{{ file }}</a></li>
	{% endfor %}
</ul>

{% endblock content %}
