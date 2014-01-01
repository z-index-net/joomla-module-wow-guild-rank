<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JFactory::getDocument()->addStyleSheet(JUri::base(true) . '/modules/' . $module->module . '/tmpl/default.css');
?>
<?php if ($params->get('ajax')) : ?>
    <div class="mod_wow_guild_rank ajax"></div>
<?php else: ?>
    <div class="mod_wow_guild_rank <?php echo $params->get('color', 'gold'); ?>">
        <div class="image"></div>
        <div class="rank <?php echo $rank->size; ?>"><?php echo $rank->display; ?></div>
        <div class="display"><?php echo JText::_('MOD_WOW_GUILD_RANK_DISPLAY_' . strtoupper($params->get('display', 'realm'))); ?></div>
        <?php echo JHtml::_('link', $rank->url, parse_url($rank->url, PHP_URL_HOST), array('target' => '_blank', 'class' => 'source')); ?>
    </div>
<?php endif; ?>