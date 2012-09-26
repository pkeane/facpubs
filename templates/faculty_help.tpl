{% extends "framework/bootstrap.tpl" %}

{block name="head-links}
<link rel="service" href="{$app_root}service">
<link rel="alternate" type="application/json" href="{$app_root}faculty/{$fac->eid}.json">
<link rel="stylesheet" href="www/css/colorbox.css">
{% endblock %}

{% block content %}

{assign var="check" value=""}

<h1 class="fac">{$fac->cn}  | {$fac->eid}</h1>
<h3 class="fac">{$fac->utexasedupersonorgunitname}</h3>

<ul id="fac_menu">
	<li><a href="faculty/{$fac->eid}/upload">Citations</a></li>
	<li><a href="faculty/{$fac->eid}/sections">Sections</a></li>
	<li><a href="faculty/{$fac->eid}/review">Certify</a></li>
	<li class="active gap"><a href="faculty/{$fac->eid}/assistance">Other tasks</a></li>
</ul>
<div class="clear"></div>
<div class="inner-cont">


	<div class="main_help hide">
	<h2>FAQ</h2>
	<ul id="help">

		<li>
		<a href="#0.1_">What is considered a <strong>creative work</strong>?</a>
		<ul>
			<li>A <strong>creative work</strong> is a product of creative scholarship that has been published, released, or presented, and which may not adhere to a standard bibliographic reference format.The creative work flag is used to differentiate traditional academic publications from other scholarly works that are included in the Faculty Publications Manager, in the digital FAR, and in other common uses for your scholarly record. Use your best judgment to determine if a citation represents a creative work.
			<h3>Examples Include:</h3>
			<ul>
				<li>- A screenplay, musical score, or architectural design</li>
				<li>- A dance performance, art exhibition, or film screening</li> 
				<li>- A TV or radio broadcast, or internet podcast</li> 
				<li>- Software, websites, or other instructional materials</li> 
			</ul>
			</li>
		</ul>
		</li>

		<li>
		<a href="#0.1_">What is considered a <strong>peer reviewed</strong> work?</a>
		<ul>
			<li>The <strong>peer reviewed</strong> flag is generally used to indicate that a work has been published in or accepted to a peer-reviewed or refereed journal or venue. Peer review carries various meanings and implications depending on discipline, venue, and the nature of the publication. Please use the definitions customary to your discipline or field.
			</li>
			<li class="ital">Please note: When importing citations into your record from CVs and other publication lists, our staff marked citations as peer reviewed only when there was an explicit indication of such in the CV. Please correct any ommissions or errors if needed.
			</li>
		</ul>
		</li>

		<li>
		<a href="#0.1_">What types of citations should be included in the Publications Manager?</a>
		<ul>
			<li>Use the Faculty Publications Manager to store individual bibliographic references of publications and other creative works. This includes such categories as:
			<ul>
				<li>- Books, articles, reports, published conference proceedings</li> 
				<li>- Chapters and other contributions, reviews, translations, edited works</li>	
				<li>- Software and instructional technology, recordings, curatorship, architectural designs </li>
				<li>- Exhibitions, broadcasts, screenings, installations</li>
			</ul>
			</li>
			<li>Make sure each citation is a complete reference. Other applications that may use your citation iformation won't necessarily reference section headers or other citations.</li>
			<li>Please avoid including references to unpublished activities such as presentations and lectures, or references to standing positions, such as magazine editorship.</li> 
		</ul>
		</li>

		<li>
		<a href="#0.1_">Can my citations contain additional information?</a>
		<ul>
			<li>Additional information, such as reprint details, citation counts, and annotations may be included in the citation if desired, but these details will be displayed as part of the citation.</li>
		</ul>
		</li>
		
		
		<li>
		<a href="#0.1_">Why do I need to <strong>review</strong> and <strong>certify</strong> my record?</a>

		<ul>
			<li>Marking a citation as "reviewed" indicates that a citation is accurate and belongs in your record, and helps to mitigate any errors introduced by citation imports.</li> 
			<li>Certification provides an "as of" date for the completeness of your record, so that you and any administrative applications that need your publications list can easily identify how up-to-date your record is.</li>
			<li>Initial review and certification of your record will allow you to verify that we didn't miss any of your citations, if we collected any. After that, it is up to you to regularly update your record - we recommend at least once a semester, especially before the annual FAR is submitted.</li>
		</ul>
		</li>


	</ul>


	<h2>How Do I...</h2>
	<ul id="help">	
		<li>
		<a href="#0.1_">Add a citation</a>
		<ul>
			<li>1. Open the section that you wish the citation to go in. (Make new sections in the <a id="helpout" href="faculty/{$fac->eid}/sections">Mange Sections</a> page)</li>
			<li>2. Click on <strong>add a citation</strong> and fill out all of the fields</li>
			<li>3. If needed, click <strong>enable citation sorting</strong> in the section title bar and drag the citation to the appropriate place in the list</li>
			<li class="ital">Note: To add multiple citations, you may upload a file and import citation in the <a id="helpout" href="faculty/{$fac->eid}/upload">Upload Citations</a> page</li>
		</ul>
		</li>

		<li>
		<a href="#0.1_">Move a citation to another section</a> 
		<ul>
			<li>1. Open citation for editing</li>
			<li>2. Select a different section in the section bar</li> 
			<li>3. Citation will appear at the top of the designated section.  If needed, click <strong>enable citation sorting</strong> in the section title bar and drag the citation to the appropriate place in the list.</li>
			<li class="ital">Note: To move all citations from one section to another section, click on <strong>bulk edit</strong> in the section header bar and choose a new section.</li>
		</ul>
		</li>

		<li>
		<a href="#0.1_">Delete a citation</a> 
		<ul>
			<li>1. Open citation for editing</li>
			<li>2. Click on "delete citation" and confirm</li> 
		</ul>	
		</li>

		<li>
		<a href="#0.1_">Delete a section</a> 
		<ul>
			<li class="ital">(Only empty sections may be deleted)</li>
			<li>1. Remove all citations from the section you wish to delete, either by deleting them or moving them to another section</li> 
			<li>2. Go to the Manage Sections page and click on <strong>edit section header</strong></li> 
			<li>3. If section has no citations, a <strong>delete empty section</strong> button will appear</li>
		</ul>
		</li>
		
	</ul>

	<h2>Background Information</h2>
	<ul id="help">	

		<li>
		<a href="#0.1_">Where did the current citations come from?</a>
		<ul>
			<li>To help you get started using the Faculty Publications Manager, university staff extracted from available CV's or, in a few cases from customary disciplinary or departmental databases, all records of publications and creative works available as of late spring 2011. We maintained the sections and citation order from CV's whenever we could, and marked citations as peer reviewed if they were explicitly indicated as such in the CV. If you see a citation in your record that is not your work, delete it by clicking <strong>edit</strong> and then <strong>delete citation</strong></li>

		</ul>
		</li>

		<li>
		<a href="#0.1_">How can I use my record to complete the Faculty Annual Report?</a>
		<ul>
			<li>No additional work is needed to make your records available for the FAR application.</li>
			<li>The new digital FAR application for the 2011-2012 reporting year will automatically import citations stored in the Faculty Publications Manager. When you log in to the FAR application, you will be presented with a draft list of your citations to edit and update these citations as needed and submit as part of your annual report. Any new citations added during the FAR will be stored in the Faculty Publications Manager for you to later certify as part of your record.</li>
		</ul>
		</li>

	</ul>
	</div><!-- main_help -->
</div><!-- inner_cont -->

{% endblock %}


