<?php

class Dase_Handler_User extends Dase_Handler
{
	public $resource_map = array(
		'/' => 'user',
		'settings' => 'settings',
		'email' => 'email'
	);

	protected function setup($r)
	{
		$this->user = $r->getUser();
	}
	
// not in use - settings displayed at framework/admin.tpl
	public function getSettings($r) 
	{
		$r->renderTemplate('framework/admin.tpl');
	}

	public function getUser($r) 
	{
		$r->renderTemplate('framework/home.tpl');
	}

	public function postToEmail($r)
	{
		$this->user->email = $r->get('email');
		$this->user->update();
		$r->renderRedirect('user/settings');
	}
}

