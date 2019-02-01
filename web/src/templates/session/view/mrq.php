<?php
/**
 * @var $question QuestionMrq
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
    if($responses) {
        //
        foreach ($responses as $response) {
            if($choice->getChoiceID()==$response->getResponse()) {
                $checked = $choice->getChoiceID()==$response->getResponse() ? " checked" : "";
                break;
            }
        }
    }
    ?>
    <input class="answer" id="answer-<?=$i?>" type="checkbox" value="<?=$i?>" name="answer-<?=$i?>"<?=$checked?><?=$disabled?>>
    <label style="margin-bottom: 0" for="answer-<?=$i?>"><?=$this->e($choice->getChoice())?></label><br>
    <?php
    $i++;
endforeach;