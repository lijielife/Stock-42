<?php

class Application_Model_DbTable_RefLogins extends Projet_Db_Table {
	const NAME = 'REF_LOGINS';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
