<?php

require_once 'Dase/DBO/Autogen/UploadedFile.php';

class Dase_DBO_UploadedFile extends Dase_DBO_Autogen_UploadedFile 
{

		public $versions = array();
		public $lines = array();

		public function __get($key)
		{
				if ('ago' == $key) {
						$ts = strtotime($this->uploaded);
						$now = time();
						$diff = $now - $ts;
						$ago['days'] = $diff/(3600*24);
						$ago['hours'] = $diff/3600;
						$ago['minutes'] = $diff/60;
						$ago['seconds'] = $diff;
						foreach ($ago as $str => $num) {
								if ($num > 2) {
										$num = floor($num);
										return "$num $str ago";
								}
						}
				}
				return parent::__get($key);
		}

		public function getLines()
		{
				$lines = array();
				$i = 0;
				foreach (preg_split('/(\r\n|\r|\n)/', $this->edited_text) as $line) {
						$this_line = trim($line);
						if ($this_line) {
								if (!isset($lines[$i])) {
										$lines[$i] = '';
								}
								$lines[$i] .= $this_line;
								$lines[$i] .= ' ';
						} else {
								$i++;
						}
				}
				$new_lines = array();
				foreach ($lines as $ln) {
						$ln = preg_replace('/\xc2\xa0/',' ',$ln);
						$ln = preg_replace('/\s\s+/',' ', $ln);
						$new_lines[] = trim($ln);
				}
				$text = join("\n\n",$new_lines);

				/*
				$lines = new Dase_DBO_Line($this->db);
				$lines->cv_id = $this->id;
				$this->lines = $lines->findAll(1);
				return $this->lines;
				 */

				$set = array();

				foreach (preg_split('/(\r\n|\r|\n)/', $text) as $string) {
						//get rid of curlies && em-dash
						$string = preg_replace('/\xe2\x80\x99/',"'",$string);
						$string = preg_replace('/\xe2\x80\x98/',"'",$string);
						$string = preg_replace('/\xe2\x80\x9c/','"',$string);
						$string = preg_replace('/\xe2\x80\x9d/','"',$string);
						$string = preg_replace('/\xe2\x80\x94/','-',$string);

						$string = preg_replace('/\xca\xbc/',"'",$string);
						$string = preg_replace('/\xca\xbb/',"'",$string);

						$string = trim($string);
						$string = trim($string, "\x00..\x1F");
						if ($string) {
								$set[] = $string;
						}
				}
				return $set;
		}

		public function file2text($filename,$new_path,$mime)
		{
				$rawtext = '';
				if ('application/pdf' == $mime) {
						copy($filename,$new_path);
						$outfile = $new_path.'.txt';
						$cmd = 'pdftotext -enc UTF-8 -layout "'.$new_path.'" "'.$outfile.'"';
						print system($cmd);
						$rawtext = @file_get_contents($outfile);
				}

				if ('application/msword' == $mime) {
						copy($filename,$new_path);
						$outfile = $new_path.'.txt';
						$cmd = 'antiword -m UTF-8 '.$new_path.' > '.$outfile;
						print system($cmd);
						$rawtext = @file_get_contents($outfile);
				}

				if ('application/applefile' == $mime) {
						copy($filename,$new_path);
						$outfile = $new_path.'.txt';
						$cmd = 'antiword -m UTF-8 '.$new_path.' > '.$outfile;
						print system($cmd);
						$rawtext = @file_get_contents($outfile);
				}

				if ('application/vnd.openxmlformats-officedocument.wordprocessingml.document' == $mime) {
						$new_path = str_replace('.unknown','.docx',$new_path);
						copy($filename,$new_path);
						$outfile = str_replace('.docx','.txt',$new_path);
						$cmd = '/usr/bin/perl '.BASE_PATH.'/bin/docx2txt.pl "'.$new_path.'" "'.$outfile.'"';
						print system($cmd);
						$rawtext = @file_get_contents($outfile);
				}
				if (!$rawtext) {
						$rawtext = "no text";
				}
				return $rawtext;
		}

		public function convert()
		{
				if ($this->rawtext) {
						return;
				}
				$filename = '/mnt/www-data/publications/cvproc/'.$this->faculty_eid.'/'.$this->name;
				$new_path = '/mnt/www-data/publications/cvproc/'.$this->faculty_eid.'/'.$this->name.'.txt';
				$mime = $this->upload_mime;
				$rawtext = $this->file2text($filename,$new_path,$mime);
				$this->rawtext = $rawtext;
				$this->rawtext_md5 = md5($rawtext);
				$this->rawtext_size = strlen($rawtext);
				$this->converted = date(DATE_ATOM);
				$this->workflow_state = 'converted';
				$this->update();

		}
}
