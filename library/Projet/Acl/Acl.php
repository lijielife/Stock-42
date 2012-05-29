<?php

/**
 * Classe de création des ACL via un fichier de configuration INI.
 * 
 * @category   Projet
 * @package    library
 * @subpackage ACL
 * @author     jeanchristophe.fraillon
 */
class Projet_Acl_Acl extends Zend_Acl {
	/** @brief	rôle à utiliser par défaut
	 * @see defaultIsAllowed
	 * @author amboise.lafont
	 */
	static protected $default_role = null;
	/** @brief	Acl à utiliser par défaut
	 * @see defaultIsAllowed
	 * @author amboise.lafont
	 */
	static protected $default_acl = null;

	/**
	 * Constructeur de la classe qui permet la récupération des droits
	 * depuis le fichier de configuration généralement trouvé dans config/acl.ini.
	 * 
	 * @brief	Lecture des droits ACLs.
	 * @param	string $sFile
	 */
	public function __construct($fAclFile = false){

		if (!$fAclFile && file_exists(APPLICATION_PATH.'/configs/acl.ini')) {
			// Récupération du fichier.
			$fAclFile = APPLICATION_PATH.'/configs/acl.ini';
		}

		// Récupération des différents roles de l'application.
		$roles = new Zend_Config_Ini($fAclFile, 'roles');
		$this->_setRoles($roles);

		// Récupération des ressources et des droits liés.
		$aRessources = new Zend_Config_Ini($fAclFile, 'ressources');
		$this->_setRessources($aRessources);


		// Récupération des différents privilèges.
		foreach ($roles->toArray() as $role => $sNull) {			
			$privileges = new Zend_Config_Ini($fAclFile, $role);
			$this->_setPrivileges($role, $privileges);
		}		
	}

	/** @brief	Détermine si une ressource est accessible pour le rôle et la ressource par défaut
	 *
	 * Fournit un interface pour les Acl accessible partout dans l'appplication
	 * 
	 * @author amboise.lafont
	 */
	static public function defaultIsAllowed($resource) {
		return self::$default_acl->isAllowed(self::$default_role, $resource);
	}

	/**
	 * fonction de création des différents roles de l'application
	 * @param array $roles
	 */
	protected function _setRoles($roles) {
		foreach ($roles as $role => $parents) {
			if (empty($parents)) {
				$parents = null;
			} else {
				$parents = explode(',', $parents);
			}

			$this->addRole(new Zend_Acl_Role($role), $parents);
		}
		return $this;
	}


	/**
	 * fonction qui crée les diverses ressources de l'application
	 * @param array $aRessources ressource définit dans le acl.ini
	 **/
	protected function _setRessources($aRessources) {
		foreach ($aRessources as $ressource => $sNull) {
			$this->addResource($ressource);
		}
		return $this;
	}


	/**
	 * 
	 * fonction définissant les privilèges en fonction du profil et des ressources
	 * @param int $role id du profil
	 * @param array $privileges ressource de l'application
	 */
	protected function _setPrivileges($role, $aPrivileges) {
		// avec première boucle on récupère la permission et la ressource
		foreach ($aPrivileges as $do => $aRessources) {			
			foreach ($aRessources as $sRessource => $sNull) {
				// do donne le type de permission allow ou deny
				// ressource donne la ressource sous forme : controller_action
				// role donne le profil concerné
				$this->{$do}($role, $sRessource);
			}
		}
		return $this;
	}


	/**
	 * le super-admin à droit à tout. Oui monsieur.
	 * TODO: supprimer ces privilèges indécents au vu de la conjoncture actuelle
	 * Nous ne pouvons plus nous permettre de telles inégalités au sein
	 * de notre nation si nous voulons l'union sacrée
	 * @author amboise.lafont
	 */
	public function addResource($resource, $parent = null) {
		parent::addResource($resource, $parent);
	}

	/** @brief	Renvoie la resource
	 *
	 * Crée la ressource si elle n'existe pas
	 * La surcharge de cette méthode de Zend_Acl permet de créer automatiquement
	 * la ressource si elle n'existe pas quand on fait des allow ou des deny
	 * par exemple.
	 *
	 * @author amboise.lafont
	 */
	public function get($resource)
	{
		if (!$this->has($resource)) {
			$this->addResource($resource);
		}

		return parent::get($resource);
	}


	static public function setDefaultRole($role) {
		self::$default_role = $role;
	}

	static public function setDefaultAcl($acl) {
		self::$default_acl = $acl;
	}



}
