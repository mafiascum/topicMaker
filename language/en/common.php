<?php
/**
*
* @package phpBB Extension - MafiaScum ISOs and Activity Overview
* @copyright (c) 2017 mafiascum.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'UNLOCK' => 'Unlock',
	'LINK_TO_PARENT_TOPIC' => 'Back to Topic',
));
