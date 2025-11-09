<?php
require_once '../qtools/qfil.php';
require_once '../qtools/qcli.php';
require_once '../qtools/qstr.php';

$lines = qfil::getLinesFromFile("../../dataraw/flashcards.txt");
qcli::message("Read " . count($lines) . " lines from flashcards.txt");

$flashcards = [];
for ($i = 0; $i < count($lines); $i += 4) {
	$suuid = qstr::generateSuuid();
	$category = trim($lines[$i] ?? "");
	$front = trim($lines[$i + 1] ?? "");
	$back = trim($lines[$i + 2] ?? "");

	if ($category && $front && $back) {
		$flashcards[] = [
			'suuid' => $suuid,
			'category' => $category,
			'front' => $front,
			'back' => $back
		];
	} else {
		qcli::message(
			"Skipping incomplete flashcard at lines " . ($i + 1) . "-" . ($i + 3),
			"warning"
		);
	}
}

qcli::message("Generated " . count($flashcards) . " flashcards");

// Save flashcards to JSON file
$jsonData = json_encode($flashcards, JSON_PRETTY_PRINT);
if (file_put_contents("../../data/flashcards.json", $jsonData) === false) {
	qcli::message("Error writing flashcards to JSON file", "error");
} else {
	qcli::message("Wrote " . count($flashcards) . " flashcards to flashcards.json");
}