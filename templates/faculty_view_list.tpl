<!doctype html>
<html lang="en">
    <head>
	<base href="{{ app_root }}/">
	<meta charset="utf-8">
        {% block headmeta %}
        {% endblock %}

	<title>{% block title %}{% endblock %}</title>

	<link rel="stylesheet" href="www/css/local.css">
	<link rel="stylesheet" href="www/css/list_style.css">
	{% block headlinks %}{% endblock %}

	{% block headjs %}{% endblock %}

	{% block head %}{% endblock %}
	<script src="www/js/script.js"></script>

    </head>
	
    <body>
	<div id="container">
		<div class="controls">
		<a href="faculty/{{ fac.eid }}/assistance">&lt; back</a>
		</div>
		
	{% for sec in fac.sections %}
		<div class="sec">
			<div class="sec_head">
			{{ sec.title }} ( {{ sec.citations|length }} )
			</div>

			<div class="sec_body">
			<ul class="lines">
			{% for line in sec.citations %}
				<li>
				{{ line.revised_text }}
				</li>
			{% endfor %}
			</ul>
			</div> <!-- close div.sec_body -->
	
		</div> <!-- close div.sec -->
	{% endfor %}

	</div>
    </body>
</html>

