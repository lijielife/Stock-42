<?php
/** @brief	Classe mère des formulaire
 *
 * classe mere pour tout les formulaires avec un construct générique
 * Elle regrouppe tous les paramètrages communs à la majorité des formulaires
 *
 * @author	francois.espinet
 * @uses	Zend_Form
 */
class Projet_Form extends Zend_Form {
	// nom du bouton submit
	const SUBMIT = "ValiderSubmit";
	//nom du bouton ID
	const ID = 'ID';

	public function __construct($name, array $option = array()) {
		
		parent::__construct();
		//on met la methode post par défault pour tous les formulaires
		//on met l'enctype classique
		$this->setMethod(parent::METHOD_POST)
			 ->setEnctype(parent::ENCTYPE_URLENCODED)
			 ->setAction('#');
		
		//on met le nom du formulaire passé en paramètre
		$this->setName($name);
		//si dans les options upload est à vrai alors on met le enctype pour un upload
		if (array_key_exists('upload', $option) && $option['upload'] == true) {
			$this->setEnctype(parent::ENCTYPE_MULTIPART);
		};
		$this->addPrefixPath('Projet_Form_Decorator', 'Projet/Form/Decorator', 'decorator');
		$this->addElementPrefixPath('Projet_Form_Decorator', 'Projet/Form/Decorator', 'decorator');
	}
	
	/**
	 * @brief	Ajoute les decorateurs par default de l'application
	 *
	 *
	 * ajout des décorateurs par défaut
	 *
	 * @author	francoisespinet
	 * @version 2 mars 2012 - 10:00:57
	 */
	protected function setDefaultDecorators() {
		$this->setDecorators(array('FormElements', 'Form', 'FormErreurGlobale'));
		//$this->setDecorators(array('Generic','Form'));
	}
	
	protected function addSubmit($sText = 'Valider') {
		$this->addElement($this->createSubmit($sText));
	}
	
	protected function createSubmit($sText = 'Valider') {
		$oSubmit = new Projet_Form_Element_Submit(self::SUBMIT);
		$oSubmit->setLabel($sText);
		return $oSubmit;
	}
	
	protected function addElementsWithSubmit(array $aElements, $sText = null) {
		$this->addElements($aElements);
		$this->addSubmit($sText);
	}
	
	/** @brief	nom du service associ√© au formulaire
	 *
	 * A chaque formulaire est associ√© un service qui s'occupe de
	 * remplir les valeurs par d√©faut des champs (populate) et d'enregistrer
	 * en base lors d'un post
	 *
	 * A red√©finir dans chaque classe fille
	 *
	 * @author amboise.lafont
	 */
	protected $sService;
	
	/** @brief	objet formulaire associ√© au formulaire
	 *
	 * l'objet n'est instanci√© qu'en cas de besoin
	 *
	 * @author amboise.lafont
	 */
	
	protected $oService = null;
	
	/** @brief	nom de la m√©thode du service pour sauvegarder les donn√©s du formulaires
	 *
	 * Cette m√©thode doit prendre $this->getValues() en param√®tre
	 *
	 * A red√©finir dans chaque classe fille
	 *
	 * @author amboise.lafont
	 */
	protected $sSvcSaveMethode = 'formSave';
	
	/** @brief	nom de la m√©thode du service pour chercher les donn√©s du formulaire
	 *
	 * Cette m√©thode doit prendre un $id en param√®tre (pour le populate)
	 *
	 * A red√©finir dans chaque classe fille
	 *
	 * @author amboise.lafont
	 */
	protected $sSvcDataMethode = 'formGet';

	/** @brief	renvoie le service associ√© au formulaire
	 */
	public function getService() {
		// instanciation du service s'il n'existe pas
		if (!$this->oService) {
			$this->oService = new $this->sService;
		}
		return $this->oService;
	
	}
	
	public function getSvcSaveMethode() {
		return $this->sSvcSaveMethode;
	}
	
	public function getSvcDataMethode() {
		return $this->sSvcDataMethode;
	}
	
	/**
	 * @brief	Enregistrement des m√©thodes pour g√©rer le formulaire dans le service
	 *
	 *
	 * @author	francoisespinet
	 * @version 7 mars 2012 - 11:00:17
	 * @param string $sService
	 * @param string $sSvcDataMethode
	 * @param string $sSvcSaveMethode
	 */
	public function setSrvMethodes($sService, $sSvcSaveMethode, $sSvcDataMethode='') {
		$this->sService = $sService;
		$this->sSvcDataMethode = $sSvcDataMethode;
		$this->sSvcSaveMethode = $sSvcSaveMethode;
	}

}
