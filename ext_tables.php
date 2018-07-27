<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Cobra3.BraPersonUkd',
            'Pi1',
            'Person Teaser'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Personendatenbank UKD');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_brapersonukd_domain_model_person', 'EXT:bra_person_ukd/Resources/Private/Language/locallang_csh_tx_brapersonukd_domain_model_person.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_brapersonukd_domain_model_person');

    },
    $_EXTKEY
);
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('bra_person_ukd'));
$pluginName = strtolower('Pi1');
$pluginSignature = $extensionName.'_'.$pluginName;
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature, 'FILE:EXT:' . 'bra_person_ukd' . '/Configuration/FlexForms/Pi1.xml');

