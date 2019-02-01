<?php
/**
 * @var $question Question
 * @var $response Response
 * @var $responses Response[]
 * @var $session Session
 * @var $totalQuestions int
 * @var $questionNumber int
 */
?>
<h2 class="page-section">
    <?=$this->e($question->getQuestion());?>
</h2>
<form action="." method="POST">
    <input name="sessionQuestionID" id="sessionQuestionID" value="<?=$this->e($question->getSessionQuestionID())?>" type="hidden">
    <input name="questionNumber" id="questionNumber" value="<?=$this->e($questionNumber)?>" type="hidden">

    <?php
    $type = $question->getType();

    try {
        $this->insert("session/view/$type", [
            "question" => $question,
            "response" => $response,
            "responses" => $responses,
            "session" => $session
        ]);
    }

    catch(Exception $e) {
        echo "Invalid Question Type";
    }

    ?>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <input id="answer-submit" name="submit" value="Submit Answer" class="answer-submit btn btn-primary<?=$response?" display-none":""?>" type="submit">
            <?php if($response && $session->getAllowModifyAnswer()): ?>
                <button id="answer-update" type="button" class="btn btn-success">
                    Update Answer
                </button>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <div id="question-navigation" class="pull-right">

                <?php if($session->getQuestionControlMode() == 0): ?>
                    <a id="check-new-question" href="." class="btn btn-light btn-light-border">
                        Check for new question
                    </a>

                <?php elseif($session->getQuestionControlMode() == 1): ?>
                    <nav id="question-pagination" aria-label="Question Navigation">
                        <ul class="pagination">

                            <?php
                            $current = $questionNumber + 1;
                            $lower = 1;
                            $upper = $totalQuestions;
                            $z = 2;

                            // The lower and upper number visible
                            $lowerVisible = $current - $z;
                            $upperVisible = $current + $z;

                            // Get the difference from the current to the lower/upper
                            $lowerDifference = $current - $lower;
                            $upperDifference = $upper - $current;

                            // If there are not enough items at lower end, display more at upper end
                            if($lowerDifference < $z) $upperVisible += ($z - $lowerDifference);

                            // If there are not enough items at upper end, display more at lower end
                            if($upperDifference < $z) $lowerVisible -= ($z - $upperDifference);

                            $lowerVisible = $lowerVisible < $lower ? $lower : $lowerVisible;
                            $upperVisible = $upperVisible > $upper ? $upper : $upperVisible;

                            ?>

                            <li class="page-item<?=$this->e($questionNumber)<=0?" disabled":""?>">
                                <a class="page-link" href=".?q=<?=$this->e($lower)?>" aria-label="First">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">First</span>
                                </a>
                            </li>
                            <li class="page-item<?=$questionNumber<=0?" disabled":""?>">
                                <a class="page-link" href=".?q=<?=$this->e($current-1)?>" aria-label="Previous">
                                    <span aria-hidden="true">&lsaquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>

                            <?php if($lowerVisible > $lower): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>

                            <?php for($i = $lower; $i <= $upper; $i++): ?>
                                <?php if($i >= $lowerVisible && $i <= $upperVisible): ?>
                                    <li class="page-item<?=$i==$current?" active":""?><?=$this->e($class)?>">
                                        <a class="page-link" href=".?q=<?=$i?>">
                                            <?=$i?>
                                            <?php if($i==$current): ?>
                                                <span class="sr-only">(current)</span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if($upperVisible < $upper): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>

                            <li class="page-item<?=$questionNumber>=$totalQuestions-1?" disabled":""?>">
                                <a class="page-link" href=".?q=<?=$this->e($current+1)?>" aria-label="Next">
                                    <span aria-hidden="true">&rsaquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                            <li class="page-item<?=$questionNumber>=$totalQuestions-1?" disabled":""?>">
                                <a class="page-link" href=".?q=<?=$this->e($upper)?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Last</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>