<?php

class Dase_Handler_Home extends Dase_Handler
{
		public $resource_map = array(
				'/' => 'home',

		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
		}

		public function getHome($r) 
		{
				// look up current user
				$fac = new Dase_DBO_Faculty($this->db);
				$fac->eid = $this->user->eid;

				//if not in FAculty table, create record using TED data
				if (!$fac->findOne()) {
						$fac->created_by = $this->user->eid;
						$fac->created = date(DATE_ATOM);
						$data = getTedData($this->user->eid);
						if (!$data) {
								$params['msg'] = 'no such EID '.$eid.' in the Enterprise Directory';
								$r->renderRedirect('error',$params);
						}
						if (is_array($data) && count($data)) {
								$fac->retrieved_from_ted = date(DATE_ATOM);
								foreach ($data as $k => $ar) {
										if (is_array($ar)) {
												$fac->$k = $ar[0];
										}
								}
						}
						$fac->insert();
				}
				$r->assign('fac',$fac);
				$r->renderTemplate('home.tpl');
		}
}

function getTedData($eid) 
{
		//include_once('/mnt/home/pkeane/.ted.conf/001.php');
		include('/mnt/www-data/publications/.ted.conf/001.php');
		$base_dn = "ou=people,dc=entdir,dc=utexas,dc=edu";
		$filter = "(uid=$eid)";
		$ds = ldap_connect($ted['host'],636);
		$bth = @ldap_bind($ds,$ted['user'],$ted['pass']);
		$result = @ldap_search($ds,$base_dn,$filter);
		$entry_array = @ldap_get_entries($ds,$result);
		if (is_array($entry_array) && isset($entry_array[0])) {
				return $entry_array[0]; //first result
		} else {
				return false;
		}
}

