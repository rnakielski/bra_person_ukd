<?php 
namespace Cobra3\BraPersonUkd\Service\Email;

class EmailService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * objectManager
	 *
	 * @var TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;
	

	/**
	 * configurationManager
	 *
	 * @var TYPO3\CMS\Core\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;
	
	
	/**
	 * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
	 * @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
	 * @param string $subject subject of the email
	 * @param string $templatePath template path (string starting with typo3conf)
	 * @param string $templateName template name (UpperCamelCase)
	 * @param array $variables variables to be passed to the Fluid view
	 * @return boolean TRUE on success, otherwise false
	 */
	public function sendTemplateEmail(array $recipient, array $sender, $subject, $templatePath, $templateName, array $variables = array()) {
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
		$emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
	
		//$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		//$templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
		
		//$templatePathAndFilename = $templateRootPath . 'Email/' . $templateName . '.html';
		//$templatePathAndFilename = 'typo3conf/ext/speciality/Resources/Private/Plugin/Insidemed/Templates/Email/' . $templateName . '.html';
		$templatePathAndFilename = $templatePath . $templateName . '.html';
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);
		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();
	
		/** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
		$message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
		$message->setTo($recipient)
		->setFrom($sender)
		->setSubject($subject);
	
		// Possible attachments here
		//foreach ($attachments as $attachment) {
		//    $message->attach($attachment);
			//}
	
			// Plain text example
			//$message->setBody($emailBody, 'text/plain');
	
			// HTML Email
			$message->setBody($emailBody, 'text/html');
	
			$message->send();
			return $message->isSent();
	}
}	
	
?>	
