<?php

/**
 * 
 */

class Date
{

    private $timezone;
    private $date;
    private $month = array(1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık');

    public function __construct()
    {
        // Zaman ayarlaması yapılır
        $this->timezone = new DateTimeZone('Europe/Istanbul');
    }

    /**
     * Timestamp yada normal tarih tanımlar
     * Unix timestamp için @ kullanılır
     * 
     * @param type $datetime
     * @return \Date
     */
    public function set($datetime = 'now')
    {
        $this->date = new DateTime($datetime, $this->timezone);
        return $this;
    }

    public function diff($datetime)
    {
        $diff = new DateTime($datetime, $this->timezone);
        $this->date = $this->date->diff($diff);

        return $this;
    }

    /**
     * GG-AA-YYYY şeklinde tarihi formatlar
     * 
     * @param type $separator
     * @return type
     */
    public function date($separator = '-')
    {
        $format = 'd' . $separator . 'm' . $separator . 'Y';
        return $this->date->format($format);
    }

    /**
     * GG Ay YYYY şeklinde tarihi formatlar
     * 
     * @param type $separator
     * @return type
     */
    public function dateWithName($separator = ' ')
    {
        return $this->date->format('d') . $separator . $this->month[$this->date->format('n')] . $separator . $this->date->format('Y');
    }

    /**
     * SS:DD[:SS] şekinde saati formatlar
     * 
     * @param type $second
     * @param type $separator
     * @return type
     */
    public function time($second = FALSE, $separator = ':')
    {
        $format = 'H' . $separator . 'i';

        if ($second === TRUE) {
            $format = $format . $separator . 's';
        }
        return $this->date->format($format);
    }

    /**
     * GG-AA-YYYY SS:DD[:SS] şeklinde tarihi ve saati formatlar
     * 
     * @param type $second
     * @param type $datesap
     * @param type $timesap
     * @return type
     */
    public function datetime($second = FALSE, $datesap = '-', $timesap = ':')
    {
        $format = 'd' . $datesap . 'm' . $datesap . 'Y H' . $timesap . 'i';

        if ($second === TRUE) {
            $format = $format . $timesap . 's';
        }
        return $this->date->format($format);
    }

    /**
     * GG Ay YYYY SS:DD[:SS] şeklinde tarihi ve saati foratlar
     * 
     * @param type $second
     * @param type $datesap
     * @param type $timesap
     * @return type
     */
    public function datetimeWithName($second = FALSE, $datesap = ' ', $timesap = ':')
    {
        $date = $this->date->format('d') . $datesap . $this->month[$this->date->format('n')] . $datesap . $this->date->format('Y');

        $format = 'H' . $timesap . 'i';

        if ($second === TRUE) {
            $format = $format . $timesap . 's';
        }
        $time = $this->date->format($format);

        return $date . ' ' . $time;
    }

    /**
     * 
     * @return type
     */
    public function mysqlDate()
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * 
     * @return type
     */
    public function mysqlDatetime()
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    /**
     * Geçerli zamanın Timestamp halini döndürür
     * 
     * @return type
     */
    public function timestamp()
    {
        return $this->date->getTimestamp();
    }

    /**
     * Zamanı istenlen biçimde formatlar
     * 
     * @param type $format
     * @return type
     */
    public function format($format)
    {
        return $this->date->format($format);
    }

}
