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

class Dase_Handler_Admin extends Dase_Handler
{
    public $resource_map = array(
        '/' => 'admin',
        'user/email' => 'user_email',
        'directory' => 'directory',
        'users' => 'users',
        'add_user_form/{eid}' => 'add_user_form',
        'user/{id}/is_admin' => 'is_admin',
        'create' => 'content_form',   
        'citation_count' => 'citation_count',
        'faculty/form' => 'faculty_form',
    );

    protected function setup($r)
    {
        $this->user = $r->getUser();
        if ($this->user->is_admin) {
            //ok
        } else {
            $r->renderError(401);
        }
    }

    public function getDirectory($r) 
    {
        if ($r->get('lastname')) {
            $results = Utlookup::lookup($r->get('lastname'),'sn');
            $results_eid = Utlookup::lookup($r->get('lastname'),'uid');
            usort($results,'sortByName');
            $r->assign('lastname',$r->get('lastname'));
            $r->assign('results',$results);
            $r->assign('results_eid',$results_eid);
        }
        $r->renderTemplate('framework/admin_directory.tpl');
    }

    public function postToUserEmail($r)
    {
        $this->user->email = $r->get('email');
        $this->user->update();
        $r->renderRedirect('admin');
    }

    public function getAdmin($r) 
    {
        $r->renderTemplate('framework/admin.tpl');
    }

    public function getUsers($r) 
    {
        $users = new Dase_DBO_User($this->db);
        $users->orderBy('name');
        $r->assign('users', $users->findAll(1));
        $r->renderTemplate('framework/admin_users.tpl');
    }

    public function getAddUserForm($r) 
    {
        $record = Utlookup::getRecord($r->get('eid'));
        $u = new Dase_DBO_User($this->db);
        $u->eid = $r->get('eid');
        if ($u->findOne()) {
            $r->tpl->assign('user',$u);
        }
        $r->assign('record',$record);
        $r->renderTemplate('framework/admin_add_user.tpl');
    }

    public function postToUsers($r)
    {
        $record = Utlookup::getRecord($r->get('eid'));
        $user = new Dase_DBO_User($this->db);
        $user->eid = $record['eid'];
        if (!$user->findOne()) {
            $user->name = $record['name'];
            $user->email = $record['email'];
            $user->insert();
        } else {
            //$user->update();
        }
        $r->renderRedirect('admin/users');

    }

    public function deleteIsAdmin($r) 
    {
        $user = new Dase_DBO_User($this->db);
        $user->load($r->get('id'));
        $user->is_admin = 0;
        $user->update();
        $r->renderResponse('deleted privileges');
    }

    public function putIsAdmin($r) 
    {
        $user = new Dase_DBO_User($this->db);
        $user->load($r->get('id'));
        $user->is_admin = 1;
        $user->update();
        $r->renderResponse('added privileges');
    }
    
    public function getCitationCount($r)
    {
	$c = new Dase_DBO_Citation($this->db);
	$count = $c->findCount();
	$r->assign('count',$count);
	$r->renderTemplate('citation_count.tpl');
    }
    
    public function getFacultyForm($r) 
    {
	$r->renderTemplate('framework/admin_faculty_form.tpl');
    }   

    public function postToFacultyForm($r) 
    {
	$record_exists = false;
	$fac = new Dase_DBO_Faculty($this->db);
	$fac->eid = $r->get('eid');
	if ($fac->findOne()) {
		$record_exists = true;
	} else {
		$fac->created_by = $this->user->eid;
		$fac->created = date(DATE_ATOM);
	}
	$data = getTedData($r->get('eid'));
	if (!$data) {
		$params['msg'] = 'no such EID '.$r->get('eid');
		$r->renderRedirect('admin/faculty/form',$params);
	}
	if (is_array($data) && count($data)) {
		$fac->retrieved_from_ted = date(DATE_ATOM);
		foreach ($data as $k => $ar) {
			if (is_array($ar)) {
				$fac->$k = $ar[0];
			}
		}
	}
	if ($record_exists) {
		$fac->update();
	} else {
		$fac->insert();
	}
	$params['msg'] = 'added '.$r->get('eid').' '.$fac->cn;
	$r->renderRedirect('admin/faculty/form',$params);
    }


}

function getTedData($eid) 
{
		include_once('/mnt/home/pkeane/.ted.conf/001.php');
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

