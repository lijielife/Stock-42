<?php
/**
 * @brief classe de base pour les models/Mapper
 * @author amboise.lafont
 * @version
 */
class Projet_Mapper {
#	const DATE_FIN = "01/01/2038";
	/**
	 * @var Projet_Db_Table
	 */
	private $_dbTable;
	private $_sDefaultTable;

	protected function setDbTable($dbTable) {
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if(! $dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('invalide table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;

	}

	protected function getDbTable() {
		if (null === $this-> _dbTable) {
			$this->setDbTable($this->_sDefaultTable);
		}
		return $this->_dbTable;
	}

	/**
	 * le construcuteur est appelé par les classes filles
	 * @param $sDefaultTable classe par défaut pour faire l'interface avec la base de données
	 */
	public function __construct($sDefaultTable) {
		$this->_sDefaultTable = $sDefaultTable;
	}


	/**
	 *  @brief construit le tableau des attributs de la table SQL associés à leur valeurs (sauf l'id)
	 *
	 *  La méthode effectue une requête sur la base de donnée pour obtenir les noms des colonnes
	 *  de la table SQL. Elle appelle dynamiquement les getters de $oEntite pour calculer les valeurs
	 *
	 *  Les conventions de nommages sont les suivantes :
	 *  - la primary key se nomme ID
	 *  - chaque caractère '_' dans le nom de colonne est supprimé et le caractère suivant est mis en
	 *  majuscule. Le reste est en minuscule (sauf le premier caractère)
	 *
	 *  Exemple : ID_REF_UNITE => IdRefUnite => appel de $oEntite->getIdRefUnite()
	 *
	 *  @param $oEntite entité utilisé pour remplir le tableau
	 *  @return le tableau des noms de colonnes de la table associés à leur valeur (sauf l'id)
	 * @deprecated
	 */
	public function getDataArray($oEntite) {
		// Récupérer l'objet DbTable pour la liaison avec la base de donnée
		$oTable = $this->getDbTable();

		// ->info() renvoie un tableau associatif dont la clé 'cols' contient le tableau
		// des noms de colonnes de la table SQL
		$aColumns = $oTable->info();
		$aColNames = $aColumns['cols'];

		// Tableau à remplir (et à renvoyer)
		$aData = array();

		foreach ($aColNames as $key) {
			// Ignoration de l'id, comme annoncé
			if ($key != 'ID') {
				/* Calcul du nom du getter à appeler
				 *
				 * strtolower($key) : le nom de colonne en miniscule
				 *
				 * explode('_', strtolower($key)) : séparation du nom de la colonne en sous-chaines
				 * 	initialement séparés par '_'
				 *
				 * ucfirst : mets la première lettre en majuscule de la chaine donné en paramètre
				 * array_map(ucfirst, $aArray) : met la première lettre de chaque chaine du tableau $aArray
				 * 	en majuscule
				 *
				 * implode('', $aArray) : réuni toutes les chaines du tableau $aArray en
				 * 	une seule chaine de caractère
				 *
				 * 'get'. : c'est un getter donc on préfixe par get
				 */
				$sMethodName = 'get'.implode('',
						array_map("ucfirst", explode('_', strtolower($key))));
				try {
					// appel de la méthode calculée pour remplir le tableau
					$aData[$key] = $oEntite->$sMethodName();
				} catch (Zend_Exception $e) {
					
				}
			}

		}
		return array_filter($aData);
	}

	function formatDate($sChampDate) {
		return "DATE_FORMAT($sChampDate, '%d-%m-%Y')";
	}
	
	function formatDateTime($sChampDate) {
		return "DATE_FORMAT($sChampDate, '%d-%m-%Y %Hh%im%ss')";
	}
	/**
	 * Liste totale.
	 * @return RowSet (spl)
	 */
	public function fetchAllDataTable() {
		$oTable = $this->getDbTable();
		return $oTable->genericSelect();
	}

	/**
	 * @brief mets à jour l'entité $oEntite en base de donnée
	 *
	 * La méthode utilise $oEntite->getId() pour obtenir la PRIMARY KEY
	 * @param $oEntite l'entité à mettre à jour physiquement en base de donnée
	 */
	public function update($oEntite) {
		$this->updateRaw($oEntite->getId(), $this->getDataArray($oEntite));
	}

	/**
	 * @brief ajoute l'entité $oEntite en base de donnée
	 *
	 * La méthode utilise $oEntite->setId() pour affecter la PRIMARY KEY à l'issue
	 * de l'opération
	 * @param $oEntite l'entité à ajouter physiquement en base de donnée
	 */
	public function add(Projet_Entite $oEntite) {
		$oEntite->setId($this->addRaw($this->getDataArray($oEntite)));
		return $oEntite->getId();
	}

	/**
	 * @brief sauvegarde l'entité $oEntite en base de donnée
	 *
	 * La méthode utilise $oEntite->getId() pour déterminer s'il s'agit d'une
	 * mise à jour ou d'un ajout en base de donnée : si l'id est défini, c'est une
	 * mise à jour.
	 *
	 * @param $oEntite l'entité à sauvegarder physiquement en base de donnée
	 */
	public function save(Projet_Entite $oEntite) {
		// si l'id n'existe pas, alors l'entité n'existe pas en base
		if (!$oEntite->getId()) {
			// donc c'est un ajout
#			$oEntite->setPer($this->getDbTable()->PDO_datetimeFormat(self::DATE_FIN));//on met la date de péremption à loin dans le temps
			//$oEntite->setMoe($this->getDbTable()->PDO_datetimeFormat());
			return $this->add($oEntite);
		} else {
			// sinon c'est une mise à jour
			$this->update($oEntite);
			return null;
		}
	}

	/**
	 * Permet de changer l'état d'activité d'un élement
	 * selon les clés qui ont été rentrées dans le tableau (seul la présence d'une clé est requise
	 * @brief	Change l'état d'activité d'un élément.
	 * @author	francoisespinet
	 * 	@param ArrayObject $oEntite
	 * 	@param array $aDates tableau de moe=> 'date', per=>'date
	 */
	public function changerActivite(Projet_Entite $oEntite, array $aDates = array()) {
		if (array_key_exists('moe',$aDates)) {
			$oEntite->setMoe($aDates['moe']);
		}

		if (array_key_exists('per',$aDates)) {
			$oEntite->setPer($aDates['per']);
		}

		$this->update($oEntite);
	}



	/**
	 * @brief sauvegarde l'entité $aTabl au format tableau en base de donnée
	 *
	 * La méthode admire $aTabl['ID'] pour déterminer s'il s'agit d'une
	 * mise à jour ou d'un ajout en base de donnée : si l'id est défini, c'est une
	 * mise à jour.
	 *
	 * @param $aTabl l'entité à sauvegarder physiquement en base de donnée au format tableau
	 * @return l'id en base de donné
	 */
	public function saveRaw($aTabl) {
		$id = $aTabl['ID'];
		unset($aTabl['ID']);
		// si l'id existe pas, alors l'entité existe en base
		if ($id) {
			// donc c'est une mise à jour
			$this->updateRaw($id, $aTabl);
			return $id;
		}
		else {
#			$aTabl['PER'] = $this->getDbTable()->PDO_datetimeFormat(self::DATE_FIN);//on met la date de péremption à loin dans le temps
			// sinon c'est une mise à jour
			return $this->addRaw($aTabl);
		}
	}

	/**
	 * @brief mets à jour l'entité $aTabl au format tableau en base de donnée
	 *
	 * le tableau doit avoir les clés égales aux noms des attributs
	 *
	 * @param $id l'id de l'attribut à mettre à jour
	 * @param $aTabl l'entité à mettre à jour physiquement en base de donnée au format tableau
	 */
	public function updateRaw($id, $aTabl) {
		$this->getDbTable()->update($aTabl, array ('ID = ?' => $id));
	}

	/**
	 * @brief ajoute l'entité $aTabl au format tableau en base de donnée
	 *
	 * le tableau doit avoir les clés égales aux noms des attributs
	 * La méthode renvoie l'id à l'issue
	 *
	 * @param $aTabl l'entité à ajouter physiquement en base de donnée au format tableau
	 * @return l'id correspondant à l'entrée créé
	 */
	public function addRaw($aTabl) {
		return ( $this->getDbTable()->insert($aTabl));
	}

	public function find($id) {
		return $this->getDbTable()->find($id);
	}

	public function delete($oEntite) {
		$this->changerActivite($oEntite, array('per' => $this->getDbTable()->PDO_datetimeFormat()));
	}

	public function deleteRaw($id) {
		$this->updateRaw($id, array('PER' => $this->getDbTable()->PDO_datetimeFormat()));
	}

	public function getAsArray(array $aCols, $bPer = true) {
		$oDb = $this->getDbTable();

		$oSelect = $oDb->select()->from($oDb, $aCols);

		$bPer && $this->addWhereActive($oSelect);
		// On renvoie un tableau de tableau
		return $oDb->fetchAllArray($oSelect);

	}
	
	/**
	 * @brief	Verifie si un elément existe en base par son id
	 *
	 *
	 * @author	francoisespinet
	 * @version 7 mars 2012 - 11:20:35
	 * @param int $nId
	 */
	public function existsInDatabase($nId) {
		return $this->getDbTable()->isRecordExists($nId);

	}

	protected function addWhereActive(Zend_Db_Table_Select $oSelect, $prefix = '') {
		$prefix && $prefix .= '.';
		$date = new Zend_Date();
		$date->add('1', Zend_Date::DAY);
		return $oSelect->where($prefix.'PER > ?', $this->getDbTable()->PDO_dateFormat($date));
	}

	protected function listerIdPair($prop, $bPer = true) {
		return $this->listerPair('ID', $prop, $bPer);
	}

	protected function listerPair($cle, $prop, $bPer = true) {
		$oSelect = $this->getDbTable()->select()
			->from($this->getDbTable(), array($cle, $prop))
			->order($prop);

		//$bPer && $this->addWhereActive($oSelect);

		return $this->getDbTable()->fetchPairs($oSelect);
	}

	public function getLibelles() {
		return $this->listerIdPair('LIBELLE');
	}


}
