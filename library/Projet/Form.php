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
			 ->setEnctype(parent::ENCTYPE_URLENCODED);
		
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

}
