<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as t;

class Note extends Block
{
    protected $icon;
    protected $content;

    public function setIcon($elem)
    {
        $this->icon = $elem;
    }

    public function setContent($elem)
    {
        $this->content = $elem;
    }

    public function render()
    {
        if (!$this->content) {
            return '';
        }

        $processIcon = t\compose(
            t\render(),
            t\trim("\n\t\r`_ |>"),
            t\lines(),
            t\unwords(),
            t\splitBy('| <')
        );
        $iconLink = $processIcon($this->icon);
        $icon = array_shift($iconLink);
        $link = $iconLink ? trim(array_shift($iconLink)) : '';

        $processContents = t\compose(
            t\render(),
            'trim',
            t\when(
                t\id(),
                t\perLine(t\prepend('   ')),
                t\surround("\n", "\n")
            )
        );

        $out = "\n\n.. dnote::\n   :icon: $icon\n";
        if ($link) {
            $out .= "   :link: $link\n";
        }
        $out .= $processContents($this->content);
        return $out;
    }

    public function isEmpty()
    {
        return false;
    }
}
