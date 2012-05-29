<?php

class Application_Model_DbTable_Item extends Projet_Db_Table {
	const NAME = 'ITEMS';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
