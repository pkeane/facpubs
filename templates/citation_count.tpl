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
                <li><a href="admin/directory">add a user</a></li>
                <li><a href="faculty">faculty list</a></li>
                <li><a href="admin/faculty/form">add faculty by EID</a></li> 
                <li class="active"><a href="admin/citation_count">citation count</a></li>
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
	<h2>Citation Count</h2>
	<p>{{ count }}</p>
	</div>	
        
    </div>	
</div>

{% endblock %}
