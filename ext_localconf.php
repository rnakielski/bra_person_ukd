<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Cobra3.BraPersonUkd',
            'Pi1',
            [
                'Person' => 'list'
            ],
            // non-cacheable actions
            [
                'Person' => ''
            ]
        );

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					pi1 {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey) . 'Resources/Public/Icons/user_plugin_pi1.svg
						title = LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_bra_person_ukd_domain_model_pi1
						description = LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_bra_person_ukd_domain_model_pi1.description
						tt_content_defValues {
							CType = list
							list_type = brapersonukd_pi1
						}
					}
				}
				show = *
			}
	   }'
	);
    },
    $_EXTKEY
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Cobra3\BraPersonUkd\Task\ImportCommandController';
