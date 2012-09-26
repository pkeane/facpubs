{% extends "framework/bootstrap.tpl" %}

{% block content %}
<div class="admin container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <h3>Administration</h3>
            <ul>
                <li><a href="admin">my user settings</a></li>
            {% if request.user.is_admin %}
                <li><a href="admin/users">list Users</a></li>
                <li class="active"><a href="admin/directory">add a User</a></li>
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
            <h2>Add User</h2>
            <dl class="well container dl-horizontal fix">
                <dt>name</dt>
                <dd>{{ record.name }}</dd>
                <dt>eid</dt>
                <dd>{{ record.eid }}</dd>
                <dt>email</dt>
                <dd>{{ record.email }}</dd>
                <dt>title</dt>
                <dd>{{ record.title }}</dd>
                <dt>unit</dt>
                <dd>{{ record.unit }}</dd>
                <dt>phone</dt>
                <dd>{{ record.phone }}</dd>
            </dl>
            {% if user %}
            <h3>{{ user.name }} is already registered</h3>
            {% endif %}
            <form method="post" action="admin/users">
                <input type="hidden" name="eid" value="{{ record.eid }}">
                <input type="submit" value="add {{ record.name }}">
            </form>
        </div>
    </div>
</div>
{% endblock %}
