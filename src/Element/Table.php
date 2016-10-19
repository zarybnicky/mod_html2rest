<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Table extends Block
{
    public function render()
    {
        $unboxContainers = ut\compose(
            ut\map(
                function ($child) {
                    if (
                        $child instanceof Thead ||
                        $child instanceof Tbody ||
                        $child instanceof Tfoot
                    ) {
                        return $child->getChildren();
                    }
                    return array($child);
                }
            ),
            ut\concatArray()
        );
        $this->children = $unboxContainers($this->children);

        if (!$this->children)
        {
            return '';
        }

        $processCells = ut\compose(
            ut\map(
                ut\render(),
                ut\map(
                    ut\map(ut\lines()),
                    ut\concatArray()
                )
            )
        );
        $contents = $processCells($this->children);

        $processWidths = ut\compose(
            ut\transpose(),
            ut\map(
                ut\filter(),
                ut\map(
                    ut\map(
                        function ($x) {
                            return mb_strlen($x, 'UTF-8');
                        }
                    ),
                    ut\max(0)
                ),
                ut\max(0)
            )
        );
        $widths = $processWidths($contents);

        $processHeights = ut\map(
            ut\map(
                function ($x) {
                    return count($x) ?: 1;
                }
            ),
            ut\max(1)
        );
        $heights = $processHeights($contents);

        $rows = array_map(
            function ($row, $height) use ($widths) { //Rows
                $cells = array_map(
                    function ($cell, $width) use ($height) { //Cells
                        return array_pad(
                            array_map(
                                function ($line) use ($width) {
                                    return (
                                        $line .
                                        str_repeat(
                                            ' ',
                                            $width - mb_strlen($line, 'UTF-8')
                                        )
                                    );
                                },
                                $cell ?: array('')
                            ),
                            $height,
                            str_repeat(' ', $width)
                        );
                    },
                    $row,
                    $widths
                );

                $processColumns = ut\compose(
                    ut\transpose(),
                    ut\map(
                        ut\intercalate(' | '),
                        ut\surround('| ', ' |')
                    )
                );
                return $processColumns($cells ?: array(array(), array()));
            },
            $contents,
            $heights
        );

        $processVertSeparator = ut\compose(
            ut\map(
                function ($item) {
                    return str_repeat('-', $item);
                }
            ),
            ut\intercalate('-+-'),
            ut\surround('+-', '-+')
        );
        $vertSeparator = $processVertSeparator($widths);

        $processTable = ut\compose(
            ut\transpose(),
            ut\concatArray(),
            ut\concatArray(),
            ut\prepend(array($vertSeparator)),
            ut\unlines(),
            ut\surround("\n", "\n")
        );
        return $processTable(
            array(
                $rows,
                array_pad(array(), count($rows), array($vertSeparator))
            )
        );
    }
}
