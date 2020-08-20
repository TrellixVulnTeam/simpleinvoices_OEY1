<?php

namespace Inc\Claz;

use Zend_Currency;
use Zend_Currency_Exception;
use Zend_Date;
use Zend_Date_Exception;
use Zend_Locale;
use Zend_Locale_Exception;
use Zend_Locale_Format;

/**
 * SiLocal class for value formatting.
 */
class SiLocal
{
    private const DATE_FORMAT_PARAMETER = "/(full|long|date_short|short|month|month_short|medium)/";

    /**
     * Format numbers.
     * Note: This is a wrapper for the <b>Zend_Locale_Format::toNumber</b> function.
     * @param string $number Number to be formatted
     * @param string $precision Decimal precision.
     * @param string $locale Locale the number is to be formatted for.
     * @param string $symbol Currency symbol. Defaults to no symbol used.
     * @return string Formatted number.
     */
    public static function number(string $number, string $precision = "", string $locale = "", string $symbol = ""): string
    {
        global $config;

        if (empty($locale)) {
            try {
                $locale = new Zend_Locale($config->local->locale);
            } catch (Zend_Locale_Exception $zle) {
                $str = "SiLocal::numberTrim() - locale[{$config->local->locale}] (default used) error: " . $zle->getMessage();
                error_log($str);
                exit($str);
            }
        }

        if (empty($precision)) {
            $precision = $config->local->precision;
        }

        try {
            $formattedNumber = Zend_Locale_Format::toNumber($number, ['precision' => $precision, 'locale' => $locale]);
        } catch (Zend_Locale_Exception $zle) {
            $str = "SiLocal::number() - locale[{$config->local->locale}] (input number returned) error: " . $zle->getMessage();
            error_log($str);
            exit($str);
        }

        if (!empty($symbol)) {
            $formattedNumber = $symbol . $formattedNumber;
        }

        return empty($formattedNumber) ? '0' : $formattedNumber;
    }

    /**
     * Format number in default form.
     * Note: Default form is without leading & trailing zeros, and locale decimal point (period or comma).
     * @param string $number Numeric value to be formatted.
     * @param string $precision Decimal places for the number. Optional, precision from $config file used if not specified.
     * @param string $locale Locale to use for formatting the number. Optional, locale from $config file used if not specified.
     * @param string $symbol Currency symbol to use. Optional, specify if want included in formatted number.
     * @return string Formatted string.
     */
    public static function numberTrim(string $number, string $precision = "", string $locale = "", string $symbol = ""): string
    {
        global $config;

        if (empty($locale)) {
            try {
                $locale = new Zend_Locale($config->local->locale);
            } catch (Zend_Locale_Exception $zle) {
                $str = "SiLocal::numberTrim() - locale[{$config->local->locale}] (default used) error: " . $zle->getMessage();
                error_log($str);
                exit($str);
            }
        }

        if (empty($precision)) {
            $precision = $config->local->precision;
        }

        $formattedNumber = self::number($number, $precision, $locale, $symbol);

        // Calculate the decimal point right offset.
        $position = ($precision + 1) * -1;

        // Get character in the decimal point position. Check if it is a
        // decimal point. If so, remove it if it is followed only by zeros.
        // Note this differs in that it won't trim trailing zeroes if there
        // are non-zero characters following the decimal point. (ex: 1.10 won't trim).
        $chr = substr($formattedNumber, $position, 1);
        if ($chr == '.' || $chr == ',') {
            $formattedNumber = rtrim(trim($formattedNumber, '0'), '.,');
        }

        return empty($formattedNumber) ? '0' : $formattedNumber;
    }

    /**
     * Format number in default currency form.
     * @param string $number Numeric value to be formatted.
     * @param string $locale Locale to use for formatting the number.
     *          Optional, locale from $config file used if not specified.
     * @return string Formatted string.
     */
    public static function currency(string $number, string $locale = ""): string
    {
        global $config;

        if (empty($locale)) {
            try {
                $locale = new Zend_Locale($config->local->locale);
            } catch (Zend_Locale_Exception $zle) {
                $str = "SiLocal::currency() - locale[{$config->local->locale}] (default used) error: " . $zle->getMessage();
                error_log($str);
                exit($str);
            }
        }

        try {
            $formattedCurrency = new Zend_Currency($locale);
            $currency = $formattedCurrency->toCurrency($number);
        } catch (Zend_Currency_Exception $zce) {
            $str = "SiLocal::currency() - locale[{$config->local->locale}] " .
                "number[$number] - 0 returned. Error: " . $zce->getMessage();
            error_log($str);
            exit($str);
        }
        return $currency;
    }

    /**
     * Convert a localized number back to the format stored in the database.
     * @param string $number
     * @return string Number formatted for database storage (ex: 12.345,67 converts to 12345.67)
     */
    public static function dbStd(string $number): string
    {
        global $config;

        try {
            $locale = new Zend_Locale($config->local->locale);
            $newNumber = empty($number) ? "0" : $number;
            $newNumber = Zend_Locale_Format::getNumber($newNumber, ['locale' => $locale, 'precision' => $config->local->precision]);
        } catch (Zend_Locale_Exception $zle) {
            $str = "SiLocal::dbStd() - locale[{$config->local->locale}] (input number returned) error: " . $zle->getMessage();
            error_log($str);
            exit($str);
        }

        return $newNumber;
    }

    /**
     * Format a date value.
     * Note: This is a wrapper for the <b>Zend_Date</b> function.
     * @param string $date Date value to be formatted.
     * @param string $date_format (Optional) Date format. Values are:
     *        <ul>
     *          <li><b>day</b>        : Zend_Date constant DAY              - Ex: 06</li>
     *          <li><b>day_short</b>  : Zend_Date constant DAY_SHORT        - Ex: 6</li>
     *          <li><b>date_short</b> : Zend_Date constant DATE_SHORT       - Ex: 5/6/2017</li>
     *          <li><b>full</b>       : Zend_Date constant DATE_FULL        - Ex: Friday, May 6, 2017</li>
     *          <li><b>long</b>       : Zend_Date constant DATE_LONG        - Ex: May 6, 2017</li>
     *          <li><b>medium</b>     : Zend_Date constant DATE_MEDIUM      - Ex: 05/06/2017</li>
     *          <li><b>month</b>      : Zend_Date constant MONTH_NAME       - Ex: 05</li>
     *          <li><b>month_short</b>: Zend_Date constant MONTH_NAME_SHORT - Ex: 5</li>
     *          <li><b>short</b>      : Zend_Date constant DATE_SHORT       - Ex: 5/6/2017</li>
     *        </ul>
     *        Defaults to <b>medium</b>.
     * @param string $locale (Optional) <i>locale</i> setting to format the date for.
     *        Defaults to <b>local.locale</b> setting in the <i>config.php</i> setting.
     *        Ex: en_US.
     * @return string <b>$date</b> formatted per option settings.
     */
    public static function date(string $date, string $date_format = "medium", string $locale = ""): string
    {
        global $config;

        if (!preg_match(self::DATE_FORMAT_PARAMETER, $date_format)) {
            $str = "SiLocal::date() - Invalid date format, {$date_format}, specified.";
            error_log($str);
            exit($str);
        }

        try {
            if (!empty($locale)) {
                $locale = new Zend_Locale($config->local->locale);
            }

            $tempDate = new Zend_Date($date, 'yyyy-MM-dd');
        } catch (Zend_Locale_Exception $zle) {
            $str = "SiLocal::date() - Zend_Locale_Exception thrown by Zend_Locale. Error: " . $zle->getMessage();
            error_log($str);
            exit($str);
        } catch (Zend_Date_Exception $zde) {
            $str = "SiLocal::date() - Zend_Date_Exception thrown by Zend_Date. Error: " . $zde->getMessage();
            error_log($str);
            exit($str);
        }

        // @formatter:off
        switch ($date_format) {
            case "full"        : return $tempDate->get(Zend_Date::DATE_FULL       , $locale);
            case "long"        : return $tempDate->get(Zend_Date::DATE_LONG       , $locale);
            case "date_short"  : // Same as "short".
            case "short"       : return $tempDate->get(Zend_Date::DATE_SHORT      , $locale);
            case "month"       : return $tempDate->get(Zend_Date::MONTH_NAME      , $locale);
            case "month_short" : return $tempDate->get(Zend_Date::MONTH_NAME_SHORT, $locale);
            case "medium"      : // Same as default for any undefined parameter setting.
            default            :
                break;
        }
        // @formatter:on
        return $tempDate->get(Zend_Date::DATE_MEDIUM, $locale);
    }

    /**
     * Truncate a given string
     *
     * @param string $string - the string to truncate
     * @param int $max - the max length in characters to truncate the string to
     * @param string $rep - characters to be added at end of truncated string
     * @return string truncated to specified length.
     */
    public static function truncateStr(string $string, int $max = 20, string $rep = ''): string
    {
        if (strlen($string) <= $max + strlen($rep)) {
            return $string;
        }
        $leave = $max - strlen($rep);
        return substr_replace($string, $rep, $leave);
    }

    /**
     * Ensure that there is a time value in the datetime object.
     *
     * @param string $in_date Datetime string in the format, "YYYY/MM/DD HH:MM:SS".
     *        Note: If time part is "00:00:00" it will be set to the current time.
     * @return string Datetime string with time set.
     */
    public static function sqlDateWithTime(string $in_date): string
    {
        $parts = explode(' ', $in_date);
        $date = isset($parts[0]) ? $parts[0] : "";
        $time = isset($parts[1]) ? $parts[1] : "00:00:00";
        if (!$time || $time == '00:00:00') {
            $time = date('H:i:s');
        }

        return "{$date} {$time}";
    }

}
