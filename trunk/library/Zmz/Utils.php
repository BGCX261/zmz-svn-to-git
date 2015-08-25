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
class Zmz_Utils
{

    public static function tokenizer($string, $pattern = null)
    {
        if ($pattern == null) {
            $pattern = " ";
        }
        $toks = array();
        $string = (string) $string;
        $string = trim($string);
        if (strlen($string) == 0)
            return array();
        $tok = strtok($string, $pattern);
        while ($tok !== false) {
            $toks[] = $tok;
            $tok = strtok($pattern);
        }
        return $toks;
    }

    public static function redirect($url)
    {
        if (!headers_sent()) {
            header('Location:' . (string) $url);
            exit();
        } else {
            throw new Zmz_Utils_Exception('Header already sent');
        }
    }

    public function getReferer($default = null)
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            if ($default) {
                return (string) $default;
            } else {
                return '';
            }
        }
    }

    public static function padInteger($int, $count = 1)
    {
        $int = (int) $int;
        if ($int < 10) {
            $zeros = '';
            for ($i = 0; $i < $count; $i++) {
                $zeros .= '0';
            }
            return (string) $zeros . $int;
        } else {
            return (string) $int;
        }
    }

    /**
     * Remove "script" tag at beginning and at the end of string
     *
     * @param string $js
     * @return string cleared string
     */
    public static function clearScript($js)
    {
        if (!is_string($js)) {
            throw new Zmz_Utils_Exception('"$js" must be a string');
        }
        $js = explode("\n", $js);
        foreach ($js as $k => $v) {
            $row = trim($v);
            if ($row == '' || substr($row, 0, 7) == '<script'
                    || substr($row, 0, 9) == '</script>') {
                unset($js[$k]);
            }
        }
        $result = implode("\n", $js);

        return $result;
    }

    public static function removeAccents($string)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
        );

        return strtr($string, $table);
    }

    public static function getIp()
    {
        $rawIp = @getenv("REMOTE_ADDR");

        return $rawIp;
    }

    public static function getFilteredIp()
    {
        return self::formatIp(self::getIp());
    }

    public static function compareIp($ip1, $ip2)
    {
        $ip1 = self::formatIp($ip1);
        $ip2 = self::formatIp($ip2);

        return strcmp($ip1, $ip2);
    }

    public static function encodeIp($ip)
    {
        $tmp = explode('.', $ip);
        $ipEncoded = sprintf("%02x%02x%02x%02x", $tmp[0], $tmp[1], $tmp[2], $tmp[3]);

        return $ipEncoded;
    }

    public static function decodeIp($ip)
    {
        $hex = explode('.', chunk_split($ip, 2, '.'));
        $ipDecoded = hexdec($hex[0]) . '.' . hexdec($hex[1]) . '.' .
                hexdec($hex[2]) . '.' . hexdec($hex[3]);

        return $ipDecoded;
    }

    public static function formatIp($ip)
    {
        $ipFormatted = self::decodeIp(self::encodeIp($ip));

        return $ipFormatted;
    }

    public static function getHostname()
    {
        $hostname = Zmz_Host::getHostname();

        return $hostname;
    }

    public static function getUrl()
    {
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $url = self::getHostname() . $url;

        return $url;
    }

    /**
     *
     * @param string $id
     * @return string
     */
    public static function stripFormArrayNotation($id)
    {
        if ('[]' == substr($id, -2)) {
            $id = substr($id, 0, strlen($id) - 2);
        }
        $id = str_replace('][', '-', $id);
        $id = str_replace(array(']', '['), '-', $id);
        $id = trim($id, '-');

        return $id;
    }

}