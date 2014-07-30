<?php

class Input
{

    /**
     * Dizi içerisinden istenilen elemanı döndürür
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    private static function getArray($array, $key, $default = null)
    {
        if (! is_array($array)) {
            return null;
        }

        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $part) {
            if (isset($array[$part])) {
                $array = $array[$part];
            } else {
                return $default;
            }
        }

        return $array;
    }


    /**
     * İstemci ip adresini döndürür
     * @param string $default
     * @return mixed
     */
    public static function ip($default = '0.0.0.0')
    {
        return static::server('REMOTE_ADDR', $default);
    }


    /**
     * Protokolü döndürür
     * @return string
     */
    public static function protocol()
    {
        if (static::server('HTTPS') == 'on' || static::server('HTTPS') == 1 ||  static::server('SERVER_PORT') == 443) {
            return 'https';
        }

        return 'http';
    }


    /**
     * Ajax isteği kontrolü yapar
     * @return bool
     */
    public static function isAjax()
    {
        return (static::server('HTTP_X_REQUESTED_WITH') !== null) and strtolower(static::server('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';
    }


    /**
     * İstek metodunu döndürür
     * @param string $default
     * @return mixed
     */
    public static function method($default = 'GET')
    {
        return static::server('REQUEST_METHOD', $default);
    }


    /**
     * Tarayıcı bilgisi döndürür
     * @param string $default
     * @return mixed
     */
    public static function userAgent($default = '')
    {
        return static::server('HTTP_USER_AGENT', $default);
    }


    /**
     * Get değerini döndürür
     * @param null $index
     * @param null $default
     * @return mixed
     */
    public static function get($index = null, $default = null)
    {
        return static::getArray($_GET, $index, $default);
    }


    /**
     * Post değerini döndürür
     * @param null $index
     * @param null $default
     * @return mixed
     */
    public static function post($index = null, $default = null)
    {
        return static::getArray($_POST, $index, $default);
    }


    /**
     * Dosya değerini döndürür
     * @param null $index
     * @param null $default
     * @return mixed
     */
    public static function file($index = null, $default = null)
    {
        return static::getArray($_FILES, $index, $default);
    }


    /**
     * Çerez değerinin döndürür
     * @param null $index
     * @param null $default
     * @return mixed
     */
    public static function cookie($index = null, $default = null)
    {
        return static::getArray($_COOKIE, $index, $default);
    }


    /**
     * Sunucu bilgisini döndürür
     * @param null $index
     * @param null $default
     * @return mixed
     */
    public static function server($index = null, $default = null)
    {
        return static::getArray($_COOKIE, strtoupper($index), $default);
    }

}
