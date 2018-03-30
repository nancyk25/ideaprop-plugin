<?php
/**
 * @package ideaPropPlugin
 */

namespace src\Base;

class Activate
{
    public static function activate(){
        flush_rewrite_rules();
    }

}