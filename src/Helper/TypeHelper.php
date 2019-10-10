<?php


namespace MisakaCloud\GoVC\Helper;


/**
 * Class TypeHelper
 * @package MisakaCloud\GoVc\Helper
 */
class TypeHelper
{
    /**
     * TypeHelper constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $value
     * @return string
     */
    public static function boolToString($value)
    {
        return $value ? 'true' : 'false';
    }
}
