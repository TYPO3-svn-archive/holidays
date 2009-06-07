<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_holidays_holidays'] = array (
	'ctrl' => $TCA['tx_holidays_holidays']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,type,day,country_exclude,country_only'
	),
	'feInterface' => $TCA['tx_holidays_holidays']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.name',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'max' => '50',	
				'eval' => 'required',
			)
		),
		'type' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.type',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.type.I.0', '0'),
					array('LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.type.I.1', '1'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'day' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.day',		
			'config' => array (
				'type' => 'input',	
				'size' => '5',	
				'max' => '3',	
				'range' => array ('lower'=>-366,'upper'=>366),	
				'eval' => 'required,int',
			)
		),
		'country_exclude' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.country_exclude',		
			'config' => array (
				'type' => 'select',    
				'foreign_table' => 'static_countries',    
				'foreign_table_where' => 'ORDER BY static_countries.cn_short_en',    
				'size' => 10,    
				'minitems' => 0,
				'maxitems' => 100,
			)
		),
		'country_only' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays.country_only',		
			'config' => array (
				'type' => 'select',    
				'foreign_table' => 'static_countries',    
				'foreign_table_where' => 'ORDER BY static_countries.cn_short_en',    
				'size' => 10,    
				'minitems' => 0,
				'maxitems' => 100,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, name, type, day, country_exclude, country_only')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_holidays_holidaynames'] = array (
	'ctrl' => $TCA['tx_holidays_holidaynames']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'holiday_uid,language_uid,local_name'
	),
	'feInterface' => $TCA['tx_holidays_holidaynames']['feInterface'],
	'columns' => array (
		'holiday_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidaynames.holiday_uid',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_holidays_holidays',	
				'foreign_table_where' => 'ORDER BY tx_holidays_holidays.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'language_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidaynames.language_uid',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'static_languages',	
				'foreign_table_where' => 'ORDER BY static_languages.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'local_name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidaynames.local_name',		
			'config' => array (
				'type' => 'input',	
				'size' => '48',	
				'max' => '50',	
				'eval' => 'required',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'holiday_uid;;;;1-1-1, language_uid, local_name')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>