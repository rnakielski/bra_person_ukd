<?php
namespace Cobra3\BraPersonUkd\Hooks;
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
//use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
//use TYPO3\CMS\Backend\Utility\IconUtility;
//use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * Hook to display verbose information about pi1 plugin in Web>Page module
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class PageLayoutView {
    /**
     * Extension key
     *
     * @var string
     */
    const KEY = 'brapersonukd';
    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang.xlf:';
    /**
     * Table information
     *
     * @var array
     */
    public $tableData = array();
    /**
     * Flexform information
     *
     * @var array
     */
    public $flexformData = array();
    /** @var  \TYPO3\CMS\Core\Database\DatabaseConnection */
    protected $databaseConnection;
    /** @var \GeorgRinger\News\Utility\TemplateLayout $templateLayoutsUtility */
    //protected $templateLayoutsUtility;
    public function __construct() {
        /** @var \TYPO3\CMS\Core\Database\DatabaseConnection databaseConnection
         * only needed for old mode
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];*/
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
    }
    /**
     * Returns information about this extension's pi1 plugin
     *
     * @param array $params Parameters to the hook
     * @return string Information about pi1 plugin
     */
    public function getExtensionSummary(array $params) {
        $result = '<strong>' . $this->getLanguageService()->sL(self::LLPATH . 'tx_bra_person_ukd_domain_model_pi1', TRUE) . '</strong>';
        if ($params['row']['list_type'] == self::KEY . '_pi1') {
            $this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);
            //$result .= 'Template: ' . $this->getFieldFromFlexform('settings.template');
            $personsUidArray = explode(',', $this->getFieldFromFlexform('settings.persons'));
            foreach ($personsUidArray as $personUid){
                /*old mode - select fileds yourself
                 * $personFromDB = $this->databaseConnection->exec_SELECTgetRows(
                    'title, firstname, lastname',
                    'tx_brapersonukd_domain_model_person',
                    'deleted=0 AND uid = ' . $personUid
                );
                $result .= '<br>' . $personFromDB[0]['title'] . ' ' . $personFromDB[0]['firstname'] . ' ' . $personFromDB[0]['lastname'];
                */
                //new mode, get field label as defined in TCA (Data Handler)
                $result .= '<br>' . $this->getRecordData($personUid, 'tx_brapersonukd_domain_model_person');
            }
        }
        return $result;
    }
    /**
     * @param $key
     * @param string $sheet
     * @return null
     */
    public function getFieldFromFlexform($key, $sheet = 'sDEF') {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF'])
                && is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }
        return NULL;
    }
    /**
     * Return language service instance
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    public function getLanguageService() {
        return $GLOBALS['LANG'];
    }

    /**
     * @param int $id
     * @param string $table
     * @return string
     */
    public function getRecordData($id, $table = 'pages')
    {
        $record = BackendUtilityCore::getRecord($table, $id);
        if (is_array($record)) {
            $data = '<span data-toggle="tooltip" data-placement="top" data-title="id=' . $record['uid'] . '">'
                . $this->iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render()
                . '</span> ';
            $content = BackendUtilityCore::wrapClickMenuOnIcon($data, $table, $record['uid'], true, '',
                '+info,edit,history');
            $linkTitle = htmlspecialchars(BackendUtilityCore::getRecordTitle($table, $record));
            if ($table === 'pages') {
                $id = $record['uid'];
                $currentPageId = (int)GeneralUtility::_GET('id');
                $link = htmlspecialchars($this->getEditLink($record, $currentPageId));
                $switchLabel = $this->getLanguageService()->sL(self::LLPATH . 'pagemodule.switchToPage');
                $content .= ' <a href="#" data-toggle="tooltip" data-placement="top" data-title="' . $switchLabel . '" onclick=\'top.jump("' . $link . '", "web_layout", "web", ' . $id . ');return false\'>' . $linkTitle . '</a>';
            } else {
                $content .= $linkTitle;
            }
        } else {
            $text = sprintf($this->getLanguageService()->sL(self::LLPATH . 'pagemodule.recordNotAvailable'),
                $id);
            $content = $this->generateCallout($text);
        }
        return $content;
    }

    /**
     * Render an alert box
     *
     * @param string $text
     * @return string
     */
    protected function generateCallout($text)
    {
        return '<div class="btn btn-warning">
            ' . htmlspecialchars($text) . '
        </div>';
    }
}