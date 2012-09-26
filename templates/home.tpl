{% extends "framework/bootstrap.tpl" %}

{% block title %}{{ main_title }}{% endblock %}
{% block content  %}

<div class="inner-cont">
  <div id="home"> 
  
    <div class="row-fluid"><div class="span12">
    <span class="span4"><h3>Welcome to</h3>
    <h1>{{ main_title }}</h1></span>
    <a class="span2 btn btn-info" href="faculty/{{ fac.eid }}/view">Get started!</a>
    </div></div>
    <hr />
    <h2>Here's what to do:</h2>
    <dl class="dl-horizontal home">
        <dt><a href="faculty/{{ fac.eid }}/view">Citations</a></dt>
          <dd><strong>Add</strong>, <strong>Tag</strong>, or <strong>Edit</strong> entries in your publication record<br/>
              <strong>Date-Stamp</strong> your up-to-date publication record</dd>
        <dt><a href="faculty/{{ fac.eid }}/sections">Sections</a></dt>
          <dd>Optionally <strong>Group</strong> your citations</dd>
        <dt><a href="faculty/{{ fac.eid }}/upload">File Upload</a></dt>
          <dd>Upload a file to import citations from a CV</dd>
    </dl>
    <hr />
    <h2>About {{ main_title }}:</h2>
    <ul>
	<li>{{ main_title }} is your tool to maintain and certify a digital record of your <strong>publications and other creative works</strong> for university and professional use.</li> 
	<li>To help you get started, UT staff seeded your digital record with citations from your public CV or other publications list.</li>
	<li>Visit the <a href="faculty/{{ fac.eid }}/help">Help</a> page to learn more.</li>
    </ul>
  </div>
</div>

{% endblock %}
