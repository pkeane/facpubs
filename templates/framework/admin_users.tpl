{% extends "framework/bootstrap.tpl" %}

{% block content %}

<div class="admin container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <h3>Administration</h3>
            <ul>
                <li><a href="admin">my user settings</a></li>
            {% if request.user.is_admin %}
                <li class="active"><a href="admin/users">list users</a></li>
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
            <h2>Users</h2>
            <table class="table" id="user_privs">
                {% for u in users %}
                <tr>
                    <td>{{ u.name }}</td>
                    <td>
                        {% if u.is_admin %}
                        <a href="{{ app_root }}/admin/user/{{ u.id }}/is_admin" data-method="delete" class="btn btn-mini btn-danger">[remove privileges]</a>
                        {% else %}
                        <a href="{{ app_root }}/admin/user/{{ u.id }}/is_admin" data-method="put" class="btn btn-mini btn-success">[grant privileges]</a>
                        {% endif %}
                    </td>
                </tr>
                {% endfor %}
            </table>
        </div>
    </div>
</div>

{% endblock %}
