<?php
 
class Dase_Handler_Faculty extends Dase_Handler
{
	public $resource_map = array(
			'/' => 'faculty',
			'search' => 'search',
			'{eid}' => 'faculty_member',
			'{eid}/view' => 'faculty_view',
			// '{eid}/review' => 'faculty_review',
			'{eid}/proxies' => 'faculty_proxies',
			'{eid}/proxy/{proxy_eid}' => 'faculty_proxy',
			'{eid}/upload' => 'faculty_assistance',
			'{eid}/view/list' => 'faculty_view_list',
			'{eid}/view/{section}' => 'faculty_view',
			'{eid}/review/{section}' => 'faculty_review',
			'{eid}/view/filtered/{tag}' => 'faculty_view_filtered',
			'{eid}/sections' => 'faculty_sections',
			//from api
			'{eid}/citations' => 'citations',
			//from html form
			'{eid}/citations_form' => 'faculty_citations_form',
			// {eid}/upload' => 'faculty_upload',    no longer used
			'{eid}/certify' => 'certify',
			'{eid}/help' => 'faculty_help',
			'{eid}/sections/order' => 'faculty_sections_order',
			'{eid}/lines/order' => 'faculty_lines_order',
			'{eid}/section/{id}/bulk' => 'faculty_section_bulk',
			'{eid}/section/{id}/mover' => 'faculty_section_mover',
			'{eid}/section/{id}' => 'faculty_section',
			'{eid}/{tab}' => 'faculty_member',
			'{eid}/citation/{id}' => 'citation',
			'{eid}/citation/{id}/reviewed' => 'citation_reviewed',
			'{eid}/citation/{id}/review_flag/{status}' => 'citation_review_flag',
			'{eid}/citation/{id}/form' => 'citation_form',

			// testing year form
			'{eid}/citation/{id}/form_year' => 'year_form',


			'{eid}/citation/{id}/is_creative' => 'line_is_creative',
			'{eid}/citation/{id}/is_peer' => 'line_is_peer',
			//import lines
			'{eid}/lines/file/{file_id}' => 'faculty_lines',
			);

	protected function setup($r)
	{
		if ('faculty_view_list' != $r->resource) {
				if ('citations' == $r->resource) {
						$this->user = $r->getUser('http');
				} else {
						$this->user = $r->getUser();
				}
			if (!$this->user->is_admin && ($r->get('eid') != $this->user->eid)) {
				$r->renderError(401,'unauthorized');
			}
		} else {
			//faculty_view_list does NOT require auth
		}
	}

	public function deleteFacultyProxy($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$proxy = new Dase_DBO_FacultyProxy($this->db);
		$proxy->faculty_eid = $fac->eid;
		$proxy->proxy_eid = $r->get('proxy_eid');
		if ($proxy->findOne()) {
			$proxy->delete();
		}
		$r->renderResponse('deleted proxy');

	}

	public function postToFacultyProxies($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$proxy = new Dase_DBO_FacultyProxy($this->db);
		$proxy->faculty_eid = $fac->eid;
		$proxy->proxy_eid = $r->get('proxy_eid');
		$proxy->created = date(DATE_ATOM);
		$proxy->created_by = $this->user->eid;
		if ($proxy->proxy_eid) {
			$proxy->insert();
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/assistance');
	}

	public function postToFacultySectionMover($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$section_id = $r->get('id');
		if ('none' == $section_id) {
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->section_id = 0;
			$lines1 = $lines->findAll(1);
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->addWhere('section_id','null','is');
			$lines2 = $lines->findAll(1);
			$all_lines = array_merge($lines1,$lines2);
		} else {
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->section_id = $section_id;
			$all_lines = $lines->findAll(1);
		}
		foreach ($all_lines as $c) {
			$c->section_id = $r->get('section_id');
			$c->update();
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/view/'.$r->get('section_id'));
	}

	public function postToFacultyLines($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$file = new Dase_DBO_UploadedFile($this->db);
		if (!$file->load($r->get('file_id'))) {
			$r->renderError('404');
		}
		if ($file->faculty_eid != $fac->eid) {
			$r->renderError('401');
		}
		//need this to get array-syntax param
		$line_nums = $_POST['lines'];

		if (!$line_nums || 0 == count($line_nums)) {
			$r->renderRedirect('file/'.$fac->eid.'/view/'.$file->id);
		}
		$file_lines = $file->getLines();
		$set = array();
		foreach($file_lines as $k => $line) {
			if (in_array($k,$line_nums)) {
				$set[] = $line;
			}
		}
		$sort = 0;
		foreach ($set as $cit) {
			$sort++;
			$citation = new Dase_DBO_Citation($this->db);
			$citation->faculty_eid = $fac->eid;
			$citation->created_by = $this->user->eid;
			$citation->text = $cit;
			$citation->revised_text = $cit;
			$citation->timestamp = date(DATE_ATOM);
			$citation->year = parseOutYear($cit);
			//$citation->section_id = .....
			$citation->insert();
		}
		$fac->citation_count = $fac->citation_count+$sort;
		$fac->update();
		//using section 'none' causes uncategorized to be open
		$r->renderRedirect('faculty/'.$fac->eid.'/view/none');
	}

	public function postToCitationReviewFlag($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		if ('on' == $r->get('status')) {
			$citation->reviewed = date(DATE_ATOM);
		}
		if ('off' == $r->get('status')) {
			$citation->reviewed = '';
		}
		$citation->update();
		$r->renderOk('review flag set to '.$r->get('status'));
	}

	public function postToCitationReviewed($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$citation->reviewed = date(DATE_ATOM);
		$citation->update();
		$r->renderRedirect('faculty/'.$fac->eid.'/view/'.$citation->section_id.'#line'.$citation->id);
	}

	public function postToFacultySectionBulk($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		if ('none' != $r->get('id')) {
			$fsh = new Dase_DBO_FacultySectionHeader($this->db);
			if (!$fsh->load($r->get('id'))) {
				$r->renderError(404,'no such section');
			}
			if ($fac->eid != $fsh->faculty_eid) {
				$r->renderError(409,'section does not belong to faculty member');
			}
		}

		$set = array();
		if (false !== $r->get('all_peer') && 2 != $r->get('all_peer')) {
			$sub = array();
			$sub['att'] = 'is_peer';
			$sub['val'] = $r->get('all_peer');
			$set[] = $sub;
		}

		if (false !== $r->get('all_creative') && 2 != $r->get('all_creative')) {
			$sub = array();
			$sub['att'] = 'is_creative';
			$sub['val'] = $r->get('all_creative');
			$set[] = $sub;
		}

		if ('none' == $r->get('id')) {
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->section_id = 0;
			$lines1 = $lines->findAll(1);
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->addWhere('section_id','null','is');
			$lines2 = $lines->findAll(1);
			$all_lines = array_merge($lines1,$lines2);
		} else {
			$lines = new Dase_DBO_Citation($this->db);
			$lines->faculty_eid = $fac->eid;
			$lines->section_id = $fsh->id;
			$all_lines = $lines->findAll(1);
		}

		$count = 0;
		foreach ($all_lines as $c) {
			$count++;
			foreach($set as $sub) {
				$c->$sub['att'] = $sub['val'];
			}
			$c->update();
		}

		$params['msg'] = 'updated '.$count.' citations';
		$r->renderRedirect('faculty/'.$fac->eid.'/view/'.$r->get('id'),$params);
	}

	public function putLineIsCreative($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$cit = new Dase_DBO_Citation($this->db);
		if (!$cit->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($cit->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$cit->is_creative = $r->getBody();
		$cit->update();
		$r->renderResponse('ok is_creative');
	}

	public function putLineIsPeer($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$cit = new Dase_DBO_Citation($this->db);
		if (!$cit->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($cit->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$cit->is_peer = $r->getBody();
		$cit->update();
		$r->renderResponse('ok is_peer');
	}

	public function postToCertify($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		if (!$fac->canCertify()) {
			//		$params['cert_msg'] = 'Cannot certify until all citations are reviewed';
			//		$params['cert_msg'] = 'Mark all citations reviewed before certifying';
			//		$r->renderRedirect('faculty/'.$fac->eid.'/review',$params);
		}
		$fac->certified_citations = date(DATE_ATOM);
		$fac->update();  
		$r->renderRedirect('faculty/'.$fac->eid.'/view'); 
	}

	public function getSearch($r) 
	{
		if (strlen($r->get('q')) < 2) {
			$params['msg'] = 'search term must be at least two characters long';
			$r->renderRedirect('home',$params);
		}

		$facs = new Dase_DBO_Faculty($this->db);
		$facs->orderBy('eid');
		$search_term = '%'.$r->get('q').'%';
		$facs->addWhere('eid',$search_term,'like');
		$one = $facs->findAll(1);

		$facs = new Dase_DBO_Faculty($this->db);
		$facs->orderBy('eid');
		$search_term = '%'.$r->get('q').'%';
		$facs->addWhere('cn',$search_term,'like');
		$two = $facs->findAll(1);

		$set = array();
		foreach ($one as $f1) {
			$set[$f1->eid] = $f1;
		}
		foreach ($two as $f2) {
			$set[$f2->eid] = $f2;
		}

		$r->assign('facs',$set);
		$r->renderTemplate('faculty.tpl');
	}

	public function getSearchJson($r) 
	{
		if (strlen($r->get('q')) < 2) {
			$r->renderError(412,'query term must be at least 2 characters long');
		}

		$facs = new Dase_DBO_Faculty($this->db);
		$facs->orderBy('eid');
		$search_term = '%'.$r->get('q').'%';
		$facs->addWhere('eid',$search_term,'like');
		$one = $facs->findAll(1);

		$facs = new Dase_DBO_Faculty($this->db);
		$facs->orderBy('eid');
		$search_term = '%'.$r->get('q').'%';
		$facs->addWhere('cn',$search_term,'like');
		$two = $facs->findAll(1);

		$set = array();
		foreach ($one as $f1) {
			$set[$f1->eid] = $f1;
		}
		foreach ($two as $f2) {
			$set[$f2->eid] = $f2;
		}

		$result = array();
		foreach ($set as $eid => $fac) {
			$subset = array();
			$subset['eid'] = $fac->eid;
			$subset['cn'] = $fac->cn;
			$subset['links'] = array(
					'citations' => $r->app_root.'/faculty/'.$fac->eid.'/citations.json',
					'self' => $r->app_root.'/faculty/'.$fac->eid.'.json',
					);
			$result[] = $subset;
		}
		$r->renderResponse(Dase_Json::get($result));
	}

	public function getFacultyView($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$this->user = $r->getUser();
			if ($this->user->is_admin){
				$r->renderError(404,'admin user, not fac');
			} else {
				$r->renderError(404,'no such eid');
			}
		}

		$fac->getSections(true);
		if ($r->get('section')) {
			$r->assign('section_id',$r->get('section'));
		}
		// if ($r->get('cert_msg')) {
		// 		$r->assign('cert_msg',$r->get('cert_msg'));
		// }
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_view.tpl');
	}

	public function getFacultyReview($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fac->getSections();
		if ($r->get('section')) {
			$r->assign('section_id',$r->get('section'));
		}
		if ($r->get('cert_msg')) {
			$r->assign('cert_msg',$r->get('cert_msg'));
		}
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_review.tpl');
	}

	public function getFacultyViewFiltered($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		$tag = $r->get('tag');

		if ('peer_reviewed' == $tag) {
			$tag_att = 'is_peer';
		}

		if ('creative_work' == $tag) {
			$tag_att = 'is_creative';
		}

		if ('primary_author' == $tag) {
			$tag_att = 'auth_primary';
		}

		if ('major_contributor' == $tag) {
			$tag_att = 'auth_major';
		}

		if ('secondary_author' == $tag) {
			$tag_att = 'auth_secondary';
		}

		$fac->getSections(false,$tag_att);
		if ($r->get('section')) {
			$r->assign('section_id',$r->get('section'));
		}
		if ($r->get('cert_msg')) {
			$r->assign('cert_msg',$r->get('cert_msg'));
		}
		$r->assign('tag',$tag);
		$r->assign('open_sections',1);
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_view.tpl');
	}

	public function getFacultyViewList($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		$fac->getSections();
		if ($r->get('section')) {
			$r->assign('section_id',$r->get('section'));
		}
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_view_list.tpl');
	}

	public function getFacultySections($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fac->getSections(1);
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_sections.tpl');
	}

	public function getFacultyUpload($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		//$fac->getCitations($r);
		$fac->getSections();

		$files = new Dase_DBO_UploadedFile($this->db);
		$files->faculty_eid = $fac->eid;
		$files->orderBy('uploaded DESC');
		$r->assign('files',$files->findAll(1));

		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_upload.tpl');
	}

	public function getFacultyHelp($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		//$fac->getCitations($r);
		$fac->getSections();
		$r->assign('fac',$fac);
		$r->renderTemplate('help.tpl');
	}

	public function getFacultyAssistance($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		if ($r->get('lastname')) {
			$results = Utlookup::lookup($r->get('lastname'),'sn');
			usort($results,'sortByName');
			$r->assign('lastname',$r->get('lastname'));
			$r->assign('results',$results);
		}
		$fac->getSections();
		$fac->getProxies();
		$files = new Dase_DBO_UploadedFile($this->db);
		$files->faculty_eid = $fac->eid;
		$files->orderBy('uploaded DESC');
		$r->assign('files',$files->findAll(1));
		$r->assign('fac',$fac);
		$r->renderTemplate('faculty_assistance.tpl');  // using this one now instead of faculty_upload.tpl
	}

	public function postToFacultySections($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fsh = new Dase_DBO_FacultySectionHeader($this->db);
		$fsh->faculty_eid = $fac->eid;
		$fsh->text = $r->get('text');
		$fsh->version = 0;
		$fsh->sort_order = 0;
		if ($fsh->text) {
			$fsh->insert();
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/sections');
	}

	public function postToFacultyCitationsForm($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$cit = new Dase_DBO_Citation($this->db);
		$cit->faculty_eid = $fac->eid;
		$cit->created_by = $this->user->eid;
		$cit->text = $r->get('text');
		$cit->revised_text = $r->get('text');
		$cit->year = $r->get('year');
		$cit->is_peer = $r->get('is_peer');
		$cit->is_creative = $r->get('is_creative');
		$cit->timestamp = date(DATE_ATOM);
		$cit->section_id = $r->get('section_id');
		if ($cit->text) {
			$cit->insert();
			$fac->citation_count = $fac->citation_count+1;
			$fac->update();
		}
		if ($cit->section_id) {
			$r->renderRedirect('faculty/'.$fac->eid.'/view/'.$cit->section_id);
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/view');
	}

	public function postToFacultySectionsOrder($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$order_array = explode('|',$r->getBody());
		$i = 0;
		foreach ($order_array as $fsh_id) {
			$i++;
			$fsh = new Dase_DBO_FacultySectionHeader($this->db);
			$fsh->load($fsh_id);
			$fsh->sort_order = $i;
			//verions no longer apply
			$fsh->version = 0;
			$fsh->update();
		}
		$r->renderResponse('done');
	}

	public function postToFacultyLinesOrder($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$order_array = explode('|',$r->getBody());
		$i = 0;
		foreach ($order_array as $cit_id) {
			$cit = new Dase_DBO_Citation($this->db);
			if ($cit->load($cit_id)) {
				$i++;
				$cit->sort_order = $i;
				$cit->update();
			}
		}
		$r->renderResponse('done: sorted '.$i.' citations');
	}

	public function postToFacultySection($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fsh = new Dase_DBO_FacultySectionHeader($this->db);
		$fsh->load($r->get('id'));
		if ($fac->eid != $fsh->faculty_eid) {
			$r->renderError(409,'section does not belong to faculty member'); // this is the error when attempting to edit uncategorized
		}
		$fsh->text = $r->get('text');
		if ($fsh->text) {
			$fsh->update();
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/sections');
	}

	public function deleteFacultySection($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fsh = new Dase_DBO_FacultySectionHeader($this->db);
		$fsh->load($r->get('id'));
		if ($fac->eid != $fsh->faculty_eid) {
			$r->renderError(409,'section does not belong to faculty member');
		}
		$cites = new Dase_DBO_Citation($this->db);
		$cites->section_id = $fsh->id;
		if ($cites->findOne()) {
			$r->renderError(409,'section has citations');
		}
		$fsh->delete();
		$r->renderResponse('deleted section');
	}

	public function deleteFacultyMember($r) 
	{
		$user = $r->getUser('http');
		if (!$user->is_admin && !$user->is_superuser) {
			$r->renderError(401,'unauthorized');
		}
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$fac->inflate($r);
		if (count($fac->citations)) {
			$r->renderError(409,'faculty member has citations');
		}
		if ($fac->delete()) {
			$r->renderOk('deleted faculty member');
		}
	}

	public function putFacultyMember($r) 
	{
		$user = $r->getUser('http');
		if (!$user->is_admin && !$user->is_superuser) {
			$r->renderError(401,'unauthorized');
		}
		$json = trim($r->getBody());
		$fac_data = Dase_Json::toPhp($json);
		$record_exists = false;
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');

		if ($fac->eid != $fac_data['uid']) {
			$r->renderError(409,'uid does not match eid');
		}

		if ($fac->findOne()) {
			$record_exists = true;
		} else {
			$fac->created_by = $user->eid;
			$fac->created = date(DATE_ATOM);
		}
		foreach ($fac_data as $k => $v) {
			$fac->$k = $v;
		}
		if ($record_exists) {
			$fac->update();
		} else {
			$fac->insert();
		}
		$r->renderResponse($fac->asJson());
	}

	public function postToCitation($r)
	{
		$user = $r->getUser('http');
		if (!$user->is_admin && !$user->is_superuser) {
			$r->renderError(401,'unauthorized');
		}
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		if ('application/json' == $r->getContentType()) {
			$json = trim($r->getBody());
			$citation_data = Dase_Json::toPhp($json);
			$citation = new Dase_DBO_Citation($this->db);
			if (!$citation->load($r->get('id'))) {
				$r->renderError(404,'no such citation');
			}
			if ($citation->faculty_eid != $fac->eid) {
				$r->renderError(409,'citation does not belong to faculty member');
			}
			foreach ($citation_data as $k => $v) {
				$citation->$k = $v;
			}
			$citation->timestamp = date(DATE_ATOM);
			if ($citation->update()) {
				$r->renderOk('success');
			} else {
				$r->renderError(400);
			}
		}
	}

	public function getCitation($r) // what is this for??
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$r->assign('fac',$fac);
		$r->assign('citation',$citation);
		$r->renderTemplate('citation.tpl'); // this is the old template for modal citation review from facpubs/test
	}

	public function getCitationForm($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$fsh = new Dase_DBO_FacultySectionHeader($this->db);
		$fsh->faculty_eid = $fac->eid;
		$fsh->orderBy('sort_order');

		$r->assign('sections',$fsh->findAll());
		$r->assign('citation',$citation);
		$r->assign('fac',$fac);
		if ($r->get('redirect')) {
			$r->assign('redirect','review');
		}
		$r->renderTemplate('citation_form.tpl');
	}

	// citation year form testing		
	public function getYearForm($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$r->renderTemplate('citation_form_year.tpl');
	} 

	public function postToCitationForm($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$cit = new Dase_DBO_Citation($this->db);
		if (!$cit->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($cit->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$cit->revised_text = $r->get('revised_text');
		// $cit->year = $r->get('year');
		// $cit->is_peer = $r->get('is_peer');
		$cit->is_creative = $r->get('is_creative');
		$cit->timestamp = date(DATE_ATOM);

		$orig_section_id = $cit->section_id;
		$cit->section_id = $r->get('section_id');
		if ($cit->section_id != $orig_section_id) {
			$cit->sort_order = 0;
		}
		if ($r->get('authorship')) {
			$cit->auth_primary = 0;
			$cit->auth_major = 0;
			$cit->auth_secondary = 0;
			$auth = $r->get('authorship');
			if ('primary' == $auth) {
				$cit->auth_primary = 1;
			}
			if ('major' == $auth) {
				$cit->auth_major = 1;
			}
			if ('secondary' == $auth) {
				$cit->auth_secondary = 1;
			}
		}
		//$cit->reviewed = '';
		if ($cit->revised_text) {
			$cit->update();
		}

		if ($cit->section_id) {
			if ($r->get('redirect') == 'review') {
				$r->renderRedirect('faculty/'.$fac->eid.'/review/'.$cit->section_id.'#line'.$cit->id);
			} else {
				$r->renderRedirect('faculty/'.$fac->eid.'/view/'.$cit->section_id.'#line'.$cit->id);
			}
		}
		if ($r->get('redirect') == 'review') {
			$r->renderRedirect('faculty/'.$fac->eid.'/review#line'.$cit->id);
		}
		$r->renderRedirect('faculty/'.$fac->eid.'/view#line'.$cit->id);
	}

	public function postToYearForm($r)
	{
		$citation = new Dase_DBO_Citation($this->db);
		if(!$citation->load($r->get('id'))){
			$r->renderError(401);
		}	
		$citation->year = $r->get('year');
		$citation->update();
		$r->renderRedirect('faculty/'.$r->get('eid').'/view');
	}

	public function postToLineIsCreative($r)
	{
		$citation = new Dase_DBO_Citation($this->db);
		if(!$citation->load($r->get('id'))){
			$r->renderError(401);
		}	
		$citation->is_creative = $r->get('checked');
		$citation->update();
		$r->renderOk();	
	}

	public function postToLineIsPeer($r)
	{
		$citation = new Dase_DBO_Citation($this->db);
		if(!$citation->load($r->get('id'))){
			$r->renderError(401);
		}	
		$citation->is_peer = $r->get('checked');
		$citation->update();
		$r->renderOk();	
	}

	public function getCitationJson($r)
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			$r->renderError(409,'citation does not belong to faculty member');
		}
		$citation_data = array(
				'is_peer' => $citation->is_peer,
				'is_creative' => $citation->is_creative,
				'original_text' => $citation->text,
				'revised_text' => $citation->revised_text,
				'year' => $citation->year,
				'annotation' => $citation->annotation,
				'links' => array(
					'self' => $r->app_root.'/faculty/'.$fac->eid.'/citation/'.$citation->id.'.json',
					'edit' => $r->app_root.'/faculty/'.$fac->eid.'/citation/'.$citation->id,
					),
				);
		$r->renderResponse(Dase_Json::get($citation_data));
	}

	public function deleteCitation($r) 
	{
		$user = $r->getUser('http');
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		$citation = new Dase_DBO_Citation($this->db);
		if (!$citation->load($r->get('id'))) {
			$r->renderError(404,'no such citation');
		}
		if ($citation->faculty_eid != $fac->eid) {
			if (!$user->is_admin && !$user->is_superuser) {
				$r->renderError(409,'citation does not belong to faculty member');
			}
		}
		$archived = new Dase_DBO_DeletedCitation($this->db);
		foreach ($citation as $k => $v) {
			if ('id' != $k) {
				$archived->$k = $v;
			}
		}
		$archived->insert();

		if ($citation->delete()) {
			$fac->citation_count = $fac->citation_count-1;
			$fac->update();
			$r->renderOk('deleted citation');
		}
	}

	public function postToCitations($r)
	{
		$user = $r->getUser('http');
		if (!$user->is_admin && !$user->is_superuser) {
			$r->renderError(401,'unauthorized');
		}
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}

		$json = trim($r->getBody());
		$citation_data = Dase_Json::toPhp($json);
		$citation = new Dase_DBO_Citation($this->db);
		$citation->faculty_eid = $fac->eid;
		foreach ($citation_data as $k => $v) {
			$citation->$k = $v;
		}
		if ($citation->findOne()) {
			$r->renderOk('no change');
		}
		if (!$citation->sort_order) {
			$citation->sort_order = 0;
		}
		if (!$citation->section_header) {
			$citation->section_header = ''; 
		}
		$citation->timestamp = date(DATE_ATOM);
		if ($citation->insert()) {
			$fac->citation_count = $fac->citation_count+1;
			$fac->update();
			$r->renderOk('success');
		} else {
			$r->renderError(400);
		}
	}

	public function getFacultyMemberJson($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$set = array();
		foreach ($fac as $k => $v) {
			$set[$k] = $v;
		}
		$set['links'] = array();
		$set['links']['citations'] = $r->app_root.'/faculty/'.$fac->eid.'/citations.json';
		$set['links']['self'] = $r->app_root.'/faculty/'.$fac->eid.'.json';
		$set['links']['edit'] = $r->app_root.'/faculty/'.$fac->eid;
		//$r->renderResponse(Dase_Json::get($fac->inflate($r)));
		$r->renderResponse(Dase_Json::get($set));
	}

	public function getCitationsJson($r) 
	{
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $r->get('eid');
		if (!$fac->findOne()) {
			$r->renderError(404,'no such eid');
		}
		$set = $fac->inflate($r);
		$set['sort'] = $r->get('sort');
		$set['limit'] = $r->get('limit');
		$r->renderResponse(Dase_Json::get($set));
	}

	public function getFaculty($r) 
	{
		//rev2
		$facs = new Dase_DBO_Faculty($this->db);
		//$facs->orderBy('sn');
		$facs->orderBy('eid');
		$set = $facs->findAll(1);
		$r->assign('facs',$set);
		$r->renderTemplate('faculty.tpl');
	}

	public function getFacultyJson($r) 
	{
		$facs = new Dase_DBO_Faculty($this->db);
		$set = array();
		foreach ($facs->findAll(1) as $fac) {
			$subset = array();
			$subset['eid'] = $fac->eid;
			$subset['cn'] = $fac->cn;
			$subset['links'] = array(
					'citations' => $r->app_root.'/faculty/'.$fac->eid.'/citations.json',
					'self' => $r->app_root.'/faculty/'.$fac->eid.'.json',
					);
			$set[] = $subset;
		}
		$r->renderResponse(Dase_Json::get($set));
	}

	public function postToFaculty($r) 
	{
		$user = $r->getUser('http');
		if (!$user->is_admin && !$user->is_superuser) {
			$r->renderError(401,'unauthorized');
		}
		$eid = trim($r->getBody());

		$record_exists = false;
		$fac = new Dase_DBO_Faculty($this->db);
		$fac->eid = $eid;
		if ($fac->findOne()) {
			$record_exists = true;
		} else {
			$fac->created_by = $user->eid;
			$fac->created = date(DATE_ATOM);
		}

		$data = getTedData($eid);
		if (is_array($data) && count($data)) {
			$fac->retrieved_from_ted = date(DATE_ATOM);
			foreach ($data as $k => $ar) {
				if (is_array($ar)) {
					$fac->$k = $ar[0];
				}
			}
		}
		if ($record_exists) {
			$fac->update();
		} else {
			$fac->insert();
		}
		$r->renderResponse($fac->asJson());
	}
}

function sortByName($a,$b)
{
	$a_str = strtolower($a['name']);
	$b_str = strtolower($b['name']);
	if ($a_str == $b_str) {
		return 0;
	}
	return ($a_str < $b_str) ? -1 : 1;
}


function parseOutYear($line) 
{
	$matches = array();
	preg_match_all("/(19|20)\d{2}/",$line,$matches);
	$res = $matches[0];
	$set = array();
	foreach ($res as $y) {
		if ($y > 1930 && $y < ((int) date('Y')+3) ) {
			$set[] = $y;
		}
	}

	if (isset($set[0])) {
		return max($set);
	} else {
		return '';
	}
}

function getTedData($eid) 
{
	include_once('/mnt/home/pkeane/.ted.conf/001.php');
	$base_dn = "ou=people,dc=entdir,dc=utexas,dc=edu";
	$filter = "(uid=$eid)";
	$ds = ldap_connect($ted['host'],636);
	$bth = @ldap_bind($ds,$ted['user'],$ted['pass']);
	$result = @ldap_search($ds,$base_dn,$filter);
	$entry_array = @ldap_get_entries($ds,$result);
	if (is_array($entry_array) && isset($entry_array[0])) {
		return $entry_array[0]; //first result
	}
}
