<?php
require_once '../scripts/qtools/qfil.php';

$lines = qfil::getLinesFromFile("../data/flashcards.txt");
echo json_encode($lines, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);