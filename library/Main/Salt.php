<?php
    
    class Main_Salt
    {
    
    public static $resetPwrdHash = "__*11%3#passwordReset)|(*__";

        
    public static function getRandomSha1Hash($length = 20)
    {
	    $code = sha1(uniqid(rand(), true));
	    return  (int)$length > 0 ? substr($code, 0, (int)$length) : $code;
    }

    public static function getSha1Hash($str, $hash)
    {
       return sha1($str.$hash);
    }
    


    public static function getResetPasswordHash($str, $hash)
    {
       return sha1($str.self::$resetPwrdHash.$hash);
    }





}
