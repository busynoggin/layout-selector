<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['pages']['columns']['tx_fed_page_controller_action']['config']['renderType'] = 'fluxLayoutSelector';
$GLOBALS['TCA']['pages']['columns']['tx_fed_page_controller_action_sub']['config']['renderType'] = 'fluxLayoutSelector';