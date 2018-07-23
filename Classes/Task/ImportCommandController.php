<?php 
namespace Cobra3\BraPersonUkd\Task;
class ImportCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController{ 

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
	 * new
	 * @var boolean
	 */
	protected $new = true;

	/**
	 *  Import Command
	 *
	 * @return void
	 */
	public function ImportCommand() {
        $this->docRoot = PATH_site . 'fileadmin/'; //

        $this->log('******************************************************************************************');
        $this->log('Import Run: ' . date("Y-m-d H:i:s"));
        $this->log('DocRoot: ' . $this->docRoot);
        $this->log('******************************************************************************************');

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


        $personsToMigrate = $this->personRepository->getPersonsToMigrate($this->settings['import']['limit']);
        $this->log('Personen gefunden: ' . count($personsToMigrate));
        foreach($personsToMigrate as $personToMigrate){
            $this->processSinglePerson($personToMigrate);
            /*foreach($personToMigrate as $key => $value){
                $this->log($key . ' : ' . $value);
            }*/
        }

        $this->writeLog();
 	}

	public function processSinglePerson($personToMigrate) {
        $newPid = $this->settings['import']['lumpensammlerStoragePid'];//1; //Lumpensammler-pid
        $newPath = $this->settings['import']['lumpensammlerFilePath'];//"lumpensammler/"; //Lumpensammler-path

	    $this->log('------------------------------------------------------------------------------------------');
	    $this->log('verarbeite uid: ' . $personToMigrate['uid'] . ' ' . $personToMigrate['firstname'] .  ' ' . $personToMigrate['lastname']);

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

	    //mapping
	    $mappings = $this->personRepository->getMappingForPid($personToMigrate['pid']);
        if(count($mappings) == 0){
            $this->log('kein Mapping zur Pid gefunden', 2);
        }
        else{
            $mapping = $mappings[0];
            if($mapping['pid_new'] == ''){
                $this->log('Pid beim Mapping leer', 2);
            }
            else{
                $newPid = $mapping['pid_new'];
                $this->log('neue Pid: ' . $newPid, 0);
            }

            if($personToMigrate['image']){
                if ($mapping['path_new'] == '') {
                    $this->log('Path beim Mapping leer', 2);
                }
                else {
                    $checkPath = str_replace('%2F', '/', $mapping['path_new']) ;
                    if(!is_dir($this->docRoot . $checkPath)){
                        $this->log('Mapping Path ' . $checkPath .' nicht vorhanden', 2);
                    }
                    else {
                        $newPath = $checkPath;
                        $this->log('neuer Path: ' . $checkPath, 0);
                    }
                }
            }
        }

        //person
	    $this->getPersonData($personToMigrate, $person);

        //adress
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

        // Image
        if($personToMigrate['image']){
            $this->log('versuche Bild zu laden: ' . $personToMigrate['image']);
            $this->getImage($personToMigrate['pid'], $personToMigrate['image'], $newPath, $person, $newPid);
        }

        //persist
        $person->setPid($newPid);
        if($this->new){
            $this->personRepository->add($person);
        }
        else{
            $this->personRepository->update($person);
        }
        $this->personRepository->setImportDone($personToMigrate['uid']);
	}
	
	protected function getImage($pid, $imagePath, $newPath, $person, $newPid){
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
    	    $this->log('Download fertig  Dateigröße:' . $filesize);
            if($filesize < 3){
                $this->log('Datei nicht gefunden!');
            }
            elseif($filesize < 250){
                $this->log('Datei zu klein. Wird nicht verwendet!');
            }
    	    else{
                //$path = $this->getFilePath($pid);
                $path = $newPath;
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
                $falMedia->setPid($newPid);
    	        $person->setImage($falMedia);
    	    }
	    }
	    if(is_file($tempfile )){
	        unlink($tempfile);
	    }
	    
	}
	
	//not used anymore - only for Test
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
        $person->setTstamp($personToMigrate['tstamp']);
        $person->setCrdate($personToMigrate['crdate']);
        $person->setHidden($personToMigrate['hidden']);
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
	    $person->setPhone1($address['phone1']);
	    $person->setPhone2($address['phone2']);
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
        //$myfile = fopen($this->docRoot . "importlog.txt", "a") or die("Unable to open file!");
        $myfile = fopen($this->docRoot . $this->settings['import']['logfileName'], "a") or die("Unable to open file!");
	    foreach($this->log as $l){
	        fwrite($myfile, $l . "\n");
	    }
	    fclose($myfile);
	}

}
