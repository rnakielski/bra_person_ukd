<?php 
namespace Cobra3\BraPersonUkd\Task;
class ImportCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController{ 

	/**
	 * emailService
	 *
	 * @var  Cobra3\BraPersonUkd\Service\Email\EmailService
	 * @inject
	 */
	protected $emailService;

	/**
	 * @var TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 * @inject
	 */
	protected $tsService;

	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;
	
        /**
	 * Object Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;
  
	
	/**
	 * Person Repository
	 *
	 * @var \Cobra3\BraPersonUkd\Domain\Repository\PersonRepository
	 * @inject
	 */
	protected $personRepository;
	
	/**
	 * Import Repository
	 *
	 * @var \Cobra3\BraPersonUkd\Domain\Repository\ImportRepository
	 * @inject
	 */
	protected $importRepository;
	
    /**
	 * Storage Repository
	 *
	 * @var \TYPO3\CMS\Core\Resource\StorageRepository
	 * @inject
	 */
    protected $storageRepository;
    
   /**
        
	/**
	 * The settings.
	 * @var array
	 */
	protected $settings = array();

	/**
	 * The document Root
	 * @var string
	 */
	protected $docRoot = '';

	/**
	 * The log.
	 * @var array
	 */
	protected $log = array();

	/**
	 * Warnings.
	 * @var integer
	 */
	protected $warnings = 0;

	/**
	 * Errors.
	 * @var integer
	 */
	protected $errors = 0;

	/**
	 * Kind
	 * @var boolean
	 */
	protected $new = true;

	/**
	 * countJobs
	 * @var integer
	 */
	protected $countJobs = 0;
	
	/**
	 *  Import Command
	 *
	 * @return void
	 */
	public function ImportCommand() {
        // get settings
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        //$this->log($extbaseFrameworkConfiguration['plugin.']['tx_brapersonukd.']['settings.']);
        $this->settings = $this->tsService->convertTypoScriptArrayToPlainArray($extbaseFrameworkConfiguration['plugin.']['tx_brapersonukd.']['settings.']);
        //$this->log($this->settings['x']);
        $configurationArray = array(
                        'persistence' => array('storagePid' => $this->settings['import']['storagePid']),
                        'persistence' => array('recursive' => 9)
        );
        $this->configurationManager->setConfiguration($configurationArray);		

        $this->docRoot = $this->settings['import']['docRoot'];

        /*$this->log(count($files) . ' Dateien gefunden. Maximal ' . $this->settings['import']['maxObjectsAtOnce'] . ' werden verarbeitet.');
        foreach($filesToProcess as $file){
                $this->log('******************************************************************************************');
                $this->log('Datei gefunden ' . $file);
                $this->processSingleFile($file);
        }
        $this->log('******************************************************************************************');
        if(count($files) > (int)$this->settings['import']['maxObjectsAtOnce']){
            $this->log('Noch ' . (count($files) - (int)$this->settings['import']['maxObjectsAtOnce']) . ' Dateien zu verarbeiten!');
        }*/
        $personsToMigrate = $this->personRepository->getPersonsToMigrate($this->settings['import']['limit']);
        $this->log('Personen gefunden: ' . count($personsToMigrate));
        foreach($personsToMigrate as $personToMigrate){
            $this->processSinglePerson($personToMigrate);
            //$this->log('verarbeite uid: ' . $personToMigrate['uid'] . ' ' . $personToMigrate['firstname'] .  ' ' . $personToMigrate['lastname']);
            /*foreach($personToMigrate as $key => $value){
                $this->log($key . ' : ' . $value);
            }*/
                
        }
        
        $this->writeLog();
        //$this->mail('imp news (' . count($filesToProcess) . ' von ' . count($files) . ')' );
	}
	
	public function processSinglePerson($personToMigrate) {
	    $this->log('******************************************************************************************');
	    $this->log('verarbeite uid: ' . $personToMigrate['uid'] . ' ' . $personToMigrate['firstname'] .  ' ' . $personToMigrate['lastname']);
	    //ToDo neu/alt ggf lesen
	    $person = $this->personRepository->findOneByOldId($personToMigrate['uid']);
	    if($person){
	        $this->log('Person bereits vorhanden - Update');
	        $this->new = false;
	    }
	    else{
	        $this->log('neue Person - Insert');
	        $person = $this->objectManager->get('Cobra3\BraPersonUkd\Domain\Model\Person');
	        $this->new = true;
	    }
	    $this->getPersonData($personToMigrate, $person);
	    
	    $addresses = $this->personRepository->getAddressForPerson($personToMigrate['uid']);
	    if(count($addresses) == 0){
	        $this->log('keine Adresse zur Person gefunden');
	    }
	    if(count($addresses) > 1){
	        $this->log(count($addresses) . ' Adressen zur Person gefunden - neueste wird verarbeitet');
	    }
        if(count($addresses) > 0){
            $address = $addresses[0];
            $this->log(count($addresses) . ' Adresse zur Person gefunden. uid: ' . $address['uid']);
            $this->getAddressData($address, $person);
        }
        
        // ToDo Image
        if($personToMigrate['image']){
            $this->log('versuche Bild zu laden: ' . $personToMigrate['image']);
            $this->getImage($personToMigrate['pid'], $personToMigrate['image'], $person);
        }
        
        // ToDo setPid
        $person->setPid(1);
        
        //persist
        if($this->new){
            $this->personRepository->add($person);
        }
        else{
            $this->personRepository->update($person);
        }
        $this->personRepository->setImportDone($personToMigrate['uid']);
	}
	
	protected function getImage($pid, $imagePath, $person){
	    //$tempfile = "/var/www/vhosts/typo3-7/httpdocs/fileadmin/importTempFile";
	    $tempfile = $this->docRoot . "importTempFile";
	    $prefix = "http://www.uniklinik-duesseldorf.de";
	    if(substr($imagePath, 0, 1) != '/'){
	        $prefix .= '/';
	    }
	    $url = $prefix . $imagePath;
	    $ch = \curl_init($url);
	    $fp = fopen($tempfile, "w");
	    \curl_setopt($ch, CURLOPT_FILE, $fp);
	    \curl_setopt($ch, CURLOPT_HEADER, 0);
	    \curl_exec($ch);
	    $error = \curl_error ($ch);
	    $errorNo = \curl_errno ($ch);
	    \curl_close($ch);
	    fclose($fp);
	    if($error OR $errorNo){
	        $this->log('Curl-Error: ' . $error . ' ' . $errorNo);
	    }
	    else{
	        $filesize = filesize($tempfile);
    	    $this->log('Datei geladen! Größe:' . $filesize);
    	    if($filesize < 100){
    	        $this->log('Datei zu klein - wird nicht verwendet!');
    	    }
    	    else{
    	        $path = $this->getFilePath($pid);
    	        $this->log('Datei rename zu: ' .  $path . basename($imagePath));
    	        //rename($tempfile , $path . basename($imagePath));
    	        $storage = $this->storageRepository->findByUid(1);    // get file storage with uid 1 (this should by default point to your fileadmin/ directory)
    	        $file = $storage->getFile("importTempFile");
    	        $newFile = $storage->moveFile(
    	            $file,
    	            $storage->getFolder($path),
    	            $targetFileName = basename($imagePath),
    	            $conflictMode = 'replace'
    	        ); 	
    	        $fileObject = $storage->getFile($path . basename($imagePath)); // create file object for the image (the file will be indexed if necessary)
    	        $this->log('Image-UID: ' . $fileObject->getUid());
    	        //FileReference anlegen oder heraussuchen
    	        if($person->getImage()){
    	            $falMedia = $person->getImage();
    	        }
    	        else{
    	            $falMedia = $this->objectManager->get('Cobra3\BraPersonUkd\Domain\Model\FileReference');
    	        }
    	        $falMedia->setFile($fileObject);
    	        //$falMedia->setTablenames('tx_brapersonukd');
    	        $person->setImage($falMedia);
    	        
    	        //go on
    	    }
	    }
	    if(is_file($tempfile )){
	        unlink($tempfile);
	    }
	    
	}
	
	protected function getFilePath($pid){
	    //$dir = "/var/www/vhosts/typo3-7/httpdocs/fileadmin/importPersonen/pid" . $pid . "/";
	    $dir = $this->docRoot . "importPersonen/pid" . $pid . "/";;
	    $dirInStorage = "/importPersonen/pid" . $pid . "/";
	    if (!is_dir($dir)) {
	        mkdir($dir);
	    }
	    return $dirInStorage;
	}
	
	protected function getPersonData($personToMigrate, $person){
	    //ToDo ggf crdate und tstamp übernehmen? z.Z. keine setter
	    //$person->setCrdate($personToMigrate['crdate']);
	    $person->setOldId($personToMigrate['uid']);
	    $person->setPosition($personToMigrate['position']);
	    $person->setSalutation($personToMigrate['salutation']);
	    $person->setSex($personToMigrate['sex']);
	    $person->setTitle($personToMigrate['title']);
	    $person->setFirstname($personToMigrate['firstname']);
	    $person->setLastname($personToMigrate['lastname']);
	    $person->setJob($personToMigrate['job']);
	    $person->setOldImageId($personToMigrate['old_image_id']);
	    $person->setJobTitle($personToMigrate['job_title']);
	    if($personToMigrate['countryJob']){
	       $person->setCountryJob($personToMigrate['countryJob']);
	    }
	    $person->setMedicalAssociation($personToMigrate['medical_association']);
	    $person->setLinkMedicalAssociation($personToMigrate['link_medical_association']);
	    $person->setAddress($personToMigrate['address']);
	}
	
	protected function getAddressData($address, $person){
	    $person->setRoomNr($address['room_nr']);
	    $person->setBuildingLevel($address['building_level']);
	    $person->setBuildingNr($address['building_nr']);
	    $person->setStreet($address['street']);
	    $person->setStreetNr($address['street_number']);
	    $person->setPostOfficePOst($address['post_office_post']);
	    $person->setCountryCode($address['country_code']);
	    $person->setCountry($address['country']);
	    $person->setZipCode($address['zip_code']);
	    $person->setCity($address['city']);
	    $person->setPhone1($address['phhone1']);
	    $person->setPhone2($address['phhone2']);
	    $person->setFax($address['fax']);
	    $person->setMobilePhone($address['mobile_phone']);
	    $person->setEmail($address['email']);
	    $person->setHomepage($address['homepage']);
	    $person->setMisc($address['misc']);
	    $person->setPersonIdFk($address['person_id_fk']);
	    $person->setUnitIdFk($address['unit_id_fk']);
	    $person->setDepartmentIdFk($address['department_id_fk']);
	    $person->setHomepageTitle($address['homepage_title']);
	    $person->setHomepagePageTitle($address['homepage_page_title']);
	    $person->setHomepagePage($address['homepage_page']);
	    $person->setFilelinkTitle($address['filelink_title']);
	    $person->setFilelink($address['filelink']);
	    $person->setEmail2($address['email2']);
	}
	
	public function log($logString, $severity = 0) {
		$preText = '';
		if($severity == 1){
			$this->warnings++;
			$preText = 'WARNING ';
		}
		if($severity == 2){
			$this->errors++;
			$preText = 'ERROR ';
		}
		$this->log[] = $preText . $logString;
	}
	
	public function writeLog(){
	    //$myfile = fopen("/var/www/vhosts/typo3-7/httpdocs/fileadmin/importlog.txt", "w") or die("Unable to open file!");
	    $myfile = fopen($this->docRoot . "importlog.txt", "w") or die("Unable to open file!");
	    foreach($this->log as $l){
	        fwrite($myfile, $l . "\n");
	    }
	    fclose($myfile);
	}
	
	public function mail($subject) {
		//$subject = 'Imp news (' . $count . ') ';;
        if($news){
            $subject .= ' ' . $news->getExternalId() . ' / ' . $news->getUid();
        }
		if($this->errors){
			$subject .= 'ERRORS (' . $this->errors . ') ';
		}
		if($this->warnings){
			$subject .= 'WARNINGS (' . $this->warnings . ') ';
		}
		$variables['settings'] = $this->settings;
		$variables['log'] = $this->log;
		//$recipient = array($this->settings['import']['admin']['email'] => $this->settings['import']['admin']['name']);
		$recipient = array("rolf@nakielski.de" => "rolf");
		//$templatePath =  $this->docRoot . '/'  . 'typo3conf/ext/jobs/Resources/Private/Templates/Email/';
		//$templatePath =  $this->settings['import']['docRoot'] . 'typo3conf/ext/bra_person_ukd/Resources/Private/Templates/Email/';
		$templatePath =  '/var/www/vhosts/typo3-7/httpdocs/typo3conf/ext/bra_person_ukd/Resources/Private/Templates/Email/';
		//                  /var/www/vhosts/typo3-7/httpdocs/typo3conf/ext/bra_person_ukd/Resources/Private/Templates/Email/
		//$sender = array('test@daev-online-unit.de' => $this->settings['import']['admin']['name']);
        $sender = $recipient;
		//$subject = $subject;
		$templateName = 'Import';
		$res = $this->emailService->sendTemplateEmail($recipient, $sender, $subject, $templatePath, $templateName, $variables);
		return $res;
	}
    
}
