<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $responsesMrq array
 * @var $responsesMcq array
 * @var $userMcqResponses array
 * @var $userMrqResponses array
 * @var $responsesWordCloud array
 * @var $responsesText Response[]
 * @var $live boolean
 * @var $question Question
 * @var $session Session
 */

// Ensure $live is a valid boolean
$live = isset($live) ? !!$live : false;

$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "noHeaderFooter" => $live,
        "alert" => $alert
    ]
);

$backgroundColours = [];
$backgroundColours[] = "rgba(212, 2, 2, 0.5)";
$backgroundColours[] = "rgba(54, 162, 235, 0.5)";
$backgroundColours[] = "rgba(96, 169, 24, 0.5)";
$backgroundColours[] = "rgba(153, 102, 255, 0.5)";
$backgroundColours[] = "rgba(255, 128, 0, 0.5)";
$backgroundColours[] = "rgba(23, 0, 77, 0.5)";

$borderColours = [];
$borderColours[] = "rgba(212, 2, 2, 1)";
$borderColours[] = "rgba(54, 162, 235, 1)";
$borderColours[] = "rgba(96, 169, 24, 1)";
$borderColours[] = "rgba(153, 102, 255, 1)";
$borderColours[] = "rgba(255, 128, 0, 1)";
$borderColours[] = "rgba(23, 0, 77, 1)";

function getColour($colours, $i) {
    return $colours[$i%count($colours)];
}

?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="<?=$this->e($config["baseUrl"])?>css/session/edit/question/response.css" />
    <meta name="sessionIdentifier" content="<?=$session->getSessionIdentifier()?>" />
    <meta name="sessionQuestionID" content="<?=$question->getSessionQuestionID()?>" />
<?php $this->end(); ?>

<?php $this->push("end"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js" crossorigin="anonymous"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit/question/response.js" crossorigin="anonymous"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.js" charset="utf-8"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.layout.cloud.js"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.wordcloud.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script>
        <?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
            initBarChartSection();
        <?php elseif(isset($responsesWordCloud)): ?>
            initWordCloudSection('<?=str_replace("'","\'",json_encode($responsesWordCloud))?>');
        <?php endif; ?>
    </script>
<?php $this->stop(); ?>

<?php if(!$live): ?>
    <div class="page-header live">
        <h1 class="row">
            <div class="col-sm-9">
                <h1>Responses</h1>
            </div>
            <div class="col-sm-3">
                <a href="<?=$config["baseUrl"]?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/" class="btn btn-light btn-light-border pull-right width-xs-full">Edit Session</a>
            </div>
        </h1>
    </div>
<?php else: ?>
    <div class="page-header">
        <h1 class="row">
            <?=$question->getQuestion()?>
        </h1>
    </div>
<?php endif; ?>

<ul class="nav nav-tabs" data-target="sections">
    <?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
        <li class="nav-item" id="nav-bar-chart" data-target="section-bar-chart" data-callback="initBarChartSection">
            <a class="nav-link active" href="#">Bar Chart</a>
        </li>
        <li class="nav-item" id="nav-pie-chart" data-target="section-pie-chart" data-callback="initPieChartSection">
            <a class="nav-link" href="#">Pie Chart</a>
        </li>
        <li class="nav-item" id="nav-responses" data-target="section-responses">
            <a class="nav-link" href="#">Responses</a>
        </li>
    <?php endif; ?>
    <?php if(isset($responsesWordCloud)): ?>
        <li class="nav-item" id="nav-word-cloud" data-target="section-word-cloud">
            <a class="nav-link active" href="#">Word Cloud</a>
        </li>
    <?php endif; ?>
    <?php if($question->getType() == "textlong" || $question->getType() == "text"): ?>
        <li class="nav-item" id="nav-analysis" data-target="section-analysis">
            <a class="nav-link" href="#">Response Clusters</a>
        </li>
        <li class="nav-item" id="nav-bar-analysis" data-target="section-bar-analysis">
            <a class="nav-link" href="#">Response Cluster Counts</a>
        </li>
    <?php endif; ?>
    <?php if(isset($responsesWordCloud)): ?>
        <li class="nav-item" id="nav-responses" data-target="section-responses">
            <a class="nav-link" href="#">Responses</a>
        </li>
    <?php endif; ?>
</ul>


<div class="sections" id="sections">
    <?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
        <div id="section-bar-chart" class="section">
            <canvas id="bar-chart" width="400" height="200" style="margin-top: 60px;"></canvas>
        </div>
        <br>
        <div id="section-pie-chart" class="section display-none">
            <canvas id="pie-chart" width="400" height="200" style="margin-top: 60px;"></canvas>
        </div>
    <?php endif; ?>
    <?php if($question->getType() == "textlong" || $question->getType() == "text"): ?>
        <div id="section-analysis" class="section display-none">
            <div id="analysis-loading" class="analysis-loading">Loading...</div>
            <div id="no-analysis-error" class="no-analysis-error display-none">No Analysis Available</div>
            <canvas id="analysis-chart" width="400" height="200"></canvas>
            <div id="analysis-description" class="container">
                <hr>
                <h5>Cluster Graph Topic Analysis</h5>
                <h6>Every dot represents a response. Responses are clustered (colored) and labeled above
                based on the main topics they discuss. Note that these labels represent topics through
                the two most unique words of each cluster. Dots closer together represents
                responses with similar word structure and content. To find the total count of responses in each
                topic cluster, please see the Response Cluster Counts tab.</h6>
            </div>
        </div>
        <div id="section-bar-analysis" class="section display-none">
            <div id="analysis-loading-2" class="analysis-loading">Loading...</div>
            <div id="no-analysis-error-2" class="no-analysis-error display-none">No Analysis Available</div>
            <canvas id="bar-analysis" width="400" height="200"></canvas>
        </div>
    <?php endif; ?>

    <?php // The word cloud bit ?>
    <?php if(isset($responsesWordCloud)): ?>
        <div id="section-word-cloud" class="section">
            <div id="wordcloud"></div>
        </div>
    <?php endif; ?>
    <?php if(isset($responsesText) || isset($userMcqResponses) || isset($userMrqResponses)): ?>
        <div id="section-responses" class="section display-none">
            <button id="display-personal" class="btn btn-primary width-xs-full">Display Personal Information</button>
            <button id="hide-personal" class="btn btn-primary width-xs-full">Hide Personal Information</button>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="username" scope="col">Username</th>
                    <th class="fullname" scope="col">Full Name</th>
                    <th scope="col">Time</th>
                    <th scope="col">Response</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($responsesText)): ?>
                    <?php foreach($responsesText as $response): ?>
                        <tr>
                            <td class="username">
                                <?=$response->getUser()->isGuest() ? "Guest" : $this->e($response->getUser()->getUsername())?>
                            </td>
                            <td class="fullname">
                                <?=$this->e($response->getUser()->getFullName())?>
                            </td>
                            <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                            <td><?=$this->e($response->getResponse())?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif(isset($userMcqResponses)): ?>
                    <?php foreach($userMcqResponses as $response): ?>
                        <tr>
                            <td class="username">
                                <?=$response->getUser()->isGuest() ? "Guest" : $this->e($response->getUser()->getUsername())?>
                            </td>
                            <td class="fullname">
                                <?=$this->e($response->getUser()->getFullName())?>
                            </td>
                            <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                            <td><?=$this->e($response->getResponse())?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif(isset($userMrqResponses)): ?>
                    <?php foreach($userMrqResponses as $response): ?>
                        <tr>
                            <td class="username">
                                <?=$this->e($user->isGuest()) ? "Guest" : $this->e($user->getUsername())?>
                            </td>
                            <td class="fullname">
                                <?=$this->e($user->getFullName())?>
                            </td>
                            <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                            <td><?=$this->e($response->getResponse())?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>

    var backgroundColours = [
        <?php
        foreach($backgroundColours as $colour) {
            echo "'$colour',";
        }
        ?>
    ];

    function getColour(colours, i) {
        return colours[i%colours.length]
    }

    function chartJsLabelFormat(str) {
        str = str.replace("&#039;", "'");
        var regex = /(&#)([0-9]*)(;)/;
        return str.replace(regex, "");
    }

    <?php if(isset($responsesMcq)): ?>
        var labels = [
            <?php foreach($responsesMcq as $response): ?>
            chartJsLabelFormat("<?=$this->e($response["choice"])?>"),
            <?php endforeach; ?>
        ];

        var data = [
            <?php foreach($responsesMcq as $response): ?>
                <?=$response["count"]?$this->e($response["count"]):0?>,
            <?php endforeach; ?>
        ];

        var backgroundColor = [
            <?php for($i = 0; $i < count($responsesMcq); $i++): ?>
                '<?=getColour($backgroundColours, $i)?>',
            <?php endfor; ?>
        ];
        var borderColor = [
            <?php for($i = 0; $i < count($responsesMcq); $i++): ?>
                '<?=getColour($borderColours, $i)?>',
            <?php endfor; ?>
        ];
    <?php endif; ?>
    <?php if(isset($responsesMrq)): ?>
    var labels = [
        <?php foreach($responsesMrq as $response): ?>
        chartJsLabelFormat("<?=$this->e($response["choice"])?>"),
        <?php endforeach; ?>
    ];

    var data = [
        <?php foreach($responsesMrq as $response): ?>
        <?=$response["count"]?$this->e($response["count"]):0?>,
        <?php endforeach; ?>
    ];

    var backgroundColor = [
        <?php for($i = 0; $i < count($responsesMrq); $i++): ?>
        '<?=getColour($backgroundColours, $i)?>',
        <?php endfor; ?>
    ];
    var borderColor = [
        <?php for($i = 0; $i < count($responsesMrq); $i++): ?>
        '<?=getColour($borderColours, $i)?>',
        <?php endfor; ?>
    ];
    <?php endif; ?>
</script>
