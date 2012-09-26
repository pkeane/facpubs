{% extends "framework/bootstrap.tpl" %}

{% block headlinks %}
<link rel="service" href="{{ app_root }}/service">
<link rel="alternate" type="application/json" href="{{ app_root }}/faculty/{{ fac.eid }}.json">
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
	<li class="active"><a href="faculty/{{ fac.eid }}/review">Certify</a></li>
	<li class="gap"><a href="faculty/{{ fac.eid }}/assistance">Other tasks</a></li>
</ul>

<div class="inner-cont" id="lines">	
<h1>Certify Record</h1>
    <div class="page_top row">
      <div class="span12"><div class="row-fluid">
        <dl class="dl-horizontal span8">
          <dt>Certify</dt>
            <dd>Date-stamp your reviewed publication record with today's date.</dd>
        </dl>
        
        <div class="span4">
        <h3>Record last certified:</h3>
 	{% if fac.certified_citations %}
	    <h4 id="cert_date">{{ fac.certified_citations|date('m/d/Y') }}</h4>
	{% else %}
	    <span><h4 id="cert_date">(not yet certified)</h4></span>
	{% endif %}     
        </div>
      </div></div>
      
      <div class="span6 offset1 yellow" id="cert_statement">
	  <h4 class="span3">I ({{ fac.eid }}) certify that my record is current as of {{ "now"|date('m/d/Y') }}:</h4>
	  <form action="faculty/{{ fac.eid }}/certify" method="post">
	  <input type="submit" value="Certify Record" class="btn btn-info">
	  </form>	
      </div>
      <div class="cert_msg span3">      
      	{% if cert_msg %}
	<h4 class="msg cert">NOTE: {{ cert_msg }}</h4>
	{% endif %}
      </div>
    </div>




{% for sec in fac.sections %}
	<div class="sec">
		<div class="sec_head" id="sec_head{{ sec.id }}">
			<a href="{{ sec.id }}" class="section_header"><img src="www/img/arrow_open.png">{{ sec.title }}</a>
			<span class="section_count">({{ sec.citations|length }})</span>
		</div>

		<div class="sec_body" id="sec_body{{ sec.id }}">
			<div class="clear"></div>
			<table class="section_table table table-bordered" id="sectionLines{{ sec.id }}">
				{% for line  in sec.citations %}
				<a name="line{{ line.id }}"></a>
				<tr class="section_row" id="{{ line.id }}">

				<td class="li_sec review">
					{% if not line.reviewed %}
					<p>
					<a class="review" href="faculty/{{ fac.eid }}/citation/{{ line.id }}/review_flag/on"><button class="review btn btn-danger"><input type="checkbox"> reviewed</button></a>
					<a class="review hide" href="faculty/{{ fac.eid }}/citation/{{ line.id }}/review_flag/off"><button class="reviewed btn"><input type="checkbox" checked="yes"> reviewed</button></a>
					</p>
					{% else %}
					<p>
					<a class="review" href="faculty/{{ fac.eid }}/citation/{{ line.id }}/review_flag/off"><button class="reviewed btn"><input type="checkbox" checked="yes">reviewed</button></a>
					<a class="review hide" href="faculty/{{ fac.eid }}/citation/{{ line.id }}/review_flag/on"><button class="review btn btn-danger"><input type="checkbox">reviewed</button></a>
					</p>
					{% endif %}
				</td>

				<td class="li_sec year">
					{{ line.year }}
				</td>
 
				<td class="li_sec citation">
					<span class="line">{{ line.revised_text }}</span>	

				</td>

				<td class="is_peer">
					{% if line.is_peer %}
					<span class="creative">peer-reviewed</span>
					{% else %}
					{% endif %}
				
					{% if line.is_creative %}
			
					{% endif %} 
				</td>

				</tr>
				{% endfor %}

			</table>

		</div> <!-- close div.sec_body -->
	</div> <!-- close div.sec -->
{% endfor %}
</div>
<footer>{% block footer %}{{ parent() }}{% endblock footer %}</footer>
{% endblock main %}
