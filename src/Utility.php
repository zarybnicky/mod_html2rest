<?php
namespace Unifor\Import\Html2Rest\Utility;

function id()
{
    return __NAMESPACE__ . '\\identity';
}

function identity($x = null)
{
    return $x;
}

function compose()
{
    $fns = func_get_args();
    if (!$fns) {
        return __NAMESPACE__ . '\\identity';
    } elseif (!isset($fns[1])) {
        return $fns[0];
    }

    $fn = array_shift($fns);
    $total = count($fns);
    return function ($a = null, $b = null) use ($fn, $fns, $total) {
        $passed = func_num_args();
        if ($passed > 0) {
            $value = $fn($a);
        } else {
            $value = $fn();
        }
        $i = 0;
        while ($i < $total) {
            $value = $fns[$i++]($value);
        }
        return $value;
    };
}

function map($f)
{
    if (func_num_args() > 1) {
        $f = call_user_func_array(__NAMESPACE__ . '\\compose', func_get_args());
    }
    return function ($xs) use ($f) {
        return array_map($f, $xs);
    };
}

function dump()
{
    return function ($x) {
        var_dump($x);
        return $x;
    };
}

function max($default)
{
    return function ($xs) use ($default) {
        if (!$xs) {
            return $default;
        }
        return \max($xs);
    };
}

function min($default)
{
    return function ($xs) use ($default) {
        if (!$xs) {
            return $default;
        }
        return min($xs);
    };
}

function render()
{
    return function ($x) {
        return $x->render();
    };
}

function mapRender()
{
    return function ($xs) {
        $out = array();
        foreach ($xs as $key => $x) {
            $out[$key] = $x->render();
        }
        return $out;
    };
}

function mapRenderConcat()
{
    return function ($xs) {
        $out = array();
        foreach ($xs as $key => $x) {
            $out[$key] = $x->render();
        }
        return implode('', $out);
    };
}

function mapRenderUnlines()
{
    return function ($xs) {
        $out = array();
        foreach ($xs as $key => $x) {
            $out[$key] = $x->render();
        }
        return implode("\n", $out);
    };
}

function lines()
{
    return function ($xs) {
        return explode("\n", $xs);
    };
}

function unlines()
{
    return function ($xs) {
        return implode("\n", $xs);
    };
}

function words()
{
    return function ($xs) {
        return explode(' ', $xs);
    };
}

function unwords()
{
    return function ($xs) {
        return implode(' ', $xs);
    };
}

function perLine()
{
    return compose(
        lines(),
        map(call_user_func_array(__NAMESPACE__ . '\\compose', func_get_args())),
        unlines()
    );
}

function trim($chars = null)
{
    if ($chars !== null) {
        return function ($x) use ($chars) {
            return \trim($x, $chars);
        };
    } else {
        return function ($x) {
            return \trim($x);
        };
    }
}

function prepend($prefix)
{
    return function ($x) use ($prefix) {
        if (is_string($prefix) && is_string($x)) {
            return $prefix . $x;
        } elseif (is_array($prefix) && is_array($x)) {
            return array_merge($prefix, $x);
        }
        var_dump(array($prefix, $x));
        fail();
    };
}

function append($postfix)
{
    return function ($x) use ($postfix) {
        if (is_string($postfix) && is_string($x)) {
            return $x . $postfix;
        } elseif (is_array($postfix) && is_array($x)) {
            return array_merge($x, $postfix);
        }
        fail();
    };
}

function surround($prefix, $postfix)
{
    return function ($x) use ($prefix, $postfix) {
        if (is_string($prefix) && is_string($postfix) && is_string($x)) {
            return $prefix . $x . $postfix;
        } elseif (is_array($prefix) && is_array($postfix) && is_array($x)) {
            return array_merge($prefix, $x, $postfix);
        }
        fail();
    };
}

function transpose()
{
    return function ($xs) {
        //special case: empty matrix
        if (!$xs) {
            return array();
        }

        //special case: row matrix
        if (count($xs) == 1) {
            return array_chunk($xs[0], 1);
        }

        return call_user_func_array(
            'array_map',
            array_merge(array(null), $xs)
        );
    };
}

function concatArray()
{
    return function ($xs) {
        if (!$xs) {
            return array();
        }
        return call_user_func_array('array_merge', $xs);
    };
}

function concatString()
{
    return function ($xs) {
        if (!$xs) {
            return '';
        }
        return implode('', $xs);
    };
}

function intersperse($separator)
{
    return function ($xs) use ($separator) {
        if (!$xs) {
            return array();
        }
        $content = array(array_shift($xs));
        foreach ($xs as $x) {
            $content[] = $separator;
            $content[] = $x;
        }
        return $content;
    };
}

function intercalate($separator)
{
    if (is_string($separator)) {
        return compose(
            intersperse($separator),
            concatString()
        );
    } else {
        return compose(
            intersperse($separator),
            concatArray()
        );
    }
}

function hang($start)
{
    $ind = map(prepend(str_repeat(' ', mb_strlen($start))));
    return function ($xs) use ($start, $ind) {
        $out = $start . array_shift($xs);
        return array_merge(array($out), $ind($xs));
    };
}

function isA($class)
{
    return function ($x) use ($class) {
        return $x instanceof $class;
    };
}

function any($f, $xs)
{
    return array_reduce(
        $xs,
        function ($c, $x) use ($f) {
            return $c | $f($x);
        },
        false
    );
}

function all($f, $xs)
{
    return array_reduce(
        $xs,
        function ($c, $x) use ($f) {
            return $c & $f($x);
        },
        true
    );
}

function filter($cond = null)
{
    return function ($xs) use ($cond) {
        if ($cond !== null) {
            return array_filter($xs, $cond);
        } else {
            return array_filter($xs);
        }
    };
}

function splitBy($separator)
{
    return function ($xs) use ($separator) {
        if (is_string($xs)) {
            return explode($separator, $xs);
        }
        fail();
    };
}

function splitByClass($separator)
{
    return function ($xs) use ($separator) {
        $out = array();
        $tmp = array();

        foreach ($xs as $item) {
            if (basename(get_class($item)) == $separator) {
                $out[] = $tmp;
                $tmp = array();
                continue;
            }
            $tmp[] = $item;
        }
        if ($tmp) {
            $out[] = $tmp;
        }
        return $out;
    };
}

function when($cond, $f)
{
    if (func_num_args() > 2) {
        $fs = func_get_args();
        array_shift($fs);
        $f = call_user_func_array(
            __NAMESPACE__ . '\compose',
            $fs
        );
    }
    return function ($xs) use ($cond, $f) {
        return $cond($xs) ? $f($xs) : $xs;
    };
}

function cond($cond, $true, $false)
{
    return function ($xs) use ($cond, $true, $false) {
        return $cond($xs) ? $true($xs) : $false($xs);
    };
}

function zip()
{
    return function ($xs) {
        $out = array();
        while (reset($xs)) {
            $elem = array();
            foreach ($xs as &$x) {
                if (!$x) {
                    break;
                }
                $elem[] = array_pop($x);
            }
            $out[] = $elem;
        }
        return $out;
    };
}

function sort($f = null)
{
    if ($f === null) {
        return function ($xs) {
            sort($xs);
            return $xs;
        };
    } else {
        return function ($xs) use ($f) {
            usort($xs, $f);
            return $xs;
        };
    }
}
