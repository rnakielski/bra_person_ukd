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
     * FlexForm Service
     *
     * @var \TYPO3\CMS\Extbase\Service\FlexFormService
     * @inject
     */
    protected $flexFormService;



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
     * count
     * @var integer
     */
    protected $countExactMatch = 0;

    /**
     * count
     * @var integer
     */
    protected $countNoMatch = 0;

    /**
     * count
     * @var integer
     */
    protected $countVariousMatches = 0;

    protected $toConvert = array();

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

        $columns = $this->personRepository->getColumns(0, 50000000);
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
        //$toConvert = array();
        $lastTemplate = '';
        $lastHidden = 0;
        $storagePid = 0;
        $ces = $this->personRepository->getCEs($column['pid'], $column['colPos']);
        //$this->log('----Neue Spalte - Content Elemente gefunden: ' . count($ces));
        foreach($ces as $ce){
            if($ce['CType'] == 'bra_contact_teaser' AND $ce['header'] != ''){
                //$this->log('Mask Teaser mit Namen gefunden: ' . $ce['header']);
                if($storagePid == 0){
                    $this->log('------------------------------------------------------------------------------------------');
                    $this->log('verarbeite Spalte pid/colPos: ' . $column['pid'] . '/' . $column['colPos'] . ' Anz CEs insg.: ' . count($ces));
                    $storagePid = $this->getStoragePidForPage($column['pid']);
                    if($storagePid == 0){
                        $this->log('Kein Sysfolder gefunden für ' . $column['pid']);
                        return;
                    }
                    else{
                        $this->log('Sysfolder ' . $storagePid . ' gefunden für Page ' . $column['pid']);
                    }
                }
                $fullName = trim($ce['header']);
                if(substr($fullName, 0, 5) == 'Frau ' OR substr($fullName, 0, 5) == 'Herr '){
                    $fullName = substr($fullName, 5);
                }
                $match = $this->tryToMatch(trim($fullName), $storagePid, $ce);
                if($match){
                    $this->log('Match gefunden! mask | db ' . trim($ce['header']) . ' | ' . $match['title'] . ' ' . $match['firstname'] . ' ' . $match['lastname']);
                    $flexFormArray = $this->flexFormService->convertFlexFormContentToArray($ce['pi_flexform']);
                    $template = $flexFormArray['viewcontact'];
                    //compare properties of ce with other matches - if not the same make new ce and start new toConvert array
                    // template, hidden and what else?
                    if(count($this->toConvert) > 0){
                        if(($template != $lastTemplate) OR ($ce['hidden'] != $lastHidden)){
                            //$this->log('----Template oder Hiddenwechsel');
                            $this->convertCEs();
                        }
                    }
                    $newMatch = array();
                    $newMatch['person'] = $match;
                    $newMatch['oldCE'] = $ce;
                    $this->toConvert[] = $newMatch;
                    $lastTemplate = $template;
                    $lastHidden = $ce['hidden'];
                }
                else{
                    //$this->log('----MCE ohne Match');
                    $this->convertCEs();
                }
            }
            else{
                //$this->log('----anderes Content Element');
                $this->convertCEs();
            }
        }
        //$this->log('----Spalte - Ende');
        $this->convertCEs();
    }

    protected function convertCEs(){
        if(count($this->toConvert) > 0){
            $this->log('todo neues Plugin schreiben');
            foreach($this->toConvert as $person){
                //todo neues Plugin mit n Personen schreiben
                //$this->log('todo Person eintragen: ' . $person['person']->getLastname());
                //todo alte Plugins auf deleted setzen
                //$this->log('todo MCE löschen: ' . $person['oldCE']['header']);
            }
            $this->toConvert = array();
        }
    }

    protected function tryToMatch($fullName, $storagePid, $ce){
        $matches = $this->personRepository->getPersonsByFullName($fullName, $storagePid);
        if(count($matches) == 0){
            $this->log('kein Match für: ' . $fullName);
            $this->countNoMatch++;
            return null;
        }
        if(count($matches) > 1){
            $this->log(count($matches) . ' Matches für: ' . $fullName);
            $this->countVariousMatches++;
            return null;
        }
        if(count($matches) == 1){
            //$this->log('genau ein Match für: ' . $fullName);
            $this->countExactMatch++;
            //return 1;
            $person =  $this->personRepository->findOneByUid($matches[0]['uid']);
            //$this->log('genau ein Match für: ' . $fullName . ' : ' . $person->getTitle() . ' ' . $person->getFirstname() . ' ' . $person->getLastname());
            //check person by extbase
            /*if(get_class($person) != 'Cobra3\BraPersonUkd\Domain\Model\Person'){
                //das darf gar nicht sein
                $person->getLastname();
            }*/
            return $matches[0];
        }
        //return $matches;
    }

    protected function getStoragePidForPage($page){
        $storagePid = 0; //$this->personRepository->getTeamStorageFolder($page);
        $parentPage = $page;
        while($storagePid == 0 AND $parentPage != 0){
            $storagePid = $this->personRepository->getTeamStorageFolder($parentPage);
            $parentPage = $this->personRepository->getParentPage($parentPage);
        }
        return $storagePid;
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
        $this->log('genau 1 Match: ' . $this->countExactMatch);
        $this->log('mehr als 1 Match: ' . $this->countVariousMatches);
        $this->log('kein Match: ' . $this->countNoMatch);
        //$myfile = fopen($this->docRoot . "convertlog.txt", "a") or die("Unable to open file!");
        $myfile = fopen($this->docRoot . $this->settings['convert']['logfileName'], "a") or die("Unable to open file!");
        foreach($this->log as $l){
            fwrite($myfile, $l . "\n");
        }
        fclose($myfile);
    }





}
