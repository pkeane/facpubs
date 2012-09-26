{% extends "framework/bootstrap.tpl" %}

{% block content %}

<div class="admin container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <h3>Administration</h3>
            <ul>
                <li><a href="admin">my user settings</a></li>
            {% if request.user.is_admin %}
                <li><a href="admin/users">list users</a></li>
                <li class="active"><a href="admin/directory">add a user</a></li>
                <li><a href="faculty">faculty list</a></li>
                <li><a href="admin/faculty/form">add faculty by EID</a></li> 
                <li><a href="admin/citation_count">citation count</a></li>
                <li>
                    <form action="faculty/search">
		    <input type="text" name="q" class="input-small">
		    <input type="submit" value="search faculty">
		    </form>
		</li>
		
	    {% endif %}
            </ul>
        </div>
        <div class="span8">
            <h2>Find User in UT Directory</h2>
            <form>
                <label for="lastname">last name or UT EID:</label>
                <input type="text" name="lastname" value="{{ lastname }}">
                <input type="submit" value="search">
            </form>
						{% if results %}
            <h3>Results for {{ lastname }} as Last Name</h3>
            <ul class="results">
                {% for person in results %}
                <li><a href="admin/add_user_form/{{ person.eid }}">{{ person.name }} : {{ person.eid }} ({{ person.unit }})</a></li>
                {% endfor %}
            </ul>
						{% else %}
            <h3>No results for {{ lastname }} as Last Name</h3>
						{% endif %}
						{% if results_eid %}
            <h3>Results for {{ lastname }} as UT EID</h3>
            <ul class="results">
                {% for person in results_eid %}
                <li><a href="admin/add_user_form/{{ person.eid }}">{{ person.name }} : {{ person.eid }} ({{ person.unit }})</a></li>
                {% endfor %}
            </ul>
						{% else %}
            <h3>No results for {{ lastname }} as UT EID</h3>
						{% endif %}
        </div>
    </div>
</div>

{% endblock %}
