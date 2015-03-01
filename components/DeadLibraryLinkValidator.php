<?php
/**
 * CValidator that validates the connection of a link using the Zend_Http_Cliend of the Zend framework.
 * The validation will not be executed if the extended validation is disabled in the space/user settings of the LibraryModule.
 *  
 * @package humhub.modules.library.components
 * @author Sebastian Stumpf
 *
 */
class DeadLibraryLinkValidator extends CValidator {
	
	/** PUSH or PULL **/
	public $type;
	/** timeout for connection **/
	public $timeout;
	
	public function validateAttribute($object, $attribute) {
		if(!$this->isEnabled()) {
			return;
		}
		try {
			$client = new Zend_Http_Client($object->$attribute, array(
				'timeout' => $this->timeout,
			));
			$response = $client->request($this->type);
		} catch(Zend_Uri_Exception $e) {
			$this->addError($object, $attribute, $e->getMessage());
			return;
		} catch( Zend_Http_Client_Exception $e) {
			$this->addError($object, $attribute, $e->getMessage());
			return;
		}
		if($response->isError()) {
			$this->addError($object, $attribute, 'Error: '.$response->getStatus().' '.$response->getMessage());
		}
	}
	
	/**
	 * Checks if the extended validation is enabled in the Libraries space/user settings.
	 * 
	 * @return boolean
	 */
	private function isEnabled() {
		$validateDeadLinks = Yii::app()->getController()->contentContainer->getSetting('enableDeadLinkValidation', 'library');
		// set default if setting empty
		if($validateDeadLinks == '' || $validateDeadLinks == null) {
			$validateDeadLinks = 0;
		}
		return $validateDeadLinks > 0;
	}
}

?>