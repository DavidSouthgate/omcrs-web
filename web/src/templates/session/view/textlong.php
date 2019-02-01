<?php
/**
 * @var $question Question
 * @var $response Response
 * @var $responses Response[]
 * @var $session Session
 */

// If a response has already been made, disable the input
$disabled = $response ? " disabled" : "";

$response = $response?$response->getResponse():"";
?>

<textarea style="width: 100%; resize: vertical;" rows="8" class="answer" id="answer" name="answer"<?=$disabled?>><?=$this->e($response)?></textarea>