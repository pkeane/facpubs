{% extends "framework/bootstrap.tpl" %}

{% block content %}

<div class="admin container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <h3>Administration</h3>
            <ul>
                <li class="active"><a href="admin">my user settings</a></li>
            {% if request.user.is_admin %}
                <li><a href="admin/users">list users</a></li>
                <li><a href="admin/directory">add a user</a></li>
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
            <h2>User Settings for {{ request.user.name }}</h2>
            <dl class="well dl-horizontal">
                <dt>name</dt>
                <dd>{{ request.user.name }}</dd>
                <dt>eid</dt>
                <dd>{{ request.user.eid }}</dd>
                <dt>email</dt>
                <dd id="email">
                {{ request.user.email }} <a href="" id="toggleEmail" class="toggle">[update]</a>
                <form id="targetEmail" class="hide" method="post" action="admin/user/email">
                    <p>
                    <input type="text" value="{{ request.user.email }}" name="email">
                    <input type="submit" value="update">
                    </p>
                </form>
                </dd>
                <dt>is admin</dt>
                <dd>
                {% if request.user.is_admin %}yes{% else %}no{% endif %}
                </dd>
            </dl>
        </div>
    </div>
</div>


<!--

<ul class="unstyled">
    <li><h2>Manage</h2></li>
    <li><a href="user/settings">my user settings</a></li>
    {% if request.user.is_admin %}
    <li><a href="directory">add a user</a></li>
    <li><a href="admin/users">list users</a></li>
    <li><h2>View</h2></li>
    <li><a href="items">view items</a></li>
    <li><a href="set/list">view sets</a></li>
    <li><h2>Create</h2></li>
    <li><a href="admin/create">create content</a></li>
    <li><a href="set/form">create a set</a></li>
    {% endif %}
</ul>
-->


{% endblock %}
