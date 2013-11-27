<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
 
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';

$rank = mod_wow_guild_rank::_($params);

if(!is_object($rank)) {
	echo $rank;
	return;
}

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));