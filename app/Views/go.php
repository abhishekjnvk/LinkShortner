<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Go To <?= $link_detail->link ?></title>
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

        <main class="page-wrapper">
            <div class="error-page-inner bg_color--4">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inner">
                                <h2 class="title theme-gradient" id="timer">Please Wait</h2>
                                <div class="error-button">
                                    <a href="<?= $link_detail->link ?>" class="rn-button-style--2 btn_solid">Force Proceed</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
    <script>
        var time = 100;
        setInterval(() => {
            if (time > 0)
                time = time - 1;
            document.getElementById("timer").innerHTML = "Redirecting you in " + time + " second";
            if (time < 1) {
                window.location = `<?= $link_detail->link ?>`;
            }
        }, 1000)
    </script>

</body>

</html>