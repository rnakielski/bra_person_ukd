<?php
namespace Cobra3\BraPersonUkd\Task;
class ConvertCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController{

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
     * count
     * @var integer
     */
    protected $count = 0;

    /**
     *  Convert Command
     *
     * @return void
     */
    public function convertCommand() {
        $this->docRoot = PATH_site . 'fileadmin/'; //
        $this->count = 0;

        $this->log('******************************************************************************************');
        $this->log('Convert Run: ' . date("Y-m-d H:i:s"));
        $this->log('******************************************************************************************');

        // get settings
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $this->settings = $this->tsService->convertTypoScriptArrayToPlainArray($extbaseFrameworkConfiguration['plugin.']['tx_brapersonukd.']['settings.']);
        $configurationArray = array(
            'persistence' => array('storagePid' => $this->settings['convert']['storagePid']),
            'persistence' => array('recursive' => 9)
        );
        $this->configurationManager->setConfiguration($configurationArray);

        $columns = $this->personRepository->getColumns();
        $this->log('Spalten gefunden: ' . count($columns));
        foreach($columns as $column){
            $this->processColumn($column);
            /*foreach($personToMigrate as $key => $value){
                $this->log($key . ' : ' . $value);
            }*/
        }
        $this->writeLog();
    }

    /**
     * @param array $column
     */
    protected function processColumn($column){
        $ces = $this->personRepository->getCEs($column['pid'], $column['colPos']);
        $this->log('Content Elemente gefunden: ' . count($ces));
        foreach($ces as $ce){
            $this->log('Content Element : ' . count($ce));
        }
    }

    


    /**
     * @param string $logString
     * @param int $severity
     */
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

    /**
     *
     */
    public function writeLog(){
        //$myfile = fopen($this->docRoot . "convertlog.txt", "a") or die("Unable to open file!");
        $myfile = fopen($this->docRoot . $this->settings['convert']['logfileName'], "a") or die("Unable to open file!");
        foreach($this->log as $l){
            fwrite($myfile, $l . "\n");
        }
        fclose($myfile);
    }


    //der ganze Kram kommt weg
    /**
     * @param array $personToMigrate
     */
    public function processSinglePerson($personToMigrate) {
        $new = true;
        $newPid = $this->settings['convert']['lumpensammlerStoragePid'];//1; //Lumpensammler-pid
        $newPath = $this->settings['convert']['lumpensammlerFilePath'];//"lumpensammler/"; //Lumpensammler-path

        $this->log('------------------------------------------------------------------------------------------');
        $this->log('verarbeite uid:' . $personToMigrate['uid'] . ' pid:' . $personToMigrate['pid'] . ' ' . $personToMigrate['firstname'] .  ' ' . $personToMigrate['lastname']);

        $person = $this->personRepository->findOneByOldId($personToMigrate['uid']);
        if($person){
            $this->log('Person bereits vorhanden - Update');
            $new = false;
        }
        else{
            $this->log('neue Person - Insert');
            $person = $this->objectManager->get('Cobra3\BraPersonUkd\Domain\Model\Person');
            $new = true;
            //todo check for same name
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

        if($new){
            //$this->log('check for same name');
            $sameNamePersons = $this->personRepository->checkForNameInFolder(trim($personToMigrate['firstname']), trim($personToMigrate['lastname']), $newPid);
            if($sameNamePersons->count() > 0){
                $this->log('Person mit gleichem Namen bereits vorhanden! Es wird NICHT migriert Person mit Job: ' . $sameNamePersons->getFirst()->getJob(), 0);
                $this->personRepository->setConvertSkip($personToMigrate['uid']);
                return;
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
            $this->log('Adresse zur Person gefunden. uid: ' . $address['uid']);
            $this->getAddressData($address, $person);
        }

        // Image
        if($personToMigrate['image']){
            $this->log('versuche Bild zu laden: ' . $personToMigrate['image']);
            $this->getImage($personToMigrate['pid'], $personToMigrate['image'], $newPath, $person, $newPid);
        }

        //persist
        $person->setPid($newPid);
        if($new){
            $this->personRepository->add($person);
            /* nur für test
            $this->log('check for same name');
            $sameNamePersons = $this->personRepository->checkForNameInFolder($person->getFirstname(), $person->getLastname(), $newPid);
            if($sameNamePersons->count() > 0){
                $this->log('Person mit gleichem Namen bereits vorhanden! Wird XX migriert! Job: ' . $sameNamePersons->getFirst()->getJob(), 0);
                $this->personRepository->setConvertSkip($personToMigrate['uid']);
                //return;
            }*/


        }
        else{
            $this->personRepository->update($person);
        }
        $this->personRepository->setConvertDone($personToMigrate['uid']);
        $this->persistenceManager->persistAll();
    }



}
