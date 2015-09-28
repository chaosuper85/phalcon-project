<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s') , ' Creating new TemplateProcessor instance...' , EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/Users/chaosuper/migrate_project/phpweb/apps/var/temp/block.docx');

// Will clone everything between ${tag} and ${/tag}, the number of times. By default, 1.
$templateProcessor->cloneBlock('test');

// Everything between ${tag} and ${/tag}, will be deleted/erased.
//$templateProcessor->deleteBlock('DELETEME');

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('/Users/chaosuper/migrate_project/phpweb/apps/var/temp/block1.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
