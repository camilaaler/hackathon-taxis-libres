<?php 
class WCRA_Time
{
	public function __construct()
	{
		
	}
	
	
	function timeZoneConvert($fromTime, $fromTimezone, $toTimezone, $format = 'Y-m-d H:i:s') 
	{
		 // create timeZone object , with fromtimeZone
		$from = new DateTimeZone($fromTimezone);
		 // create timeZone object , with totimeZone
		$to = new DateTimeZone($toTimezone);
		// read give time into ,fromtimeZone
		$orgTime = new DateTime($fromTime, $from);
		// fromte input date to ISO 8601 date (added in PHP 5). the create new date time object
		$toTime = new DateTime($orgTime->format("c"));
		// set target time zone to $toTme ojbect.
		$toTime->setTimezone($to);
		// return reuslt.
		return $toTime->format($format);
	}
	function get_current_time_zone()
	{
		$timezone_string = get_option( 'timezone_string' );

        if ( ! empty( $timezone_string ) ) 
            return $timezone_string;
        

        $offset  = get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = ( $offset - floor( $offset ) ) * 60;
        $offset  = sprintf( '%+03d:%02d', $hours, $minutes );

        return $offset;
	}
}
?>