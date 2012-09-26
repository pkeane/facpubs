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
        //$item = Dase_DBO_Item::getBySernum($this->db,'home'); // what's this for?
        $fac = new Dase_DBO_Faculty($this->db);
        //$fac->eid = $r->get('eid');
        $fac->eid = $this->user->eid;
        $r->assign('fac',$fac->findOne());
        $r->renderTemplate('home.tpl');
	}

}

