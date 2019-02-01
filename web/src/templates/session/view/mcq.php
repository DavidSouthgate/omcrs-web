<?php
/**
 * @var $question QuestionMcq
 * @var $response Response
 * @var $responses Response[]
 * @var $session Session
 */

// If a response has already been made, disable the radio buttons
$disabled = $response ? " disabled" : "";

$i = 1;
foreach($question->getChoices() as $choice): ?>
    <?php
    $checked = "";
    if($response) {
        $checked = $choice->getChoiceID()==$response->getResponse() ? " checked" : "";
    }
    ?>
    <input class="answer" id="answer-<?=$i?>" type="radio" value="<?=$i?>" name="answer"<?=$checked?><?=$disabled?>>
    <label for="answer-<?=$i?>"><?=$this->e($choice->getChoice())?></label><br>
    <?php
    $i++;
endforeach;