<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as t;

class Header extends Block
{
    protected $level;

    public function __construct($node, $parent, $level)
    {
        parent::__construct($node, $parent);

        $this->level = $level;
    }

    public function render()
    {
        $headers = '=-~*+#';
        $char = $headers[$this->level - 1];

        $insideTable = false;
        $parent = $this;
        while ($parent = $parent->getParent()) {
            if ($parent instanceof Table) {
                $insideTable = true;
                break;
            }
        }

        if ($insideTable) {
            $process = t\compose(
                'trim',
                t\lines(),
                t\unwords(),
                t\surround("\n:header:`", "`\n")
            );
        } else {
            $process = t\compose(
                'trim',
                t\lines(),
                function ($xs) use ($char) {
                    $xs[] = str_repeat($char, mb_strlen(end($xs), 'UTF-8'));
                    return $xs;
                },
                t\unlines(),
                t\surround("\n", "\n")
            );
        }

        return $process(parent::render());
    }
}
