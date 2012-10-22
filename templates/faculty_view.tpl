{% extends "framework/bootstrap.tpl" %} 

{% block headlinks %}
<link rel="service" href="{{ app_root }}/service">
<link rel="alternate" type="application/json" href="{{ app_root }}/faculty/{{ fac.eid  }}.json">
<link rel="stylesheet" href="www/css/colorbox.css">
<link rel="lines_order" href="{{ app_root }}/faculty/{{ fac.eid }}/lines/order">
{% endblock %}

{% block main %}


<h1 class="fac">{{ fac.cn }}  | {{ fac.eid }}</h1>
<h3 class="fac">{{ fac.utexasedupersonorgunitname }}</h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="faculty/{{ fac.eid }}/view">Citations</a></li>
	<li><a href="faculty/{{ fac.eid }}/sections">Sections</a></li>
	<li><a href="faculty/{{ fac.eid }}/upload">File Upload</a></li>
</ul>

<div class="inner-cont" id="lines">
  
  <div class="page-top row">
    <div class="span12"><div class="row-fluid">

    <div class="span5" id="instructions">
    <h1 class="citations">Update your publication record</h1>
    
    <ul>
        <li>Click a citation to edit, relocate, or delete it</li>
        <li>Use section bar tools to tag all, move all, or re-order citations by section</li>
        <li>Manage section labels in the Sections tab</li>
    	<li>Add multiple citations from a CV using the File Upload tab</li>
    	
    </ul>   
    
    <a href="#" class="toggle" id="toggleNewForm{{ sec.id }}"><button class="btn custom">Add Citation</button></a>
    
    </div>
    

    
    <div class="span3">
    <h1 class="citations">Certify when done</h1>

    <p>I ({{ fac.eid }}) certify that my record is current as of {{ "now"|date('m/d/Y') }}</p>
    
    <form id="certify_record_form" action="faculty/{{ fac.eid }}/certify" method="post" >
           <input type="submit" value="Certify Record" class="btn custom" >
    </form>
           
    <p id="confirm" >
    	Record Certified	
    </p>
    
    </div>
  
    <div class="span4"><div class="summary">
        <p>Record at-a-glance:</p>
        <ul>
        <li>last certified: {% if fac.certified_citations %}
        		    <span>{{ fac.certified_citations|date('m/d/Y') }}</span>
        		    {% else %}
        		    (not yet certified)
        		    {% endif %}
        </li> 
        <li><strong>{{ fac.stats.total }}</strong> citations</li>
        <li class="indent">{{ fac.stats.is_peer }} <a href="help/peer_review" class="help_link">peer-reviewed</a></li> 
        <li class="indent">{{ fac.stats.is_creative }} <a href="help/creative_work" class="help_link">creative works <em>CW</em></a></li>
        <li><a class="update" href="faculty/{{ fac.eid }}/view">(update)</a>
        </li>
        </ul>
    </div></div>   

    </div><!-- row --></div><!-- span12 -->
    
    	<div class="hide" id="targetNewForm{{ sec.id }}">
		
		<form class="yellow span9 offset1" action="faculty/{{ fac.eid }}/citations_form" method="post">
		    <label for="text">add a new citation:</label>
		    <div id="lines"><textarea class="revision span9" name="text" rows="4" placeholder="citation text"></textarea></div>
			<p>
			<label class="year small" for="year">year</label>
			<input type="text" class="span1" name="year" placeholder="yyyy">
			<em class="note">4-digit year published. For pending works, leave blank or enter anticipated publishing date.</em>
			</p>
			
			<p>
			<label class="year small" for="section_header"><a href="help/sections" class="help_link in_form">section</a></label>
			<select name="section_id" class="span5">
				<option value="">select section header:</option>
				{% for sh in fac.sections %}
				<option {% if sec.title == sh.title %}selected{% endif %} value="{{ sh.id }}">{{ sh.title|slice(0,75) }}</option>
				{% endfor %}
			</select>
			<em class="note">existing sections were taken from your CV</em>
			</p>
			<p>
			<label class="small radio"><a href="help/peer_review" class="help_link in_form">peer-reviewed</a></label>

				<input type="radio" name="is_peer" value="1"> yes
				<input type="radio" name="is_peer" value="0"> no 
				
			</p>
			
			<p class="bottom">
			<label class="small radio creative_work"><a href="help/creative_work" class="help_link in_form">creative work </a></label>
				<input type="radio" name="is_creative" value="1"> yes
				<input type="radio" name="is_creative" value="0"> no
				
				<em class="note">"creative work" is typically for the performing and fine arts</em> 
			</p>
			<p class="buttons">
				<input type="submit" class="btn btn-success" value="add citation">
				<input class="targetNewForm{{ sec.id }} btn" type="button" value="cancel">
			</p>
				
		</form>
	</div> 
  </div><!-- page-top -->

  {% for sec in fac.sections %}
  <div class="sec mod">
	<div id="update_msg">
	{% if sec.id == section_id %}
	{% if msg %}<h3 class="msg">*{{ msg }}*</h3>{% endif %}
	{% endif %}
	</div>
  <div class="sec_head" id="sec_head{{ sec.id }}">
	<a href="{{ sec.id }}" class="section_header"><img src="www/img/arrow_open.png">{{ sec.title|slice(0,50) }}</a>
	<span class="section_count">({{ sec.citations|length }})</span>
  </div>
  <div class="sec_body" id="sec_body{{ sec.id }}">

	<div class="controls">
		<div class="inner-controls">
			<a href="#" class="toggle" id="toggleBulkForm{{ sec.id }}">tag all</a> | 
			<a href="#" class="toggle" id="toggleBulkForm2{{ sec.id }}">move all</a> | 
			<a href="sectionLines{{ sec.id }}" class="enable_sort">enable re-ordering</a>
		</div><!-- inner-controls -->

		<div id="bulk_edit_options">
			<div class="bulk_edit_form hide" id="targetBulkForm{{ sec.id }}">

			<form action="faculty/{{ fac.eid }}/section/{{ sec.id }}/bulk" method="post" class="yellow bulk_edit">
			{% if not sec.citations|length %} 
				<p>no citations in this section!</p>   
			{% else %}
				<h3>Tag all citations in section "{{ sec.title }}":</h3>
				
				<p>
				<label class="small radio peer_reviewed" for="all_peer"><a href="help/peer_review" class="help_link in_form">peer reviewed</a></label>
				<input type="radio" name="all_peer" value="1"> yes &nbsp;&nbsp;&nbsp;
				<input type="radio" name="all_peer" value="0"> no &nbsp;&nbsp;&nbsp;
				<input type="radio" name="all_peer" value="2" checked> (no change) 
				
				</p><p>				
				<label class="small radio creative_work" for="all_creative"><a href="help/creative_work" class="help_link in_form">creative work</a></label>
				<input type="radio" name="all_creative" value="1"> yes &nbsp;&nbsp;&nbsp;
				<input type="radio" name="all_creative" value="0"> no &nbsp;&nbsp;&nbsp;
				<input type="radio" name="all_creative" value="2" checked> (no change) 	
				
				</p>
				<p>
				<input class="btn btn-success" type="submit" value="apply changes">
				<input class="targetBulkForm{{ sec.id }} btn" type="button" value="cancel">
				</p>					
			{% endif %}			
			</form>
			</div><!-- #targetBulkForm{{ sec.id }} -->
			<div class="bulk_edit_form hide sec_form" id="targetBulkForm2{{ sec.id }}">

			<form action="faculty/{{ fac.eid }}/section/{{ sec.id }}/mover" class="yellow bulk_edit" method="post">
			{% if not sec.citations|length %} 
				<p>no citations in this section!</p>   
			{% else %}
				<label for="all_peer"><h3>Move all citations from section "{{ sec.title|slice(0,65) }}" to:</h3></label>
				<select name="section_id">
				<option value="">select section header:</option>
				{% for sh in fac.sections %}
					<option {% if sec.title ==  sh.title %}selected{% endif %} value="{{ sh.id }}">{{ sh.title|slice(0,60) }}</option>
				{% endfor %}
				</select>
				<p>
				<input class="btn btn-success" type="submit" value="move citations">
				<input class="targetBulkForm2{{ sec.id }} btn" type="button" value="cancel">
				<em class="note">Note: move individul citations by clicking on the citation text and selecting a new section</em>
				</p>
				
			{% endif %}	
			</form>
			</div><!-- #targetBulkForm2{{ sec.id }} -->
		</div><!-- bulk_edit_options -->			
	</div><!-- controls -->

	<table class="table table-bordered"  id="sectionLines{{ sec.id }}">

	{% for line in sec.citations %}
		<a name="line{{ line.id }}"></a>


		<tr class="section_row" id="{{ line.id }}">
	
			<td class="li_sec year">
				<span class="year">
				  <a href="#" class="year_form_toggle" id="toggleYear{{ line.id }}">
			          {% if line.year %}  
			  	  {{ line.year }}
			  	  {% else %}
				  <em class="year_placeholder">(yyyy)</em>
				  {% endif %}
			          </a>
			        </span>	  
			        <div class="hide year_form" id="targetYear{{ line.id }}">
			        	<form class="year_form" method="post" action="faculty/{{ fac.eid }}/citation/{{ line.id }}/form_year">
			        	<input type="text" style="margin-bottom:0px;" class="span1" name="year" value="{{ line.year }}">
			        	<input type="submit" class="btn btn-success" value="update">
			        	</form>
			        </div>
			</td>
			
			<td class="li_sec citation">
				<span class="line">

				  {% if line.is_creative %}
				  <a href="help/creative_work" class="creative_display help_link">CW</a>
				  {% endif %}
				
				<a href="faculty/{{ fac.eid }}/citation/{{ line.id }}/form" class="edit_form_toggle" id="toggleLine{{ line.id }}">
				    {{ line.revised_text }}
				  </a>
				  

				  
				</span>		
				<div class="hide revision_form" id="targetLine{{ line.id }}"></div>
			</td>			
			
<!--			<td class="is_md">
				<a href="help/creative_work" class="help_link">creative</a>
				{% if not line.is_creative %}
				<p>
				<a class="peer" id="1" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_creative"><button class="creative btn"><input type="checkbox"></button></a>
				<a class="peer hide" id="0" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_creative"><button class="creative btn btn-warning"><input type="checkbox" checked="checked"></button></a>
				</p>
				{% else %}
				<p>
				<a class="peer" id="0" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_creative"><button class="creative btn btn-warning"><input type="checkbox" checked="checked"></button></a>
				<a class="peer hide" id="1" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_creative"><button class="creative btn"><input type="checkbox"></button></a>
				</p>
				{% endif %}			
			</td>
-->			
			<td class="is_md">
				<a href="help/peer_review" class="help_link">peer-rev</a>
				{% if line.is_peer %}
				<p>
				<a class="peer" id="0" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_peer"><button class="peer btn btn-warning"><input type="checkbox" checked="checked"></button></a>
				<a class="peer hide" id="1" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_peer"><button class="peer btn"><input type="checkbox"></button></a>
				</p>
				{% else %}
				<p>
				<a class="peer" id="1" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_peer"><button class="peer btn"><input type="checkbox"></button></a>
				<a class="peer hide" id="0" href="faculty/{{fac.eid}}/citation/{{line.id}}/is_peer"><button class="peer btn btn-warning"><input type="checkbox" checked="checked"></button></a>
				</p>
				{% endif %}
			</td>
		</tr>			
				
	{% endfor %}
	</table>

  </div>
  </div> 
  {% endfor %}
</div>

<footer>{% block footer %}{{ parent() }}{% endblock footer %}</footer>

{% endblock %}
				
				
				
