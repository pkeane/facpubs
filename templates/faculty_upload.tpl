{% extends "framework/bootstrap.tpl" %}

 <!-- This is a deprecated page -->

{% block name="head-links %}
<link rel="service" href="{$app_root}service">
<link rel="alternate" type="application/json" href="{$app_root}faculty/{$fac->eid}.json">
<link rel="stylesheet" href="www/css/colorbox.css">
<link rel="lines_order" href="{$app_root}faculty/{$fac->eid}/lines/order">
{% endblock %}

{% block content %}

{assign var="check" value=""}

<h1 class="fac">{$fac->cn}  | {$fac->eid}</h1>
<h3 class="fac">{$fac->utexasedupersonorgunitname}</h3>

<ul id="fac_menu">
	<li class="active"><a>Citations</a></li>
	<li><a>Sections</a></li>
	<li><a>Certify</a></li>
	<li class="gap"><a>Other tasks</a></li>
</ul>
<div class="clear"></div>

<div class="inner-cont" id="lines">
<div class="page_info" id="accordion">

	<h1 class="citation_menu">Add Citations</h1>
	<div class="not_dark">
		<div class="totals">
		<h3>You currently have:</h3> 
	    	<ul>
	    	  	<li>{$fac->stats.total} citations</li>
	    	</ul>
	    	</div>
	<p><strong>Add citations</strong> by clicking on the "add a citation" link in each section header.</p>
	<p>To create <strong>new sections</strong>, go to the Sections tab.<a class="help" href="help/sections"><img alt="what's this?"class="help_question" src="www/images/sample_question.png"></a></p>
	<p><strong>Lots of citations to add?</strong> you may want to use the <a href="faculty/{$fac->eid}/assistance">File Uploader</a>. <a class="help" href="help/upload"><img alt="what's this?"class="help_question" src="www/images/sample_question.png"></a></p>
	</div>
	
	<h1><a href="faculty/{$fac->eid}/view"> &nbsp;> Edit & Review Citations</a></h1>
	
</div><!-- accordion -->
<div class="clear"></div>

{if 0 == $fac->sections|@count}
<div class="new_citation_form add_page_none">
	<h2 class="empty_count">Add a Citation</h2>
		<form action="faculty/{$fac->eid}/citations_form" method="post">
			<label for="text">citation text</label>
			<div id="lines"><textarea class="revision" name="text"></textarea></div>
			<p>
			<label class="year" for="year">year</label>
			<input type="text" name="year" value="">
			</p>
			
			<p>
			<label class="radio">peer reviewed <a class="help" href="help/peer_review">(?)</a></label>
			</p>
			
			<div class="radio_background">
			<input type="radio" name="is_peer" value="1"> yes
			<input type="radio" name="is_peer" value="0"> no 
			</div>
			
			
			<p class="buttons">
			<input type="submit" value="create citation">
			<input class="targetNewForm{$sec.id}" type="button" value="cancel">
			</p>
			
			<div class="optional_tag">
			<h3>Optional Tags:</h3>
			
			<p>
			<label class="radio creative_work">creative work &nbsp;<a class="help" href="help/creative_work">(?)</a></label>
			<div class="optional_radio radio_background">
			{if $citation->is_creative}
			<input type="radio" name="is_creative" value="1" checked> yes
			<input type="radio" name="is_creative" value="0"> no 
			{else}
			<input type="radio" name="is_creative" value="1"> yes
			<input type="radio" name="is_creative" value="0" checked> no 
			{/if}
			</div><!-- optional_radio -->
			</p>
			</div><!-- add_optional_tag -->
		</form>
	</div><!-- new_citation_form -->
	</div><!-- inner-cont -->
{/if}	
	
	{foreach item=sec from=$fac->sections}

	<div class="sec">
		<div id="update_msg">
	{if $sec.id == $section_id}
	{if $msg}<h3 class="msg">*{$msg}*</h3>{/if}
	{/if}
	</div>
		<div class="sec_head" id="sec_head{$sec.id}">
			<a href="{$sec.id}" class="section_header"><img src="www/images/down_arrow.png"> {$sec.title|truncate:80:"...":true}</a>
			<span class="section_count">({$sec.citations|@count})</span>
		</div>
		<div class="sec_body" id="sec_body{$sec.id}">

			<div class="controls" id="secmenu_add">
				<a href="#" class="toggle" id="toggleNewForm{$sec.id}">add a citation</a>
			</div>
			<div class="clear"></div>

			<div class="new_citation_form hide" id="targetNewForm{$sec.id}">
				<h2>Add A Citation</h2>
				<form action="faculty/{$fac->eid}/citations_form" method="post">
					<label for="text">citation text</label>
					<div id="lines"><textarea class="revision" name="text"></textarea></div>
					<p>
					<label class="year" for="year">year</label>
					<input type="text" name="year" value="">
					</p>
				
					<p>
					<label class="year" for="section_header">section</label>
					<select name="section_id">
						<option value="">select section header:</option>
						{foreach item=sh from=$fac->sections}
						<option {if $sec.title eq $sh.title}selected{/if} value="{$sh.id}">{$sh.title|truncate:75:"...":true}</option>
						{/foreach}
					</select>
					</p>
					
					<p>
					<label class="radio">peer reviewed <a class="help" href="help/peer_review">(?)</a></label>
					</p>
					<div class="radio_background">
					<input type="radio" name="is_peer" value="1"> yes
					<input type="radio" name="is_peer" value="0"> no 
					</div>
					
					<p class="buttons">
					<input type="submit" value="create citation">
					<input class="targetNewForm{$sec.id}" type="button" value="cancel">
					</p>
					
					<div class="optional_tag">
					<h3>Optional Tags:</h3>
					<p>
					<label class="radio creative_work">creative work &nbsp;<a class="help" href="help/creative_work">(?)</a></label>
						<div class="optional_radio radio_background">
						{if $citation->is_creative}
						<input type="radio" name="is_creative" value="1" checked> yes
						<input type="radio" name="is_creative" value="0"> no 
						{else}
						<input type="radio" name="is_creative" value="1"> yes
						<input type="radio" name="is_creative" value="0" checked> no 
						{/if}
						</div><!-- optional_radio -->
					</p>	
					</div><!-- add_optional_tag -->					
				</form>
			</div><!-- new_citation_form -->

			{if $sec.id == $section_id}
			{if $msg}<h3 class="msg">{$msg}</h3>{/if}
			{/if}

			<table class="section_table"  id="sectionLines{$sec.id}">

				{foreach item=line from=$sec.citations}
				<a name="line{$line->id}"></a>
				<tr class="section_row" id="{$line->id}">
				    <td class="li_sec review">
					{if !$line->reviewed}
					<p>
					<a class="review" href="faculty/{$fac->eid}/citation/{$line->id}/review_flag/on"><span class="button_review">review</span></a>
					<a class="review hide" href="faculty/{$fac->eid}/citation/{$line->id}/review_flag/off"><span class="button_reviewed">reviewed</span></a>
					</p>
					{else}
					<p>
					<a class="review" href="faculty/{$fac->eid}/citation/{$line->id}/review_flag/off"><span class="button_reviewed">reviewed</span></a>
					<a class="review hide" href="faculty/{$fac->eid}/citation/{$line->id}/review_flag/on"><span class="button_review">review</span></a>
					</p>
					{/if}
				    </td>

				    <td class="li_sec year">
					{$line->year}
				    </td>
				    <td class="li_sec citation">
					<span class="line">{$line->revised_text}</span>
					<a href="faculty/{$fac->eid}/citation/{$line->id}/form" class="edit_form_toggle" id="toggleLine{$line->id}">edit</a>
					<div class="hide revision_form" id="targetLine{$line->id}">
					</div>
				    </td>
				    <td class="is_peer">
					{if $line->is_peer}
					peer-reviewed
					{else}
					{/if}
				    </td>
				</tr>
				{/foreach}
			</table>
		</div> <!-- sec_body -->
	</div> <!-- sec -->
	{/foreach}
	

</div><!-- inner-cont -->

{% endblock %}
