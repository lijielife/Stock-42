<?php

class Layout_Helper_ParseMessages extends Zend_View_Helper_Abstract {
	
	public function parseMessages($messages = null) {
		if ($messages === null ) {
			return;
		}
		$sMessages = '';
		foreach ($messages as $message) {
			$sMessages .= $this->constructGenericMessage($message);
		}
		
		return $sMessages;
	}
	
	//TODO:gerer les différentes importance de messages
	public function constructGenericMessage($message, $importance = 0) {
		$sClasse = 'message';
		
		return "<p class=$sClasse>$message<p/>";
	}
	
}