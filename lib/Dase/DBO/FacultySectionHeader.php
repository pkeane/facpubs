<?php

require_once 'Dase/DBO/Autogen/FacultySectionHeader.php';

class Dase_DBO_FacultySectionHeader extends Dase_DBO_Autogen_FacultySectionHeader 
{

		public function getCitationCount() 
		{
				$citations = new Dase_DBO_Citation($this->db);
				$citations->section_id = $this->id;
				return $citations->findCount();
		}

}
