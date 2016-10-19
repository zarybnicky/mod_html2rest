<?php
use Unifor\Import\Html2Rest\Utility as t;

/**
 * PHP Version 5
 *
 * Copyright (c)2007-2015, OLC systems <olc@olc.cz>.
 * All rights reserved.
 *
 * @package   mod_scorm
 * @author    Jakub Zárybnický <jakub.zarybnicky@olc.cz>
 * @copyright 2015 OLC systems <olc@olc.cz>
 * @version   25. 2. 2015
 */
class RstPackager
{
    protected $exportRoot = ABSOLUTEPATHTOPROJECT . APPLICATIONPATH . 'resources/disciplines_rst/';
    protected $template = array(
        'original' => array(
            '<?php echo TEMPLATE ?>',
            GLOBALCOREURL . APPLICATIONPATH . 'template/' . TEMPLATENAME . '/',
                            APPLICATIONPATH . 'template/' . TEMPLATENAME . '/',
                                              'template/' . TEMPLATENAME . '/'
        ),
        'temp' => '{{TEMPLATE}}',
        'relative' => APPLICATIONPATH . 'template/' . TEMPLATENAME . '/',
        'absolute' => ABSOLUTEPATHTOPROJECT . APPLICATIONPATH . 'template/' . TEMPLATENAME . '/'
    );
    protected $resources = array(
        'original' => array(
            '<?php echo RESOURCES ?>',
            GLOBALCOREURL . APPLICATIONPATH . 'resources/',
                            APPLICATIONPATH . 'resources/',
                                              'resources/'
        ),
        'temp' => '{{RESOURCES}}',
        'relative' => APPLICATIONPATH . 'resources/',
        'absolute' => ABSOLUTEPATHTOPROJECT . APPLICATIONPATH . 'resources/'
    );

    public function __construct()
    {
        if (!file_exists($this->exportRoot)) {
            mkdir($this->exportRoot, 0777, true);
        }
    }

    /**
     * Loads a discipline from the DB, converts reST and saves to a local ZIP.
     *
     * @param int $disciplineId Discipline to convert
     *
     * @return string Location of the final ZIP archive.
     */
    public function process($disciplineId)
    {
        $archivePath = "{$this->exportRoot}discipline_$disciplineId.zip";

        $archive = new ZipArchive();
        $archive->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $resources = array();
        $chapters = $this->getDisciplineChapters($disciplineId);
        foreach ($chapters as $chapter) {
            $html = $this->originalToTemp(
                $this->withHtmlProlog($chapter->nazev, $chapter->text)
            );

            $archive->addFromString(
                "chapter_{$chapter->id_kapitoly}.html",
                $this->transform(
                    $this->getDummyPatterns(),
                    $this->getRelativePatterns(),
                    $html
                )
            );

            $resources += $this->extractFilesFromHtml($html);
        }

        foreach ($resources as $realPath => $internalPath) {
            if (!file_exists($realPath)) {
                print "File not found:\n - $realPath\n - $internalPath\n\n";
                continue;
            }
            $archive->addFile($realPath, $internalPath);
        }

        //There is a possibility of an empty ZIP archive - the archive will not
        //be created at all in such a case.
        $success = $archive->close();
        if (!$success || (empty($chapters) && empty($resources))) {
            return false;
        }

        return $archivePath;
    }

    /**
     * Wraps the input HTML in a minimal full page.
     *
     * @param string $name    String to use as a document title
     * @param string $content Body of the document
     *
     * @return string
     */
    protected function withHtmlProlog($name, $content)
    {
        return <<<EOS
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>$name</title>
  </head>
  <body>
$content
  </body>
</html>
EOS;
    }

    /**
     * Attempts to extract all local resources that can be packed into the
     * SCORM archive.
     *
     * @param string $html Source HTML
     *
     * @return array Array with local paths as keys & relative paths as values.
     */
    protected function extractFilesFromHtml($html)
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadHTML($html);

        $resources = array();

        $xpath = new DOMXpath($document);
        foreach ($xpath->query('//img') as $item) {
            $resources[$item->getAttribute('src')] = true;
        }
        foreach ($xpath->query('//audio') as $item) {
            $resources[$item->getAttribute('src')] = true;
        }
        foreach ($xpath->query('//a[class~="uploaded"]') as $item) {
            $resources[$item->getAttribute('href')] = true;
        }

        $resources = array_keys($resources);
        $thisVar = $this;
        $process = t\compose(
            t\map(
                function ($item) use ($thisVar) {
                    $absolute = $thisVar->transform(
                        $thisVar->getDummyPatterns(),
                        $thisVar->getAbsolutePatterns(),
                        $item
                    );
                    $relative = $thisVar->transform(
                        $thisVar->getDummyPatterns(),
                        $thisVar->getRelativePatterns(),
                        $item
                    );

                    if ($absolute === $relative) {
                        return array();
                    } else {
                        return array($absolute => $relative);
                    }
                }
            ),
            t\concatArray(),
            t\filter()
        );
        return $process($resources);
    }

    /**
     * Returns a set of patterns for self::transform(), describing the resource
     * location format in the input file.
     *
     * @return array
     */
    public function getOriginalPatterns()
    {
        return array(
            $this->template['original'],
            $this->resources['original']
        );
    }

    /**
     * Returns a set of patterns for self::transform(), describing intermediate
     * patterns used during resource extraction.
     *
     * @return array
     */
    public function getDummyPatterns()
    {
        return array(
            $this->template['dummy'],
            $this->resources['dummy']
        );
    }

    /**
     * Returns a set of patterns for self::transform(), describing relative
     * paths to be used in the final HTML file.
     *
     * @return array
     */
    public function getRelativePatterns()
    {
        return array(
            $this->template['relative'],
            $this->resources['relative']
        );
    }

    /**
     * Returns a set of patterns for self::transform(), describing absolute
     * paths on local filesystem
     *
     * @return array
     */
    public function getAbsolutePatterns()
    {
        return array(
            $this->template['absolute'],
            $this->resources['absolute']
        );
    }

    /**
     * Performs a str_replace after expanding the input patterns.
     *
     * @param array  $from 1D or 2D array
     * @param array  $to   1D or 2D array
     * @param string $str  String to be transformed
     *
     * @return string
     */
    public function transform($from, $to, $str)
    {
        $patterns = $this->preprocessPatterns($from, $to);
        list($from, $to) = $patterns;
        return str_replace($from, $to, $str);
    }

    /**
     * Expands two arrays of strings/arrays into str_replace compatible pairs
     *
     * Given array('from') and array(array('to-1', 'to-2')),
     * the patterns will be matched together and expanded into
     * array('from', 'from') and array('to-1', 'to-2').
     *
     * @param array $from 1D or 2D array
     * @param array $to   1D or 2D array
     *
     * @return array(array(string), array(string))
     */
    public function preprocessPatterns($from, $to)
    {
        $process = t\compose(
            t\transpose(),
            t\map(
                function ($x) {
                    list($from, $to) = $x;

                    if (is_string($from) && is_string($to)) {
                        return array(array($from), array($to));
                    }
                    if (is_array($from) && is_string($to)) {
                        $to = array_pad(array(), count($from), $to);
                    } elseif (is_string($from) && is_array($to)) {
                        $from = array_pad(array(), count($to), $from);
                    }

                    return array($from, $to);
                },
                t\zip()
            ),
            t\concatArray(),
            t\sort(
                function ($a, $b) {
                    $a = mb_strlen($a[0]);
                    $b = mb_strlen($b[0]);
                    return ($a == $b) ? 0 : (($a > $b) ? -1 : 1);
                }
            ),
            t\transpose()
        );
        return $process(array($from, $to));
    }

    /**
     * Fetches all chapters in  a discipline
     *
     * @param int $disciplineId Discipline ID to filter by
     *
     * @return DBResultIterator
     */
    protected function getDisciplineChapters($disciplineId)
    {
        $dbChapters = new dbScoChapterClass();
        $dbChapters->whereIdDiscipline($disciplineId);
        $dbChapters->fillModel();
        return $dbChapters->getIterator();
    }
}
