<?php
// $Header: /cvsroot/html2ps/css.border.right.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderRight extends CSSSubFieldProperty
{
    public static function getPropertyCode()
    {
        return CSS_BORDER_RIGHT;
    }

    public static function getPropertyName()
    {
        return 'border-right';
    }

    public static function parse($value)
    {
        if ($value == 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        $border = CSSBorder::parse($value);
        return $border->right;
    }

    public static function default_value()
    {
    }

}
