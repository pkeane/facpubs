<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="{{ app_root }}/">
        <meta charset="utf-8">
        {% block headmeta %}
        {% endblock %}


        <title>{% block title %}{{ main_title }}{% endblock %}</title>

        <!-- Le styles -->
        <link href="www/css/bootstrap.css" rel="stylesheet">
        <link href="www/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="www/css/colorbox.css" rel="stylesheet">
        <link href="www/css/local.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        {% block headlinks %}{% endblock %}

        {% block headjs %}
        <!--
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script src="http://code.jquery.com/ui/1.8.19/jquery-ui.min.js"></script>
        -->
        <script src="www/js/jquery.js"></script>
        <script src="www/js/jquery-ui.js"></script>
        <script src="www/js/jquery.colorbox.js"></script>
        {% endblock %}

        {% block head %}{% endblock %}
        <script src="www/js/script.js"></script>
    </head>

    <body>

        {% block header %}
        <div class="wordmark"><div class="container">
        <a href="http://www.utexas.edu"><img src="www/img/provostbar.jpg"></a>
        </div></div>
        <div class="navigation">
                <div class="container"><div class="row">
                    <a class="main_title" href="#"><h1 class="span6">{{ main_title }}</h1></a>
                      <ul class="navigation span4 offset1">
                          <li {% if request.handler == 'home' %}class="active"{% endif %}><a href="home">Home</a> | </li>
                          <li {% if request.handler == 'faculty' %}class="active"{% endif %}><a href="faculty/{{ fac.eid }}/help">Help</a> | </li>
                          <li id="login">
                              {% if request.user %}
                              <a href="login/{{ request.user.eid }}" class="delete">Logout {{ request.user.eid }}</a>
                              {% endif %}
                          </li>
                          {% if request.user.is_admin %}
                          <li {% if request.handler == 'admin' %} class="active"{% endif %}> | <a href="admin">Admin</a></li>
                          {% endif %}
                      </ul>   
               </div></div>
        </div>
        {% endblock header %}

        <div class="container" id="container">
            {% block main %}
            {% if msg %}<h3 class="msg">{{ msg }}</h3>{% endif %}
            {% block content %}

            <h1>default content</h1>

            {% endblock content %}

            <footer>
            {% block footer %}
	    <a href="http://www.utexas.edu/cola/laits/"><img src="www/img/logo-copy.jpg" alt="LAITS"></a>
            <p>
            <a href="mailto:facpubs@utlists.utexas.edu" class="footer">Contact Us</a><br />
            <a href="http://www.utexas.edu/policies/privacy/" class="footer">Privacy</a> 
            |
            <a href="http://www.utexas.edu/what-starts-here/web-guidelines/accessibility" class="footer">Web Accessibility</a>
            </p>
            {% endblock footer %}
            </footer>
            {% endblock main %}

        </div> <!-- /container -->

    </body>
</html>
