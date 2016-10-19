<?php
namespace Unifor\Import\Html2Rest\Element;

class Ul extends ListBase
{
    public function __construct($node, $parent = null)
    {
        parent::__construct($node, $parent);

        $this->next = 0;
        $this->bullet = '- ';
    }
}
