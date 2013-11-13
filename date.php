<?php

class Date
{
	
	private $CI;
	private $timezone;
	private $date;
	private $month;
	


	public function __construct() 
	{
		$this->CI =& get_instance();
		
		// Zaman ayarlaması yapılır
		$this->timezone = new DateTimeZone('Europe/Istanbul');
		
		// Dil dosyası yüklenir;
		$this->CI->lang->load('date');
		$this->month = $this->CI->lang->line('date_month');
				
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
	
	


	/**
	 * GG-AA-YYYY şeklinde tarihi formatlar
	 * 
	 * @param type $separator
	 * @return type
	 */
	public function date($separator = '-')
	{	
		$format = 'd'. $separator .'m'. $separator .'Y';
		return $this->date->format($format);
	}
	
	
	
	/**
	 * GG Ay YYYY şeklinde tarihi formatlar
	 * 
	 * @param type $separator
	 * @return type
	 */
	public function date_with_name($separator = ' ')
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
		$format = 'H'. $separator .'i';
		
		if ($second === TRUE){
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
		$format = 'd'. $datesap .'m'. $datesap .'Y H'. $timesap .'i';
		
		if ($second === TRUE){
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
	public function datetime_with_name($second = FALSE, $datesap = ' ', $timesap = ':')
	{
		$date = $this->date->format('d') . $datesap . $this->month[$this->date->format('n')] . $datesap . $this->date->format('Y');
		
		$format = 'H'. $timesap .'i';
		
		if ($second === TRUE){
			$format = $format . $timesap . 's';
		}
		$time = $this->date->format($format);
		
		return $date .' '. $time;
	}
	
	
	/**
	 * 
	 * @return type
	 */
	public function mysql_date()
	{
		return $this->date->format('Y-m-d');
	}
	
	
	/**
	 * 
	 * @return type
	 */
	public function mysql_datetime()
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