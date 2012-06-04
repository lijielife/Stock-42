<?php
class Projet_Controller_Action_Ajax extends Projet_Controller_Action {
	
	
	public function init() {
		$this->_helper->layout->disableLayout();		# Désactivation du layout
	}
	
	/**
	 * methode qui permet de lister la totalité des objets étant donné un service
	 * @brief	Description de la méthode.
	 * @author	francoisespinet
	 * 	@param Heliops_Service_Interface $oService
	 */
	public function lister($oService,$fonction = 'lister') {
		try {
			$this->view->aData = $oService->$fonction();
		} catch (Exception $e) {
			if (APP_DEBUG) {
				throw new Zend_Exception('AJAX_ERR', 'Erreur lors de la recupération en ajax', $e);
			} else {
				echo $this->view->translate('error.recup.ajax.liste');
				return array();
			}
		}
	}
	
	public function desactiver(Heliops_Service_Interface $oService) {
		$nId = $this->_getParam('id');

		if(isset($nId)) {
            try {
				$oService->desactiver($nId);
                $this->view->message = 'message.suppression.succes';
            }
            catch (Exception $e) {
                if (APP_DEBUG) {
                    throw $e;
                }
                $this->view->message = 'message.suppression.echec';
			}
		}
		
	}
	
	/**
	 * @brief Envoie à la vue un script de fermeture automatique si le formulaire est un suces
	 *
	 * Selon le code retour nRet du formulaire envoie le script à la vue
	 *
	 * @author		francoisespinet
	 * @version		27 mars 2012 - 14:23:57
	 * @throws
	 *
	 * @param 	int $nRet le code retour du formulaire
	 * @param 	int $nRetard les ms de retard
	 */
	public function autoCloseModale($nRet, $nRetard = 1500) {
		if ($nRet == parent::FORM_SUCCES) {
			$this->view->CloseScript = 'closeModalWithDelay(2000);';
		}
		else {
			$this->view->CloseScript = '';
		}
	}
	
}
