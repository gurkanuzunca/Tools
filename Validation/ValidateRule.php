<?php


class ValidateRule
{
    public static function alpha($value)
    {
        return preg_match('/^[\pL\pM]+$/u', $value);
    }


    public static function alphaNumeric($value)
    {
        return preg_match('/^[\pL\pM\pN]+$/u', $value);
    }


    public static function minLength($value, array $parameters)
    {
        if (empty($parameters['value'])) {
            return true;
        }

        if (strlen($value) >= (int) $parameters['value']) {
            return true;
        }

        return false;
    }


    public static function maxLength($value, array $parameters)
    {
        if (empty($parameters['value'])) {
            return true;
        }

        if (strlen($value) <= (int) $parameters['value']) {
            return true;
        }

        return false;
    }


    public static function betweenLength($value, array $parameters)
    {
        if (empty($parameters['value']['min']) || empty($parameters['value']['max'])) {
            return true;
        }

        if (strlen($value) >= (int) $parameters['value']['min'] && strlen($value) <= (int) $parameters['value']['max']) {
            return true;
        }

        return false;
    }


    public static function minNumber($value, array $parameters)
    {
        if (empty($parameters['value'])) {
            return true;
        }

        if ((int) $value >= (int) $parameters['value']) {
            return true;
        }

        return false;
    }


    public static function maxNumber($value, array $parameters)
    {
        if (empty($parameters['value'])) {
            return true;
        }

        if ((int) $value <= (int) $parameters['value']) {
            return true;
        }

        return false;
    }


    public static function betweenNumber($value, array $parameters)
    {
        if (empty($parameters['value']['min']) || empty($parameters['value']['max'])) {
            return true;
        }

        if ((int) $value >= (int) $parameters['value']['min'] && (int) $value <= (int) $parameters['value']['max']) {
            return true;
        }

        return false;
    }


    public static function required($value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif (is_array($value) && count($value) < 1) {
            return false;
        }

        return true;
    }


    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }


    public static function url($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
} 