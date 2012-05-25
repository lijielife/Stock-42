<?php
// Pourquoi faire $this->oAdapter = $this->getAdapter() quand on y a déjà acès par $this->_db ?

/**
 * Gestionnaire métier relatif aux 'TABLES'
 * Etend la classe Zend_Db_Table_Abstract
 *
 * @name		Table
 * @see			Zend/Db/Table/Abstract.php
 * @category	Projet
 * @package		application
 * @subpackage	models_Table
 */
class Projet_Db_Table extends Zend_Db_Table_Abstract {
	
	protected $_adapter;	# string	: nom de l'adapter
	protected $_name;		# string	: nom de la table
	protected $_primary;	# array		: tableau des clés primaires
	protected $_sequence;	# string	: nom de la séquence ORACLE
	
	protected $oAdapter;	# object	: Zend_Db_Adapter_Abstract
	protected $oSelect;		# object	: Zend_Db_Adapter_Abstract
	
	/**	@brief Constante du format DATETIME.
	 * Constante du format d'un DATETIME exploité par Zend_Date.
	 * @var	const DATE_SELECT.
	 * @var	const DATE_FORMAT.
	 * @var	const DATETIME_SELECT.
	 * @var	const DATETIME_FORMAT.
	 */
	const DATE_SELECT		= PDO_DATE_FORMAT;			# Format DATE exploité lors d'une requête SELECT
	const DATE_FORMAT		= ZEND_DATE_FORMAT;			# Format DATE exploité lors d'une requête INSERT / UPDATE
	const DATETIME_SELECT	= PDO_DATETIME_FORMAT;		# Format DATETIME exploité lors d'une requête SELECT
	const DATETIME_FORMAT	= ZEND_DATETIME_FORMAT;		# Format DATETIME exploité lors d'une requête INSERT / UPDATE
	
	/**
	 * Constructeur de classe.
	 *
	 * @param	string			$sTable			: nom de la table.
	 * @param	string|array	$pPrimary		: clé(s) primaire(s) (nom du champ)
	 * @param	string			$sRowClass		: nom de la classe personnalisee pour les Row
	 * @param	string			$sRowsetClass	: nom de la classe personnalisee pour les Rowset
	 */
	public function __construct($sTable, $pPrimary='ID', $sRowClass = 'Projet_Db_Row', $sRowsetClass = 'Projet_Db_Rowset') {
		
		try {
			$this->_name = $sTable;
			$this->_primary = $pPrimary;

			// Construction de l'accès à la base de données
			parent::__construct(array());//Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('db'));
			$this->setRowClass($sRowClass);
			$this->setRowsetClass($sRowsetClass);
			
			// Initialise la séquence
			$this->_sequence = $this->_name."_".$this->_primary."_SEQ";
			
			// Récupération de l'adapteur à la base.
			$this->oAdapter = $this->getAdapter();
		} catch (Exception $e) {
			throw new Zend_Exception("BAD_ACCESS", __METHOD__, $e);
		}
		
	}

	/**	@brief Dernière occurrence de la table.
	 * Permet d'initialiser l'objet sequence qui fait référence à la table.
	 * retourne la dernière occurrence de la séquence.
	 * @return	integer dernière clé de la table.
	 * @throws	exception Zend_Exception en cas d'erreur au cours de la requête.
	 */
	public function lastInsertId() {
		try {
			// Fonctionnalité réalisée si la séquence est réalisée (ORACLE)
			$nLastId = $this->oAdapter->lastSequenceId($this->_sequence);

			// Fonctionnalité réalisée dans le cas de MySQL
			if (empty($nLastId)) {
				$nLastId = $this->oAdapter->lastInsertId($this->_name, $this->_primary);
			}

			// Renvoi l'occurrence de la dernière insertion
			return $nLastId;
		} catch (Exception $e) {
			throw new Zend_Exception("Impossible d'initialiser l'objet séquence !", $e->getMessage(), $e);
		}
	}

	/**
	 * Permet d'exécuter une expression SQL.
	 *
	 * @param	string			$pSql			: expression SQL.
	 * @return	array un tableau de donnée.
	 */
	public function exprQuery($pSql) {
		try {
			$this->oAdapter->setFetchMode(Zend_Db::FETCH_ASSOC);
			return $this->oAdapter->fetchAll($pSql);
		} catch (Exception $e) {
			throw new Zend_Exception("BAD_QUERY", __METHOD__, $e);
		}
	}

	/**
	 * Permet d'exécuter une expression SQL INSERT | UPDATE ou DELETE.
	 *
	 * @param	string 			$pSql			: expression SQL.
	 * @return	bool
	 */
	public function exprExcuteQuery($pSql) {
		try {
			//$this->oAdapter->setFetchMode(Zend_Db::FETCH_ASSOC);
			return $this->oAdapter->query($pSql);
		} catch (Exception $e) {
			throw new Zend_Exception("BAD_QUERY", __METHOD__, $e);
		}
	}

	/**	@brief FetchAll.
	 * Permet d'obtenir un tableau BIDIMENTIONNEL pour chaque ligne de résultat de la forme :
	 * array(array('key_1' => "Valeur 1", 'key_2' => "Valeur 2", ... , 'key_Z => "Valeur Z")).
	 *
	 * @li La clé est l'occurence de la ligne de résultat.
	 * @li La valeur est un tableau associatif entre le nom du champ et sa valeur en base.
	 * @li Aucune ligne de résultat n'est écrasée par une autre : les données sont brutes.
	 *
	 * @param	object|string	$pSelect		: objet Zend_Db_Select ou string Sql.
	 * @throws	lève une exception de type Zend_Exception en cas d'erreur de requete.
	 * @return	array retourne tableau représentant chaque ligne de résultat sous forme array('key'=>"value").
	 */
	public function fetchAll($pSelect) {
		try {
			return parent::fetchAll($pSelect)->toArray();
		} catch (Exception $e) {

			throw new Zend_Exception("BAD_QUERY", __METHOD__, $e);
		}
	}

	/**	@brief	FetchAll au format tableau de tableau pur
	 *
	 * Permet d'obtenir un tableau BIDIMENSIONNEL pour chaque ligne de résultat de la forme :
	 * array(array(0 => "Valeur 1", 1 => "Valeur 2", ... , Z => "Valeur Z")).
	 *
	 * @param	object|string	$pSelect		: objet Zend_Db_Select ou string Sql.
	 * @return	array retourne tableau représentant chaque ligne de résultat sous forme d'un tableau
	 * @author amboise.lafont
	 */
	public function fetchAllArray($pSelect) {
		return $this->getAdapter()->fetchAll($pSelect, array(), Zend_Db::FETCH_NUM);
	}
	
	/**	@brief	Renvoie une seule valeur
	 *
	 * la valeur
	 *
	 * @param	object|string	$pSelect		: objet Zend_Db_Select ou string Sql.
	 * @return	la valeur
	 * @author amboise.lafont
	 */
	public function fetchOne($pSelect) {
		return $this->getAdapter()->fetchOne($pSelect);
	}
	
	/**	@brief	FetchAll au format tableau unidimensionnel pour une seule colonne
	 *
	 * Permet d'obtenir la liste de la première colonne sélectionnée
	 *
	 * @param	object|string	$pSelect		: objet Zend_Db_Select ou string Sql.
	 * @return	array
	 * @author amboise.lafont
	 */
	public function fetchCol($pSelect) {
		return $this->getAdapter()->fetchCol($pSelect);
	}
	
	/**	@brief FetchPairs.
	 * Permet d'obtenir un tableau UNIDIMENTIONNEL de chaque rang sous forme de couples de la forme :
	 * array('key' => "Valeur du premier champ").
	 *
	 * @li La clé est le résultat de la première colonne sélectionnée dans la requête,
	 * @li La valeur est le résultat de la deuxième colonne sélectionnée dans la requête.
	 * @li Si des clés ont des doublons, alors ils seront écrasés par la dernière occurence.
	 *
	 * @param	object|string	$pSelect		: objet Zend_Db_Select ou string Sql.
	 * @throws	lève une exception de type Zend_Exception en cas d'erreur de requete.
	 * @return	array retourne tableau de paires clés/valeurs.
	 */
	public function fetchPairs($pSelect) {
		try {
			$sSql = is_string($pSelect) ? $pSelect : $pSelect->__toString();
			return $this->oAdapter->fetchPairs($sSql);
		} catch (Exception $e) {
			throw new Zend_Exception("BAD_QUERY", __METHOD__, $e);
		}
	}
	
	/**
	 * @brief	Retourne la valeur correspondante à la ligne id et la colone sCol
	 *
	 *
	 *
	 * @author		francoisespinet
	 * @version		19 mars 2012 - 13:40:11
	 * @throws
	 *
	 * @param 	int $nId L'id demandé
	 * @param 	string $sCol La colone qui contient la valeur voulue
	 * @return	string|int|date Le contenu demandé
	 */
	public function findCol($nId, $sCol) {
		$oSelect = $this->select()->from($this, array($sCol))
					   ->where('ID = ?', $nId);
		return $this->fetchOne($oSelect);
	}
	
	public function fetchRowArray($pSelect) {
		return $this->getAdapter()->fetchRow($pSelect, array(), Zend_Db::FETCH_NUM);
	}
	public function getTableInfo($sKey = null) {
		return $this->info($sKey);
	}
	
	/**	@brief Nom de la table.
	 * Méthode permettant de récupérer le nom de la table.
	 * @return string
	 */
	public function getTableName() {
		return $this->_name;
	}

	/**	@brief Structure de la table.
	 * Méthode permettant de récupérer la structure de la table.
	 * @return array
	 */
	public function getTableStructure() {
		return self::getTableInfo('cols');
	}

	/**
	 * Méthode permettant de récupérer la clé primaire de la table
	 *
	 * @param	integer			$nId			: identifiant du tableau "$this->_primary", par défaut : "1"
	 * @return	string
	 */
	public function getPrimaryKey($nId = 0) {
		is_array($this->_primary) ? $xReturn = $this->_primary[$nId] = array_pop($this->_primary) : $xReturn = $this->_primary;
		return $xReturn;
	}
	
	/**	@brief Requête SELECT abstraite.
	 * Méthode permettant de rechercher directement un élément par son identifiant.
	 * @li Exploite la méthode abstraite @a find() de @a Zend_Db_Table.
	 * @param	integer			$nId			: identifiant de la clé primaire.
	 * @return	array tableau UNIDIMENSIONNEL contenant le résultat de la requête.
	 * @throws	exception Zend_Exception en cas d'erreur au cours de la requête.
	 * @see		Zend/Db/Table/Abstract.php
	 */
	public function find($nId) {
		try {
// 			// Exploitation de la méthode du parent
// 			$aSearch = parent::find(intval($nId));
// 			// Fonctionnalité réalisée si une entrée a été trouvée
// 			if (Projet_DataHelper::isValideArray($aSearch)) {
// 				// Renvoi de la première occurrence sous forme de tableau
// 				return Projet_DataHelper::getArrayOccurrence($aSearch);
// 			} else {
// 				// Renvoi d'un tableau vide
// 				return array();
// 			}
		} catch (Exception $e) {
			throw new Zend_Exception($e->getMessage(), __METHOD__, $e);
		}
	}

	
	/** @brief Formatage d'un champ DATE.
	 * Méthode permettant de générer une chaîne formatée pour enregistrer une date en base de données.
	 * @li Prend en charge la localisation de l'interface de l'utilisateur courante.
	 * @param	string			$pDate			: chaîne de caractères représentant une DATE.
	 */
	public function PDO_dateFormat($pDate = null) {
		// Récupération de la date selon l'interface utilisateur
		$oDate = new Zend_Date($pDate, null, 'fr-FR');
		
		// Formatage en fonction de l'adapter de la base de données
		if (strtoupper(APP_PDO_ADAPTER) == "PDO_OCI") {
			// Formatage exploitable avec PDO_OCI
			$sFormat = new Zend_Db_Expr("TO_DATE('".$oDate->toString(self::DATE_FORMAT, null, 'fr')."', 'YYYY-MM-DD')");
		} else {
			// Formatage exploitable avec PDO_MYSQL
			$sFormat = new Zend_Db_Expr("DATE_FORMAT('".$oDate->toString(self::DATE_FORMAT, null, 'fr')."', '%Y%m%d')");
		}
		// Renvoi du format DATE
		return $sFormat;
	}
	
	/**	@brief Formatage d'un champ DATETIME.
	 * Méthode permettant de générer une chaîne formatée pour enregistrer un datetime en base de données.
	 * @li Prend en charge la localisation de l'interface de l'utilisateur courante.
	 * @param	string			$pDatetime		: chaîne de caractères représentant un DATETIME.
	 */
	public function PDO_datetimeFormat($pDatetime = null) {
		// Récupération de la date selon l'interface utilisateur
		$oDate = new Zend_Date($pDatetime, null, Projet_DataHelper::getLocation());
		
		// Formatage en fonction de l'adapter de la base de données
		if (strtoupper(APP_PDO_ADAPTER) == "PDO_OCI") {
			// Formatage exploitable avec PDO_OCI
			$sFormat = new Zend_Db_Expr("TO_TIMESTAMP('".$oDate->toString(self::DATETIME_FORMAT, null, 'fr')."', 'YYYY-MM-DD HH24:MI:SS')");
		} else {
			// Formatage exploitable avec PDO_MYSQL
			$sFormat = new Zend_Db_Expr("DATE_FORMAT('".$oDate->toString(self::DATETIME_FORMAT, null, 'fr')."', '%Y%m%d%H%i%s')");
		}
		// Renvoi du format DATETIME
		return $sFormat;
	}
	
	/** @brief Vérification de la présence d'une valeur.
	 * Méthode permettant de vérifier la présence d'une valeur dans un champ de la table.
	 * @param	string			$sField			: nom du champ où réaliser la recherche.
	 * @param	string|integer	$sValue			: valeur du champ à rechercher.
	 * @return	bool résulat de la vérification.
	 */
	public function isRecordExists($sValue = null, $sField = 'ID') {

		// Initialisation du validateur
		$oValidator = new Zend_Validate_Db_RecordExists(
			$this->_name,		# Nom de la table
			$sField,			# Nom du champ de la table
			null,				# Exclusions
		    $this->oAdapter		# Adapter de la table
		);
		// Recherche de la présence de la valeur
		return $oValidator->isValid($sValue);
	}
	

	/** @brief	Fonction de concaténation en SQL
	 *
	 * Permet de concaténer des champs en SQL (fonction CONCAT de MySQL)
	 * Cette méthode est à placer dans un SELECT.
	 * Prend un nombre variable d'arguments
	 *
	 * Exemple :
	 *
	 * @code
	 * $oSelect->from('TABLE', array('ID', $oDb->concatSql('LIBELLE', '-', 'ABREGE'));
	 * @endcode
	 *
	 * produit pour mysql une requete du type :
	 * SELECT ID, CONCAT(LIBELLE, '-', ABREGE) FROM TABLE;
	 *
	 * ce qui renvoie un tableau de lignes d'ID associé à la concaténation du champ LIBELLE, de '-', et de ABREGE
	 *
	 * @param string $sExpr,... expressions à concaténer
	 * @return code SQL concaténant les expressions
	 * @author amboise.lafont
	 */
	function concatSql() {
		if (APP_PDO_ADAPTER == "PDO_MYSQL") {
			return new Zend_Db_Expr('CONCAT('.implode(',', func_get_args()).')');
		}
		else {
			// syntaxe sous oracle
			return new Zend_Db_Expr(implode('||', func_get_args()));
		}
	}

	/** @brief	Concaténation pour clause GROUP BY en SQL
	 *
	 * Permet de concaténer une liste d'enregistrements groupé par une clause GROUP BY (fonction GROUP_CONCAT de MySQL).
	 * A placer dans un select.
	 *
	 * Exemple :
	 *
	 * @code
	 * $oSelect->from('TABLE', array('ID_TYPE', $oDb->groupConcatSql('LIBELLE'))
	 * 	->group('ID_TYPE');
	 * @endcode
	 *
	 * produit pour mysql une requete du type :
	 * SELECT ID, GROUG_CONCAT(LIBELLE SEPARATOR ', ') FROM TABLE GROUP BY ID_TYPE;
	 *
	 * ce qui renvoie un tableau de lignes d'ID_TYPE associé à une chaine du style Libelle1, Libelle2, ..
	 *
	 * @param string $sCol libelle de la colonne dont les valeurs doivent être concaténés
	 * @param string $separator séparateur entre les valeurs à concaténer
	 * @return code SQL concaténant la liste des champs demandés
	 * @author amboise.lafont
	 */
	function groupConcatSql($sCol, $separator = ', ') {
		if (APP_PDO_ADAPTER == "PDO_MYSQL") {
			return new Zend_Db_Expr('GROUP_CONCAT('.$sCol.' SEPARATOR '.$this->getAdapter()->quote($separator).')');
		}
		else {
			// syntaxe sous oracle 11g Release 2
			return new Zend_Db_Expr('LISTAGG('.$sCol.','.$this->getAdapter()->quote($separator).') WITHIN GROUP (ORDER BY '.$sCol.')');
		}
	}

	/** @brief	Fonction de mise en forme de date en SQL
	 *
	 * Permet de mettre en forme une date en SQL (fonction TO_CHAR de oracle)
	 * Cette méthode est à placer dans un SELECT.
	 *
	 * Exemple :
	 *
	 * @code
	 * $oSelect->from('TABLE', array($oDb->dateTimeSql('DATEMODE'));
	 * @endcode
	 *
	 * produit pour oracle une requete du type :
	 * SELECT TO_CHAR(DATEMODE, 'dd/MM/YYYY HH:mm:ss') FROM TABLE;
	 *
	 *
	 * @param string $sCol libellé de la colonne de date à formater
	 * @return code SQL formatant une date
	 * @author amboise.lafont
	 */
	static function dateTimeSql($sCol) {
		// Fonctionnalité réalisée si la base est sous MySQL
		if (APP_PDO_ADAPTER == "PDO_MYSQL") {
			return new Zend_Db_Expr('DATE_FORMAT('.$sCol.', "%d/%m/%Y %T")');
			return $sCol;
		} else {
			// en oracle
			return new Zend_Db_Expr('TO_CHAR('.$sCol.", '".PDO_DATETIME_FORMAT."')");
		}
	}
	
}
