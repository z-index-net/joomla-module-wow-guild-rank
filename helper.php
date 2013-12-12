<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

abstract class mod_wow_guild_rank
{

    public static function _(JRegistry &$params)
    {
        $retval = new stdClass;

        switch ($params->get('source')) {
            case 'guildox':
                $url = 'http://www.guildox.com/go/guildxml.aspx?j=1&n=' . $params->get('guild') . '&r=' . $params->get('realm') . '-' . $params->get('region');
                $data = self::remoteContent($url, $params);

                if (!is_object($data)) {
                    return JText::sprintf('MOD_WOW_GUILD_RANK_DATA_ERROR', $params->get('source'));
                }

                $retval->realm = $data->guildox->guild->RealmRank;
                $retval->world = $data->guildox->guild->WorldRank;
                $retval->url = 'http://www.guildox.com/wow/guild/' . $params->get('region') . '/' . $params->get('realm') . '/' . $params->get('guild');
                break;

            case 'wowprogress':
                $url = 'http://www.wowprogress.com/guild/' . $params->get('region') . '/' . $params->get('realm') . '/' . $params->get('guild') . '/json_rank';
                $data = self::remoteContent($url, $params);

                if (!is_object($data)) {
                    return JText::sprintf('MOD_WOW_GUILD_RANK_DATA_ERROR', $params->get('source'));
                }

                $retval->realm = $data->realm_rank;
                $retval->world = $data->world_rank;
                $retval->url = 'http://www.wowprogress.com/guild/' . $params->get('region') . '/' . $params->get('realm') . '/' . $params->get('guild');
                break;
        }

        $retval->display = $retval->{$params->get('display', 'realm')};

        switch ($retval->display) {
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

    private static function remoteContent($url, JRegistry $params)
    {
        $cache = JFactory::getCache('wow', 'output');
        $cache->setCaching(1);
        $cache->setLifeTime($params->get('cache_time', 60));

        $key = md5($url);

        if (!$result = $cache->get($key)) {
            try {
                $http = new JHttp(new JRegistry, new JHttpTransportCurl(new JRegistry));
                $http->setOption('userAgent', 'Joomla! ' . JVERSION . '; WoW Guild Rank; php/' . phpversion());

                $result = $http->get($url, null, $params->get('timeout', 10));
            } catch (Exception $e) {
                return $e->getMessage();
            }

            $cache->store($result, $key);
        }

        if ($result->code != 200) {
            return __CLASS__ . ' HTTP-Status ' . JHtml::_('link', 'http://wikipedia.org/wiki/List_of_HTTP_status_codes#' . $result->code, $result->code, array(
                'target' => '_blank'
            ));
        }

        return json_decode($result->body);
    }
}