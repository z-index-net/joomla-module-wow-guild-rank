<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @var        stdClass $module
 * @var        Joomla\Registry\Registry $params
 */

defined('_JEXEC') or die;

if (!class_exists('WoWModuleAbstract')) {
    echo JText::_('JERROR_ERROR') . ': WoW-Plugin not found?!';
    return;
}

JLoader::register('ModWowGuildRankHelper', __DIR__ . '/helper.php');

$rank = ModWowGuildRankHelper::getData($params);

if (!$params->get('ajax') && !is_object($rank)) {
    echo $rank;
    return;
}

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));