<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person',
        'label' => 'lastname',
        //'label_alt' => 'firstname, job',
        //'label_alt_force' => 1,
        'label_userFunc' => 'Cobra3\BraPersonUkd\Utility\BackendUtility->getPersonLabel',
        'default_sortby' => ' lastname asc, firstname asc ',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'old_id,position,salutation,sex,title,firstname,lastname,job,old_image_id,job_title,country_job,medical_association,link_medical_association,address,image,room_nr,building_level,building_nr,street,street_nr,post_office_p_ost,country_code,country,zip_code,city,phone1,phone2,fax,mobile_phone,email,homepage,misc,person_id_fk,unit_id_fk,department_id_fk,homepage_title,homepage_page_title,filelink_title,homepage_page,filelink,email2',
        'iconfile' => 'EXT:bra_person_ukd/Resources/Public/Icons/tx_brapersonukd_domain_model_person.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, old_id, position, salutation, sex, title, firstname, lastname, job, old_image_id, job_title, country_job, medical_association, link_medical_association, address, image, room_nr, building_level, building_nr, street, street_nr, post_office_p_ost, country_code, country, zip_code, city, phone1, phone2, fax, mobile_phone, email, homepage, misc, person_id_fk, unit_id_fk, department_id_fk, homepage_title, homepage_page_title, filelink_title, homepage_page, filelink, email2',
    ],
    'types' => [
        '1' => ['showitem' => 'l10n_parent, l10n_diffsource, hidden, old_id, position, salutation, sex, title, firstname, lastname, job, old_image_id, job_title, country_job, medical_association, link_medical_association, address, image, 
                                --div--;Adresse, room_nr, building_level, building_nr, street, street_nr, post_office_p_ost, country_code, country, zip_code, city, phone1, phone2, fax, mobile_phone, email, homepage, misc, person_id_fk, unit_id_fk, department_id_fk, homepage_title, homepage_page_title, filelink_title, homepage_page, filelink, email2, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_brapersonukd_domain_model_person',
                'foreign_table_where' => 'AND tx_brapersonukd_domain_model_person.pid=###CURRENT_PID### AND tx_brapersonukd_domain_model_person.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
        ],
        'old_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.old_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'position' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.position',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'salutation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.salutation',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'sex' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.sex',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'firstname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.firstname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'lastname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.lastname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'job' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.job',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'old_image_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.old_image_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'job_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.job_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'country_job' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.country_job',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'medical_association' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.medical_association',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'link_medical_association' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.link_medical_association',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'address' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.address',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
        
        
        'image' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                array('maxitems' => 1,
                    'foreign_match_fields' => array(
                        'fieldname' => 'image',
                        'tablenames' => 'tx_brapersonukd_domain_model_person',
                        'table_local' => 'sys_file',
                    ),
                ),
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                ),
        ),
        
        
	    'XXXimage - done by Extension Builder' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.image',
	        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			    'image',
			    [
			        'appearance' => [
			            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
			        ],
			        'foreign_types' => [
			            '0' => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ]
			        ],
			        'maxitems' => 1
			    ],
			    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
	    ],
	    'room_nr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.room_nr',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'building_level' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.building_level',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'building_nr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.building_nr',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'street' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.street',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'street_nr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.street_nr',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'post_office_p_ost' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.post_office_p_ost',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'country_code' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.country_code',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'country' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.country',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'zip_code' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.zip_code',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'city' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.city',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'phone1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.phone1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'phone2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.phone2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'fax' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.fax',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'mobile_phone' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.mobile_phone',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'email' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.email',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'homepage' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.homepage',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'misc' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.misc',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'person_id_fk' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.person_id_fk',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'unit_id_fk' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.unit_id_fk',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'department_id_fk' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.department_id_fk',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'homepage_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.homepage_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'homepage_page_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.homepage_page_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'filelink_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.filelink_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'homepage_page' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.homepage_page',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'filelink' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.filelink',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'email2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:bra_person_ukd/Resources/Private/Language/locallang_db.xlf:tx_brapersonukd_domain_model_person.email2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
    ],
];
