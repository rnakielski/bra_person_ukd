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

    protected $count = 0;
    protected $countExactMatch = 0;
    protected $countNoMatch = 0;
    protected $countVariousMatches = 0;
    protected $countNewPlugins = 0;
    protected $countDeletedCe = 0;
    protected $toConvert = array();
    protected $template = '';

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

        $columns = $this->personRepository->getColumns(0, $this->settings['convert']['limit']);
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
                    $this->template = $template;
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

    /**
     * do the conversion for all persons in toConvert
     * called whenever there is no more person for a single new plugin
     * toConvert will be initialized
     */
    protected function convertCEs(){
        if(count($this->toConvert) > 0){
            $this->log('neues Plugin schreiben, '. count($this->toConvert) . ' alte CEs als deleted markieren');
            $this->insertNewPlugin();
            foreach($this->toConvert as $person){
                $this->deleteOldCe($person['oldCE']['uid']);
            }
            $this->toConvert = array();
        }
    }

    /**
     * @param int $uid
     */
    protected function deleteOldCe($uid){
        $this->personRepository->setCeDeleted($uid);
        $this->countDeletedCe++;
    }

    /**
     *
     */
    protected function insertNewPlugin(){
        $persons = array();
        $maskIds = array();
        foreach($this->toConvert as $person){
            $persons[] = $person['person']['uid'];
            $maskIds[] = $person['oldCE']['uid'];
        }
        $ffXmlSkeleton =
              '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>' .
              '<T3FlexForms>' .
              '   <data>' .
              '      <sheet index="sDEF">' .
              '        <language index="lDEF">' .
              '           <field index="settings.persons">' .
              '              <value index="vDEF">%s</value>' .
              '           </field>' .
              '           <field index="settings.template">' .
              '              <value index="vDEF">%s</value>' .
              '           </field>' .
              '           <field index="settings.maskIds">' .
              '              <value index="vDEF">%s</value>' .
              '           </field>' .
              '        </language>' .
              '      </sheet>' .
              '   </data>' .
              '</T3FlexForms>';
        $ffPersons = implode(',', $persons);
        $ffMaskIds = implode(',', $maskIds);
        $ffTemplate = $this->template;
        $piFlexform = sprintf( $ffXmlSkeleton, $ffPersons, $ffTemplate, $ffMaskIds);

        $values = array(
            'pid'       => $this->toConvert[0]['oldCE']['pid'],
            'tstamp'    => time(),
            'crdate'    => time(),
            'hidden'    => $this->toConvert[0]['oldCE']['hidden'],
            'sorting'   => $this->toConvert[0]['oldCE']['sorting'],
            'CType'     => 'list',
            'colPos'    => $this->toConvert[0]['oldCE']['colPos'],
            'list_type' => 'brapersonukd_pi1',
            'pi_flexform' => $piFlexform,
            'cruser_id' => $this->settings['convert']['adminUid'],
            'imagecols' => 2,
            'fe_group'  => '',
            'sectionindex' => 1,
            'table_delimiter' => 124
        );
        $this->personRepository->insertPlugin($values);
        $this->countNewPlugins++;
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
            $this->countExactMatch++;
            return $matches[0];
        }
    }

    protected function getStoragePidForPage($page){
        $storagePid = 0;
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
        $this->log('neue Plugins geschrieben: ' . $this->countNewPlugins);
        $this->log('alte CEs gelöscht: ' . $this->countDeletedCe);
        //$myfile = fopen($this->docRoot . "convertlog.txt", "a") or die("Unable to open file!");
        $myfile = fopen($this->docRoot . $this->settings['convert']['logfileName'], "a") or die("Unable to open file!");
        foreach($this->log as $l){
            fwrite($myfile, $l . "\n");
        }
        fclose($myfile);
    }


//delete from `tt_content` where CType = 'list' and uid > 3


}
