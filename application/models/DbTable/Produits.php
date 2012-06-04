<?php

class Application_Model_DbTable_Produits extends Projet_Db_Table {
	const NAME = 'PRODUITS';


	public function __construct() {
		parent::__construct(self::NAME);
	}

}
