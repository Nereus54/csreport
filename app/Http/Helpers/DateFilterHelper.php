<?php

/**
 * DateFilterHelper
 * 
 * @author Marcel Bobak <marcel.bobak@gmail.com>
 */

namespace App\Http\Helpers;

class DateFilterHelper
{
	const DEFAULT_DATE_FORMAT = 'Y-m-d';
	
	private $_date_format;
	
	public function setDateFormat($format)
	{
		$this->_date_format = $format;
	}
	
	public function dateFilterHelper(...$params)
	{
		list($fromDate, $toDate, $fd_def, $td_def) = $params;
		
		if ( null === $this->_date_format )
		{
			$this->_date_format = self::DEFAULT_DATE_FORMAT;
		}
		
		$fd_def = date($this->_date_format, strtotime($fd_def));
		$td_def = date($this->_date_format, strtotime($td_def));
		
		if ( empty($fromDate) )
		{
			$fromDate = $fd_def;
		}
		else
		{
			$fromDate = date($this->_date_format, strtotime($fromDate));
		}
		
		if ( empty($toDate) )
		{
			$toDate = $td_def;
		}
		else
		{
			$toDate = date($this->_date_format, strtotime($toDate));
		}
		
		$dStart = new \DateTime($fromDate);
		$dEnd  = new \DateTime($toDate);
		$dDiff = $dStart->diff($dEnd);
		$interval = $dDiff->format('%R%a'); // i.e +1
		$unsignedInterval = $dDiff->days;
		
		// if both date point to the same day
		if ( 0 == $unsignedInterval )
		{
			$fromDate = date($this->_date_format, strtotime('-1 day', strtotime($fromDate)));
		}
		
		// if toDate is lesser than fromDate
		if ( 0 === strpos($interval, '-') )
		{
			$toDate = date($this->_date_format, strtotime('+1 day', strtotime($fromDate)));
		}
		
		return [
			$fromDate,
			$toDate,
		];
	}
}