<?php

// for each file that has a namespace declaration
$dir = __DIR__;
$filesWithNamespaceDeclaration = explode("\n", `grep -r '^namespace' $dir --include=*.php --exclude=vendor -l`);

// echo implode("\n", $filesWithNamespaceDeclaration);

foreach ($filesWithNamespaceDeclaration as $file) {
	moveNamespaceToTop($file);
}

function moveNamespaceToTop($file) {
	if (!is_file($file)) {
		return;
	}
	
	$contents = file_get_contents($file);
	$lines = explode("\n", $contents);
	
	$nsDeclarationPosition = findPositionOfNamespaceDeclaration($lines);
	
	if ($nsDeclarationPosition == -1) {
		return;
	}
	
	$declaration = $lines[$nsDeclarationPosition];
	
	// echo "$declaration\n";
	
	unset($lines[$nsDeclarationPosition]);
	
	array_splice($lines, 1, 0, $declaration);
	
	$newContents = implode("\n", $lines) . "\n";
	
	file_put_contents($file, $newContents);
}

function findPositionOfNamespaceDeclaration($lines) {
	$position = -1;
	foreach ($lines as $pos => $lineContent) {
		if (isNamespaceDeclaration($lineContent)) {
			$position = $pos;
		}
	}
	
	return $position;
}

function isNamespaceDeclaration($lineContent) {
	return strpos($lineContent, "namespace ") === 0;
}
// get the contents of that file and split into lines
// find the line with the namespace declaration and remove it
// insert it into the second position
// write all the lines back to the file
