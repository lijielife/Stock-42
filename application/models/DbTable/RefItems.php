<?php

class Application_Model_DbTable_RefItems extends Projet_Db_Table {
	const NAME = 'REF_ITEMS';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
