<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $user User
 * @var $alert Alert
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Help",
        "description" => $description,
        "user" => $user,
        "alert" => $alert
    ]
);

$user = !$user ? new User() : $user;
?>

<div class="row">
    <div class="col-3" style="padding:30px">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#faqlogin" data-toggle="pill" >FAQ/Login</a>
            </li>


            <?php if($user->isSessionCreator() || $user->isAdmin()): ?>

                <?php if(!isDesktopApp()): ?>
                    <li class="nav-item">
                    <a class="nav-link" href="#homepage" data-toggle="pill">Home Page</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#homepagedesktop" data-toggle="pill">Home Page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#liveview" data-toggle="pill">Live View</a>
                    </li>
                <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="#newsession" data-toggle="pill">Creating a New Session</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#newsessionadv"data-toggle="pill">Editing Session</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#editsession"data-toggle="pill">Adding Questions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#addquestions"data-toggle="pill">Viewing Responses (Numerical)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#viewresponses"data-toggle="pill">Viewing Responses  (Text)</a>
            </li>

            <?php else: ?>

                <li class="nav-item">
                    <a class="nav-link" href="#homepagebasic" data-toggle="pill">Home Page</a>
                </li>
            <?php endif; ?>

        </ul>
    </div>

    <div class="col-9">
        <div class="tab-content">
            <div id="faqlogin" class="tab-pane fade show active">
                <div>
                    <img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-1a.jpg" width="100%" />
                </div>
                <div style="margin-left: 85px; font-size: 20px;">
                    <a href="<?=$this->e($config["baseUrl"])?>"><?=$this->e($config["baseUrl"])?></a>
                </div>
                <div>
                    <img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-1b.jpg" width="100%" />
                </div>
            </div>
            <div id="homepage" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-2.jpg" width="100%" ></div>
            <div id="newsession" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-3.jpg" width="100%" ></div>
            <div id="newsessionadv" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-4.jpg" width="100%" ></div>
            <div id="editsession" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-5.jpg" width="100%" ></div>
            <div id="addquestions" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-6.jpg" width="100%" ></div>
            <div id="viewresponses" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-7.jpg" width="100%" ></div>
            <div id="homepagebasic" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-8.jpg" width="100%" ></div>
            <div id="liveview" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-9.jpg" width="100%" ></div>
            <div id="homepagedesktop" class="tab-pane fade"><img src="<?=$this->e($config["baseUrl"])?>img/help/OMCRS Help-10.jpg" width="100%" ></div>

        </div>
.




    </div>

</div>


