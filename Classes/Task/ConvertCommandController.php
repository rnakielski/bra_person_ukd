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

        $columns = $this->personRepository->getColumns(0, 10000);
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
        $toConvert = array();
        $template = '';
        $storagePid = 0;
        $ces = $this->personRepository->getCEs($column['pid'], $column['colPos']);
        //$this->log('Content Elemente gefunden: ' . count($ces));
        foreach($ces as $ce){
            if($ce['CType'] == 'bra_contact_teaser' AND $ce['header'] != ''){
                //$this->log('Mask Teaser mit Namen gefunden: ' . $ce['header']);
                if($storagePid == 0){
                    $storagePid = $this->getStoragePidForPage($column['pid']);
                    if($storagePid == 0){
                        $this->log(' Kein Sysfolder gefunden für ' . $column['pid']);
                        return;
                    }
                    else{
                        $this->log('Sysfolder ' . $storagePid . ' gefunden für Page ' . $column['pid']);
                    }
                }
                $match = $this->tryToMatch(trim($ce['header']), $storagePid);
                if($match){
                    //$this->log('hurra gefunden!');
                    //get flexform from ce
                    $flexFormArray = $this->flexFormService->convertFlexFormContentToArray($ce['pi_flexform']);
                    $template = $flexFormArray['viewcontact'];
                    //todo compare properties of ce with other matches - if not the same make new ce and start new toConvert array
                    // template and what else?
                    $toConvert[]['person'] = $match;
                    $toConvert[]['oldCE'] = $ce;
                    //so long process immediately...

                }
            }
            else{
                //todo process toConvert Array
            }
            /*$this->log('Content Element : ' . count($ce));
            foreach($ce as $key => $value){
             $this->log($key . ' : ' . $value);
             }*/

        }
    }


    protected function tryToMatch($fullName, $storagePid){
        $matches = $this->personRepository->getPersonsByFullName($fullName, $storagePid);
        if(count($matches) == 0){
            $this->log('kein Match für: ' . $fullName);
            $this->countNoMatch++;
            //$this->countNoMatches++;
            return null;
        }
        if(count($matches) > 1){
            $this->log(count($matches) . ' Matches für: ' . $fullName);
            $this->countVariousMatches++;
            //$this->countVariousMatches++;
            return null;
        }
        if(count($matches) == 1){
            $this->log('genau ein Match für: ' . $fullName);
            $this->countExactMatch++;
            $this->countExactMatch++;
            //return 1;
            return $this->personRepository->findByUid($matches[0]['uid']);
        }
        return null;
    }

    /**
     * @param int $page
     * @return int
     */
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
