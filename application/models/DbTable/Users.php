<?php

class Application_Model_DbTable_Users extends Projet_Db_Table {
	const NAME = 'USERS';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
