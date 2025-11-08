<?php
require_once 'persons_model_json.php';

$result = persons_model::delete_person_by_id("VIaPfa");
echo $result ? "Person deleted successfully" : "Person not found or error occurred";