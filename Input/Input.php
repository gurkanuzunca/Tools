<?php

class Input
{
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


    public static function ip($default = '0.0.0.0')
    {
        return static::server('REMOTE_ADDR', $default);
    }


    public static function protocol()
    {
        if (static::server('HTTPS') == 'on' || static::server('HTTPS') == 1 ||  static::server('SERVER_PORT') == 443) {
            return 'https';
        }

        return 'http';
    }


    public static function isAjax()
    {
        return (static::server('HTTP_X_REQUESTED_WITH') !== null) and strtolower(static::server('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';
    }


    public static function method($default = 'GET')
    {
        return static::server('REQUEST_METHOD', $default);
    }


    public static function userAgent($default = '')
    {
        return static::server('HTTP_USER_AGENT', $default);
    }


    public static function get($index = null, $default = null)
    {
        return static::getArray($_GET, $index, $default);
    }


    public static function post($index = null, $default = null)
    {
        return static::getArray($_POST, $index, $default);
    }



    public static function file($index = null, $default = null)
    {
        return static::getArray($_FILES, $index, $default);
    }


    public static function cookie($index = null, $default = null)
    {
        return static::getArray($_COOKIE, $index, $default);
    }


    public static function server($index = null, $default = null)
    {
        return static::getArray($_COOKIE, strtoupper($index), $default);
    }

}
