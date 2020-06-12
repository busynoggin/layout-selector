<?php
defined('TYPO3_MODE') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1592003414] = [
    'nodeName' => 'fluxLayoutSelector',
    'priority' => 40,
    'class' => \BusyNoggin\LayoutSelector\Backend\PageLayoutSelector::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1592003415] = [
    'nodeName' => 'selectBigIcons',
    'priority' => 40,
    'class' => \BusyNoggin\LayoutSelector\Backend\SelectBigIcons::class,
];

$boot = function () {

};

$boot();
unset($boot);
