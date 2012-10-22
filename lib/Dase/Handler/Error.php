<?php

class Dase_Handler_Error extends Dase_Handler
{
		public $resource_map = array(
				'/' => 'error',

		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
		}

		public function getError($r) 
		{
				$r->renderTemplate('error.tpl');
		}
}

