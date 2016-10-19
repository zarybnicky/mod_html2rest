<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as t;

class A extends Inline
{
    public function render()
    {
        $href = $this->node->getAttribute('href');

        if ($href == '#content' || $href == '#list')
        {
            return '';
        }

        if (stripos($href, '#') === 0) {
            $href = substr($href, 1);
        } elseif (stripos($href, 'http') !== 0) {
            $href = GLOBALCOREURL . $href;
        }

        if (t\all(t\isA('Unifor\Import\Html2Rest\Element\Inline'), $this->children))
        {
            $content = trim(parent::render());
            if (!$content)
            {
                return '';
            }
            return ("`$content <$href>`_");
        }
        else
        {
            $process = t\compose(
                t\mapRenderUnlines(),
                t\prepend("`Odkaz <$href>`_\n")
            );
            return $process($this->children);
        }
    }
}
