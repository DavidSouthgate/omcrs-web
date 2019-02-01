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

<input style="width: 100%" class="answer" id="answer" type="text" value="<?=$this->e($response)?>" name="answer"<?=$disabled?>>