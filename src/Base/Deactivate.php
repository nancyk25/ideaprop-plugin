<?php
/**
 * @package ideaPropPlugin
 */

namespace src\Base;

class Deactivate
{
    public static function deactivate(){
        flush_rewrite_rules();
    }

}