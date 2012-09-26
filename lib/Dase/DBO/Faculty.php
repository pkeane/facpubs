<?php

require_once 'Dase/DBO/Autogen/Faculty.php';

class Dase_DBO_Faculty extends Dase_DBO_Autogen_Faculty 
{
		public $citations = array();
		public $sections = array();
		public $stats = array();
		public $needs_to_certify = false;
		public $proxies = array();

		public function getProxies()
		{
				$ps = new Dase_DBO_FacultyProxy($this->db);
				$ps->faculty_eid = $this->eid;
				foreach ($ps->find() as $proxy) {
						$proxy = clone($proxy);
						$this->proxies[$proxy->proxy_eid] = $proxy;
				}
				return $this->proxies;
		}

		public function getSections($include_empty=false,$tag='')
		{
				$secs = new Dase_DBO_FacultySectionHeader($this->db);
				$secs->faculty_eid = $this->eid;
				$secs->orderBy('version, sort_order');

				$citation = new Dase_DBO_Citation($this->db);
				$citation->faculty_eid = $this->eid;
				$citation->orderBy('sort_order');
				if ($tag) {
						$citation->$tag = 1;
				}
				$citations = $citation->findAll(1);
				$this->citations = $citations; //allow us to display total count
		
				$stats['total'] = count($citations);
				$stats['reviewed'] = 0;
				$stats['is_peer'] = 0;
				$stats['is_creative'] = 0;
				//MANY array iterations :(
				//
				$sections = array();

				/* none section */
				$section = array();
				$section['id'] = 'none';
				$section['title'] = '[uncategorized]';
				$section['citations'] = array();

				$most_recent_citation_review = '';

				foreach ($citations as $c) {
						if (!$c->section_id) {
								$section['citations'][] = $c;
						}
						if ($c->reviewed) {
								$stats['reviewed'] += 1;
						}
						if ($c->is_peer) {
								$stats['is_peer'] += 1;
						}
						if ($c->is_creative) {
								$stats['is_creative'] += 1;
						}
						if ($c->reviewed > $most_recent_citation_review) {
								$most_recent_citation_review = $c->reviewed;
						}
				}

				$stats['to_be_reviewed'] = $stats['total'] - $stats['reviewed'];

				if (0 == $stats['to_be_reviewed'] || ($most_recent_citation_review > $this->certified_citations)) {
						$this->needs_to_certify = true;
				}

				$this->stats = $stats;

				//none section unneccessary if empty
				if (count($section['citations'])) {
						$sections[] = $section;
				}

				foreach ($secs->findAll(1) as $s) {
						$section = array();
						$section['id'] = $s->id;
						$section['title'] = $s->text;
						$section['citations'] = array();
						foreach ($citations as $c) {
								if ($s->id == $c->section_id) {
										$section['citations'][] = $c;
								}
						}
						if ($include_empty) {
								$sections[] = $section;
						} else {
								if (count($section['citations'])) {
										$sections[] = $section;
								}
						}
				}
				$this->sections = $sections;
				return $this->sections;
		}

		public function canCertify() 
		{
				$citations = new Dase_DBO_Citation($this->db);
				$citations->faculty_eid = $this->eid;
				$citations->addWhere('reviewed','null','is');
				if ($citations->findCount()) {
						return false;
				}
				$citations = new Dase_DBO_Citation($this->db);
				$citations->faculty_eid = $this->eid;
				$citations->reviewed = '';
				if ($citations->findCount()) {
						return false;
				}
				return true;
		}

		public function getCitations($r) 
		{

				if ($r->get('limit')) {
						$limit = $r->get('limit');
				} else {
						$limit = null;
				}

				if ($r->get('sort')) {
						$sort = $r->get('sort');
				} else {
						$sections = new Dase_DBO_FacultySectionHeader($this->db);
						$sections->faculty_eid = $this->eid;
						$sections->orderBy('version, sort_order');
						$set = array();
						$set['none'] = array();
						foreach ($sections->findAll(1) as $section) {
								$set[$section->text] = array();
						}
						$citations = new Dase_DBO_Citation($this->db);
						$citations->faculty_eid = $this->eid;
						$citations->orderBy('section_header, sort_order');
						if ($limit) {
								$citations->setLimit($limit);
						}
						foreach ($citations->findAll(1) as $c) {
								if ($c->section_header) {
										$set[$c->section_header][] = $c;
								} else {
										$set['none'][] = $c;
								}
						}
						foreach ($set as $section => $cs) {
								foreach($cs as $cite) {
										$this->citations[] = $cite;
								}
						}
						return $this->citations;
				}

				//other sorting
				
				$citations = new Dase_DBO_Citation($this->db);
				$citations->faculty_eid = $this->eid;
				if ('timestamp' == $sort) {
						$citations->orderBy('timestamp DESC');
				}
				if ('year' == $sort) {
						$citations->orderBy('year DESC');
				}
				if ($limit) {
						$citations->setLimit($limit);
				}
				$this->citations = $citations->findAll(1);
				return $this->citations;
		}

		public function inflate($r)
		{
				$this->getCitations($r);
				$set = array(
						'eid' => $this->eid,
						'name' => $this->cn,
						'links' => array(),
						'citations' => array(),
				);
				$set['links']['citations'] = $r->app_root.'/faculty/'.$this->eid.'/citations.json';
				$set['links']['faculty'] = $r->app_root.'/faculty/'.$this->eid.'.json';
				foreach ($this->citations as $c) {
						$subset = array(
								'id' => $c->id,
								'is_peer' => $c->is_peer,
								'is_creative' => $c->is_creative,
								'original_text' => $c->text,
								'revised_text' => $c->revised_text,
								'annotation_text' => $c->annotation_text,
								'year' => $c->year,
								'section_header' => $c->section_header,
								'links' => array(
										'self' => $r->app_root.'/faculty/'.$this->eid.'/citation/'.$c->id.'.json',
										'edit' => $r->app_root.'/faculty/'.$this->eid.'/citation/'.$c->id,
								),
						);
						$set['citations'][] = $subset;
				}
				return $set;
		}
}
