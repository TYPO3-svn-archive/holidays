<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_holidays_holidays'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidays',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'adminOnly' => 1,
		'rootLevel' => 1,
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_holidays_holidays.gif',
	),
);

$TCA['tx_holidays_holidaynames'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:holidays/locallang_db.xml:tx_holidays_holidaynames',		
		'label'     => 'local_name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'adminOnly' => 1,
		'rootLevel' => 1,
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_holidays_holidaynames.gif',
	),
);

?>
