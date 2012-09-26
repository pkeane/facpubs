<?php

class Dase_Handler_Helpfiles extends Dase_Handler
{
		public $resource_map = array(
				'/' => 'index',
				'{unit}' => 'help_page',
		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
		}

		public function getIndex($r) 
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
		}

		public function getHelpPage($r) 
		{
				$t = new Dase_Template($r);
				$temp_path = 'help/'.$r->get('unit').'.tpl';
				$r->renderResponse($t->fetch($temp_path));
		}
}

