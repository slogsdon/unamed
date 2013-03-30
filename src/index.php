<?php

$start = microtime();
$rustart = getrusage();

require_once 'includes/settings.php';
require_once 'includes/load.php';

function rutime($ru, $rus, $index)
{
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "<!-- This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations -->\n";
echo "<!-- It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls -->\n";
echo "<!-- execution time: ".(microtime() - $start)." -->";
echo "<!--";print_r($ru);echo" -->";
