<?php

include("ATOMWriter.php");

$xmlWriter = new XMLWriter();
$xmlWriter->openUri('php://output');
$xmlWriter->setIndent(true);

$f = new ATOMWriter($xmlWriter, true);

$f->startFeed('urn:tps:com-idx')
    ->writeStartIndex(1)
    ->writeItemsPerPage(10)
    ->writeTotalResults(100)
    ->writeTitle('Index of /')
    ->writeLink('http://exemple.com/', 'text/xml');

$f->startEntry('urn:tps:com-idx-1')
    ->writeTitle('Data 1')
    ->writeLink('/1.xml', 'text/xml')
    ->writeLink('/1.txt', 'text/plain')
    ->writeContent('Un', 'text', 'fr')
    ->writeCategory('term', '#scheme')
    ->endEntry();
$f->flush();

$f->startEntry('urn:tps:com-idx-2')
    ->writeTitle('Data 2')
    ->writeLink('2.txt', 'text/plain')
    ->writeContent('deux', 'text', 'fr')
    ->endEntry();
$f->flush();

$f->endFeed();
$f->flush();
?>
