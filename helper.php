<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class ModWowGuildRankHelper extends WoWModuleAbstract
{
    protected function getInternalData()
    {
        try
        {
            $result = WoW::getInstance()->getAdapter($this->params->module->get('source'))->getData();
        } catch (Exception $e)
        {
            return $e->getMessage();
        }

        $retval = new stdClass;

        switch ($this->params->module->get('source', 'wowprogress'))
        {
            case 'guildox':
                foreach ($result->body->rank as $option)
                {
                    if (strtolower($option->name) == 'raid')
                    {
                        $retval->rank = $option->rank->realm;
                        $retval->region = $option->rank->region;
                        break;
                    }
                }

                $retval->world = $result->body->guild->world_rank;
                $retval->url = 'http://www.guildox.com/wow/guild/' . $this->params->global->get('region') . '/' . $this->params->global->get('realm') . '/' . $this->params->global->get('guild');
                break;

            case 'wowprogress':
                $retval->realm = $result->body->realm_rank;
                $retval->world = $result->body->world_rank;
                $retval->region = $result->body->area_rank;
                $retval->url = 'http://www.wowprogress.com/guild/' . $this->params->global->get('region') . '/' . $this->params->global->get('realm') . '/' . $this->params->global->get('guild');
                break;
        }

        $retval->display = $retval->{$this->params->module->get('display', 'realm')};

        switch ($retval->display)
        {
            case ($retval->display <= 9):
                $retval->size = 'size9';
                break;

            case ($retval->display <= 99):
                $retval->size = 'size99';
                break;

            case ($retval->display <= 999):
                $retval->size = 'size999';
                break;
        }

        return $retval;
    }
}