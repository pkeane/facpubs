{% extends "framework/bootstrap.tpl" %}

{% block headlinks %}
<link rel="service" href="{{ app_root }}/service">
<link rel="alternate" type="application/json" href="{{ app_root }}/faculty/{{ fac.eid  }}.json">
<link rel="stylesheet" href="www/css/colorbox.css">
<link rel="lines_order" href="{{ app_root }}/faculty/{{ fac.eid }}/lines/order">
{% endblock %}

{% block main %}

{% set check = "" %}

<h1 class="fac">{{ fac.cn }} | {{ fac.eid }}</h1>
<h3 class="fac">{{ fac.utexasedupersonorgunitname }}</h3>

<ul class="nav nav-tabs">
	<li><a href="faculty/{{ fac.eid }}/view">Citations</a></li>
	<li><a href="faculty/{{ fac.eid }}/sections">Sections</a></li>
	<li class="active"><a href="faculty/{{ fac.eid }}/upload">File Upload</a></li>
</ul>

<div class="inner-cont">
<div id="tasks">

<h1>Upload a File</h1>

<div class="row-fluid"><div class="span12">
	<div class="row-fluid">
		  <form class="span8 offset1 yellow" id="upload_form" action="upload/{{ fac.eid }}" method="post" enctype="multipart/form-data">
		    <p class="span4">
		    <label for="uploaded_file">Please select a file ( WORD or PDF format)</label>
		    <input class="input-file" type="file" name="uploaded_file" size="25"/>
		    </p>

		    <p class="span2">
		    <input class="btn custom" type="submit" value="upload {{ fac.eid }}'s file"/>
		    </p>
	 </form>	


	  </div><!-- row-fluid" -->
	  
	  <div class="row-fluid">
	  
	  
	  <dl class="dl-horizontal span8">
	    <dt>About uploading</dt>
		<dd>Add multiple citations to your record from a CV or other document.  Uploading does not replace - and may duplicate - existing citations.</dd>
	    <dt>Need help?</dt>
		<dd>Email your file to <a class="link_to" href="mailto:facpubs@utlists.utexas.edu">facpubs@utlists.utexas.edu</a> if you have trouble with file upload.  We will notify you once we've imported your citations so you can review and certify your record.</dd>
	    </dl>
	    <div class="span4" id="uploaded_files">
	    <h4>{{ fac.eid }}'s uploaded files:</h4>
	    <ul>
		{% for file in files %}
		<li>
		  <a href="file/{{ fac.eid }}/view/{{ file.id }}" class="file_name">{{ file.orig_name }} &nbsp;</a>
		  <a href="file/{{ fac.eid }}/file/{{ file.id }}" class="delete">[delete]</a>
		</li>
		{% endfor %}
	    </ul>
	    </div><!-- uploaded files -->	  
	  	 </div><!-- row-fluid -->
	  
	  
	  
	  
	  
	
</div></div>
<hr />
<h1>Share your record</h1>
	<p><a class="page_reference" href="faculty/{{ fac.eid }}/view/list">Click here</a> to access a plain-text list of your complete citation record for printing or sharing. <br />This link is visible without an EID login.</p>	

<hr />
<h1>Contact Us</h1>
	<p>For further assistance or to provide feedback, please contact us at <a class="link_to" href="mailto:facpubs@utlists.utexas.edu">facpubs@utlists.utexas.edu</a>.</p>



  <div class="hide">
  <h1>Add a Proxy User</h1>
	<form action="faculty/{{ fac.eid }}/proxies" method="post">
		<label for="eid">EID of Proxy User (look up EID in <a href="{{ request.app_root }}/directory/html" id="dir">UT Directory</a>):</label>
		<input id="proxy_eid" type="text" name="proxy_eid">
		<input type="submit" value="add proxy user">
	</form>
	{% if fac.current_proxies|length %}
		<h3>current proxies assigned to {{ fac.name }}</h3>
	{% endif %}
		<ul id="proxies" class="results">
	{% for proxy in fac.proxies %}
		<li>{{ proxy.proxy_eid }} <a href="faculty/{{ fac.eid }}/proxy/{{ proxy.proxy_eid }}" class="delete">[delete]</a></li>
	{% endfor %}
		</ul>
  </div>

</div><!-- #tasks -->
</div><!-- inner-cont -->

<footer>{% block footer %}{{ parent() }}{% endblock footer %}</footer>
{% endblock main %}
