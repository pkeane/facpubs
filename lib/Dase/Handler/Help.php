<?php

class Dase_Handler_Help extends Dase_Handler
{
		public $resource_map = array(
			'/' => 'help',
			'index' => 'help_index',
			'{unit}' => 'help_page',
		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
		}		
		
		public function getHelp($r) 
		{
			// $fac = new Dase_DBO_Faculty($this->db);
			// $fac->eid = $r->get('eid');
			// if (!$fac->findOne()) {
			// 	$r->renderError(404,'no such eid');
			// }
			// $r->assign('fac',$fac);  // I don't know why, but this was getting the wrong EID! I've temporarily switched the help page to be funneled through the Faculty handler temporarily, so that the right EID gets inserted into links.
			// $r->renderTemplate('help.tpl');  
			$t = new Dase_Template($r);
			$r-> renderTemplate('help.tpl');
		}
		
		public function getHelpIndex($r)
		{
				$t = new Dase_Template($r);
				$dir = BASE_PATH.'/templates/help';
				$files = array();
				$iterator = new DirectoryIterator($dir);
				foreach ($iterator as $fileinfo) {
						if ($fileinfo->isFile()) {
								if ('._' != substr($fileinfo->getFilename(),0,2)) {
										$files[] = str_replace('.tpl','',$fileinfo->getFilename());
								}
						}
				}
				$t->assign('files',$files);

				$r->renderResponse($t->fetch('help_index.tpl'));
				$r -> renderTemplate('help_index.tpl');
		}
		
		public function getHelpPage($r)
		{
				$t = new Dase_Template($r);
				$temp_path = 'help/'.$r->get('unit').'.tpl';
				$r->renderResponse($t->fetch($temp_path));
		}
}
