<?php 
class Base_Functions_Math 
{
    
    public static function plusone($number)
    {
        return ($number + 1);
    } 
    

    /*Return the distance 
     between to lat and long points
     @param $lat1<float>
     @param $lon1<float>
     @param $lat2<float>
     @param $lon2<float>
     @param $unit<string> M, K or N
    */
    public static function distanceBetweenCoords($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) 
                                    + cos(deg2rad($lat1)) 
                                    * cos(deg2rad($lat2)) 
                                    * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

}
