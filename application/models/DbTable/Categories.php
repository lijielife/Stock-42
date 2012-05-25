<?php

class Application_Model_DbTable_Categories extends Projet_Db_Table {
	const NAME = 'CATEGORIES';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
