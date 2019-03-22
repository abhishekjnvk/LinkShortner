<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Link Tracking</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="<?= getenv('app.baseURL') ?>public/assets/images/favicon.ico">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/assets/css/vendor/bootstrap.min.css">
    <link href="https://abhishekjnvk.github.io/Font-Awesome-Pro/include/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/assets/css/vendor/lightbox.css">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/assets/css/style.css">

</head>
<body>
    <div class="main-page">
        <header class="header-area formobile-menu header--transparent black-logo-version ">
            <div class="header-wrapper" id="header-wrapper">
                <div class="header-left">
                    <div class="logo">
                        <a href="<?= getenv('app.baseURL') ?>">
                            <strong class="text-light">Link Shortener</strong>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="breadcrumb-area rn-bg-color ptb--120 bg_image bg_image--1" data-black-overlay="6">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-inner pt--100 pt_sm--40 pt_md--50">
                            <h2 class="title">Link Tracking</h2>
                            <?  if(isset($server_message)){ ?>
                            <div class="alert alert-primary col-lg-4 mx-auto" role="alert">
                                <?= $server_message ?>
                                <?php

                                if(isset($ask_password)){?>
                               <form method="post"> <input type="password" class="bg-light" name="password"/><button class="btn btn-sm btn-primary mt-1">Unlock Analytics</button></form>
                                <?}
                                ?>
                            </div>
                            <?}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        if(!empty($all_data)){
        ?>
        <main class="page-wrapper">
            <!-- Start Accordion Area  -->
            <div class="rn-accordion-area rn-section-gap bg_color--5">
                <div class="container">
                    <div class="rn-accordion">
                        <div id="accordion" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">Device</th>
                                        <th scope="col">Browser<small> (Version)</small></th>
                                        <th scope="col">OS</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                    $sl=1;
                                    foreach($all_data as $data){
                                        $dat_object=json_decode($data->data);
                                        $device=$dat_object->device;
                                        if(!empty($dat_object->brand)){
                                            $device=$device.", ".$dat_object->brand;
                                        }if(!empty($dat_object->modal)){
                                            $device=$device.", ".$dat_object->modal;
                                        }
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $sl++ ?></th>
                                        <td><?= $data->ip ?></td>
                                        <td class="text-capitalize"><?= $device ?></td>
                                        <td><?= $dat_object->clientInfo->name ?> <small>(<?= $dat_object->clientInfo->version ?>)</small></td>
                                        <td><?= $dat_object->osInfo->name ?> <?php
                                                                                if (!empty($dat_object->osInfo->platform)) { ?>
                                                                                    <small>(<?= $dat_object->osInfo->platform ?>)</small>
                                                                            <?php } ?>
                                        </td>

                                        <td><?= getCity($data->ip) ?></td>
                                        <td><?= date('d/m/Y h:i:s A', $data->time) ?></td>
                                    </tr>
                                    <?}?>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
            <!-- End Accordion Area  -->

        </main>
        <?}?>
        <div class="footer-style-2 ptb--30 bg_image bg_image--1" data-black-overlay="6">
            <div class="wrapper plr--50 plr_sm--20">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="inner">
                            <div class="logo text-center text-sm-left mb_sm--20">
                                <a href="<?= getenv('app.baseURL') ?>">
                                    <strong class="text-light">LS</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="inner text-center">
                            <ul class="social-share rn-lg-size d-flex justify-content-center liststyle">
                                <li><a target="_blank" href="https://facebook.com/abhishekjnvk"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a target="_blank" href="https://github.com/abhishekjnvk"><i class="fab fa-github"></i></a></li>
                                <li><a target="_blank" href="https://linkedin.com/in/abhishekjnvk"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="inner text-lg-right text-center mt_md--20 mt_sm--20">
                            <div class="text">
                                <p>Copyright © <a target="_blank" href="https://github.com/abhishekjnvk">abhishekjnvk.</a> All Rights Reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/modernizr.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/jquery.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/bootstrap.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/stellar.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/particles.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/masonry.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/vendor/stickysidebar.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/assets/js/main.js"></script>
</body>

</html>