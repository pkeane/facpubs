<?php

class Dase_Handler_File extends Dase_Handler
{
		public $resource_map = array(
				'{eid}/file/{id}' => 'file',
				'{eid}/view/{id}' => 'file_view',
				'{eid}/text/{id}' => 'edited_text',
				'{eid}/rawtext/{id}' => 'rawtext',
				'{eid}/lines/{id}' => 'lines',
		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
				if (!$this->user->is_admin && ($r->get('eid') != $this->user->eid)) {
						$r->renderError(401,'unauthorized');
				}
				$fac = new Dase_DBO_Faculty($this->db);
				$fac->eid = $r->get('eid');
				if (!$fac->findOne()) {
						$r->renderError(404,'no such eid');
				}
				$this->fac = $fac;
				$file = new Dase_DBO_UploadedFile($this->db);
				if (!$file->load($r->get('id'))) {
						$r->renderError('404');
				}
				if ($file->faculty_eid != $fac->eid) {
						$r->renderError('401');
				}
				$this->file = $file;
		}

		public function deleteFile($r)
		{
				//$user = $r->getUser('http');
				if ($this->user->is_admin || ($this->file->faculty_eid == $this->user->eid))  {
						/*
						$doomed = "/mnt/www-data/publications/cvproc/".$this->file->name;
						if (is_file($doomed)) {
								if (unlink($doomed)) {
								}
						}
						 */
						if ($this->file->delete()) {
								$r->renderResponse('delete successful');
						} else {
								$r->renderError(500);
						}
				} else {
						$r->renderError(401);
				}
		}

		public function putEditedText($r)
		{
				$this->file->edited_text = $r->getBody();
				$this->file->update();
				$r->renderResponse('text updated');
		}

		public function getLinesJson($r)
		{
				$lines = $this->file->getLines();
				$r->renderResponse(Dase_Json::get($lines));
		}

		public function getLines($r)
		{
				$t = new Dase_Template($r);
				$lines = $this->file->getLines();
				$t->assign('lines',$lines);
				$t->assign('fac',$this->fac);
				$t->assign('file',$this->file);
				$r->renderResponse($t->fetch('file_lines.tpl'));
		}

		public function getRawtext($r)
		{
				$r->renderResponse($this->file->rawtext);
		}

		public function getFilePdf($r)
		{
				return $this->getFile($r);
		}

		public function getFileDoc($r)
		{
				return $this->getFile($r);
		}

		public function getFile($r)
		{   
				$filename = '/mnt/www-data/publications/cvproc/'.$this->fac->eid.'/'.$this->file->name;
				$r->serveFile($filename,$this->file->upload_mime,true);
		}


		public function getFileView($r)
		{
				$t = new Dase_Template($r);
				$t->assign('fac',$this->fac);
				$t->assign('file',$this->file);
				$r->renderResponse($t->fetch('file.tpl'));

		}
}

