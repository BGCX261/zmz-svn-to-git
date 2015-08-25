<?php

/**
 * Zmz
 *
 * LICENSE
 *
 * This source file is subject to the GNU GPLv3 license that is bundled
 * with this package in the file COPYNG.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @copyright  Copyright (c) 2010-2011 Massimo Zappino (http://www.zappino.it)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html     GNU GPLv3 License
 */
class Zmz_Host
{

    protected static $host;
    protected static $port;
    protected static $scheme;
    protected static $domainLevels;

    /**
     * Extract informations
     *
     * @return boolean
     */
    protected static function extract()
    {
        if (self::$host) {
            return true;
        }

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] === true)) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
        self::$scheme = $scheme;

        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            self::$host = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'])) {
            $name = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];

            self::$host = $name;
            self::$port = $port;
        }

        $levels = explode('.', self::$host);
        $count = count($levels);
        self::$domainLevels = array();
        foreach ($levels as $l) {
            self::$domainLevels[$count] = $l;
            $count--;
        }
        return true;
    }

    /**
     *
     * @return string
     */
    public static function getHostname()
    {
        self::extract();

        return self::$host;
    }

    /**
     * Get the current port
     * 
     * @return int 
     */
    public static function getPort()
    {
        self::extract();

        return self::$port;
    }

    /**
     * Get the current scheme: http or https
     *
     * @return string
     */
    public static function getScheme()
    {
        self::extract();

        return self::$scheme;
    }

    public static function getServerUrl()
    {
        $serverUrl = self::buildUrl(self::getHostname(), self::getScheme(), self::getPort());

        return $serverUrl;
    }

    public static function buildUrl($hostname, $scheme = null, $port = null)
    {
        if (is_null($scheme)) {
            $scheme = 'http';
        }

        $url = $scheme . '://' . $hostname;
        if ($port) {
            $url .= ':' . $url;
        }

        return $url;
    }

    /**
     * Get an array
     * 
     * @return array
     */
    public static function getDomainLevels()
    {
        self::extract();

        return self::$domainLevels;
    }

    /**
     *
     * @param int $level
     * @return mixed subdomain at given  
     */
    public static function getSubdomain($level)
    {
        self::extract();

        $domainLevels = self::$domainLevels;
        $level = (int) $level;
        if (isset($domainLevels[$level])) {
            return $domainLevels[$level];
        } else {
            return null;
        }
    }

    /**
     * Get the current user agent
     * 
     * @return string
     */
    public static function getUserAgent()
    {
        $userAgent = @$_SERVER['HTTP_USER_AGENT'];

        return $userAgent;
    }

}

