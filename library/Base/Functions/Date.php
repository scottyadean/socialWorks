<?php
class Base_Functions_Date {
    
    public $date;
    public $day;
    public $month;
    public $year;
    public $firstDay;
    public $monthName;
    
    
    
    
    
    protected function _current() {
        
        $date = new stdClass;
        $date->date  = $this->date;
        $date->day   = $this->day;
        $date->month = $this->month;
        $date->year  = $this->year;
        $date->monthName = $this->monthName;
        
        return $date;
        
    }
    
    
    public function currentMonth() {
        
        
        //This gets today's date
        $this->date = time();
        
        //This puts the day, month, and year in seperate variables
        $this->day = date('d', $this->date);
        $this->month = date('m', $this->date);
        $this->year = date('Y', $this->date);
        
        //Here we generate the first day of the month
        $this->firstDay = mktime(0,0,0, $this->month, 1, $this->year) ;
        
        //This gets us the month name
        $this->monthName = date('F', $this->firstDay) ; 
        
        //return info about the data
        return $this->_current();
        
        
        
    }
    
 
    public function currentDaysInAMonth() {

        //Here we find out what day of the week the first day of the month falls on 
        $day_of_week = date('D', $this->firstDay) ; 

        //Once we know what day of the week it falls on, we know how many blank days occure before it.
        //If the first day of the week is a Sunday then it would be zero
        switch($day_of_week){         
            case "Sun": $blank = 0; break; 
            case "Mon": $blank = 1; break;
            case "Tue": $blank = 2; break;
            case "Wed": $blank = 3; break;
            case "Thu": $blank = 4; break; 
            case "Fri": $blank = 5; break;
            case "Sat": $blank = 6; break; 
        }

         //We then determine how many days are in the current month
        $days_in_month = cal_days_in_month(0, $this->month, $this->year) ; 

        return $days_in_month;
    } 
 
    
    
        
        
public static function weekFromMonday($month, $day, $year) {
    
    // Get the weekday of the given date
    $wkday = date('l', mktime('0','0','0', $month, $day, $year));

    switch($wkday) {
        case 'Monday': $numDaysToMon = 0; break;
        case 'Tuesday': $numDaysToMon = 1; break;
        case 'Wednesday': $numDaysToMon = 2; break;
        case 'Thursday': $numDaysToMon = 3; break;
        case 'Friday': $numDaysToMon = 4; break;
        case 'Saturday': $numDaysToMon = 5; break;
        case 'Sunday': $numDaysToMon = 6; break;
    }

    // Timestamp of the monday for that week
    $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

    $seconds_in_a_day = 86400;

    // Get date for 7 days from Monday (inclusive)
    for($i=0; $i<7; $i++) {
        $dates[$i] = date('m-d-Y',$monday+($seconds_in_a_day*$i));
    }

    return $dates;
    }
    
    public static function rangeMonth($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
        $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
        return $res;
    }

    public static  function rangeWeek($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
        $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
        return $res;
    }
    
    public static function MonthNames($month = false) {
        
        $months = array("January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December");
        
        if( $month !== false && isset($months[$month]) ){
            return $months[$month];
        }
        
        return $months;
    }
    
    
    

}