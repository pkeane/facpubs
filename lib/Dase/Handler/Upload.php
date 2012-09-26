<?php

function sortByName($a,$b)
{
		$a_str = strtolower($a['name']);
		$b_str = strtolower($b['name']);
		if ($a_str == $b_str) {
				return 0;
		}
		return ($a_str < $b_str) ? -1 : 1;
}

class Dase_Handler_Upload extends Dase_Handler
{
		public $resource_map = array(
				'{eid}' => 'uploader',
		);

		protected function setup($r)
		{
				$this->user = $r->getUser();
				if (!$this->user->is_admin && ($r->get('eid') != $this->user->eid)) {
						$r->renderError(401,'unauthorized');
				}
		}

		public function postToUploader($r)
		{
				$fac = new Dase_DBO_Faculty($this->db);
				$fac->eid = $r->get('eid');
				if (!$fac->findOne()) {
						$r->renderError(404,'no such eid');
				}
				$file = $r->getFile('uploaded_file');
				$name = $file->getClientOriginalName();
				$path = $file->getPathName();
				$type  = $file->getMimeType();
				if (!is_uploaded_file($path)) {
					$r->renderError(400,'no go upload');
				}
				if (!isset(Dase_File::$types_map[$type])) {
					$r->renderError(415,'unsupported media type: '.$type);
				}

				$base_dir = "/mnt/www-data/publications/cvproc/".$fac->eid;

				if (!file_exists($base_dir)) {
						mkdir($base_dir);
				}

				$ext = 'unknown';
				if ('application/pdf' == $type) {
						$ext = 'pdf';
				}
				if ('application/msword' == $type) {
						$ext = 'doc';
				}
				if ('application/vnd.openxmlformats-officedocument.wordprocessingml.document' == $type) {
						$ext = 'docx';
				}

				$newname = md5(time().$name).'.'.$ext;
				$new_path = $base_dir.'/'.$newname;
				rename($path,$new_path);
				chmod($new_path,0755);
				$cv = new Dase_DBO_UploadedFile($this->db);
				$cv->upload_mime = $type;
				$cv->upload_note = 'from cvproc';
				$cv->other_updated = '';
				$cv->uploaded_by = $this->user->eid;
				$cv->orig_name  = $name;
				$cv->name  = $newname;
				$cv->faculty_eid = $fac->eid;
				$cv->uploaded = date(DATE_ATOM);
				$cv->insert();

				//now convert
				$cv->convert();

				$r->renderRedirect('file/'.$fac->eid.'/view/'.$cv->id);
		}
}

