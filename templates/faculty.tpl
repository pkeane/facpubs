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
                <li class="active"><a href="faculty">faculty list</a></li>
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
        
	<div class="span8 list">  
        <h2>Faculty List ({{ facs|length }})</h2>
            <div class="2col">
            <ul>
	    {% for fac  in facs %}
	        <li>
	            <a href="faculty/{{ fac.eid }}/view">{{ fac.eid }} : [{{ fac.cn }}]</a>
	            <span class="count">( {{ fac.citation_count }} )</span>
	        </li>
	    {% endfor %}
	    </ul>
	    </div>
	</div>

  </div>
</div>
{% endblock %}
