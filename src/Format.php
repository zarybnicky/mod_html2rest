<?php
namespace Unifor\Import\Html2Rest;

class Format
{
    const ITALIC = 'emphasis';
    const BOLD = 'strong';
    const CODE = 'literal';
    const UNDERLINE = 'under';
    const STRIKETHROUGH = 'strike';
    const SUPERSCRIPT = 'superscript';
    const SUBSCRIPT = 'subscript';

    protected static $shorthand = array(
        self::ITALIC => 'i',
        self::BOLD => 'b',
        self::CODE => 'c',
        self::UNDERLINE => 'u',
        self::STRIKETHROUGH => 's',
        self::SUPERSCRIPT => 'sup',
        self::SUBSCRIPT => 'sub'
    );

    public static function decorate($text, $styles)
    {
        $styles = array_unique($styles);
        if (!$styles)
        {
            return array($text, '');
        }

        $prefix = '';
        while ($text && strpos(" \n\r\t", substr($text, 0, 1)) !== false)
        {
            $prefix .= substr($text, 0, 1);
            $text = substr($text, 1);
        }

        $postfix = '';
        while ($text && strpos(" \n\r\t", substr($text, -1)) !== false)
        {
            $postfix = substr($text, -1) . $postfix;
            $text = substr($text, 0, -1);
        }

        if (!$text)
        {
            return array($prefix . $postfix, '');
        }

        if (!$prefix) {
            $prefix .= '\ ';
        }
        if (!$postfix) {
            $postfix = '\ ' . $postfix;
        }

        if (count($styles) == 1) {
            switch (reset($styles))
            {
            case self::ITALIC:
                $text = "*$text*";
                break;
            case self::BOLD:
                $text = "**$text**";
                break;
            case self::CODE:
                $text = "``$text``";
                break;
            case self::SUPERSCRIPT:
                $text = ":superscript:`$text`";
                break;
            case self::SUBSCRIPT:
                $text = ":subscript:`$text`";
                break;
            case self::UNDERLINE:
                $text = ":under:`$text`";
                break;
            case self::STRIKETHROUGH:
                $text = ":strike:`$text`";
                break;
            }
            return array($prefix . $text . $postfix, '');
        }

        sort($styles);

        $self_shorthand = self::$shorthand;
        $shorthands = array_map(
            function ($x) use ($self_shorthand) {
                return $self_shorthand[$x];
            },
            $styles
        );

        $name = implode('-', $shorthands);
        $classes = implode(' ', $styles);

        return array(
            $prefix . ":$name:`$text`" . $postfix,
            ".. role:: $name\n   :class: $classes\n"
        );
    }
}
