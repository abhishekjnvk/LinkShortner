<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link href="https://abhishekjnvk.github.io/Font-Awesome-Pro/include/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/styles.css">
</head>

<body class="d-flex flex-column">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mx-auto">
                <div class="form-body">
                    <div class="row">
                        <div class="form-holder">
                            <div class="form-content">
                                <div class="form-items">
                                    <h1 class="text-center theme-gradient">Link Shortener</h1>
                                    <div id="alert_box" class="my-5 text-center">
                                        <h3 class="title theme-gradient text-center mb-4"><?php
                                                                                            if (isset($error)) {
                                                                                                echo $error;
                                                                                            } else {
                                                                                                echo "Something went wrong";
                                                                                            } ?></h3>
                                        <div class="error-button">
                                            <a href="<?= getenv('app.baseURL') ?>" class="rn-button-style--2 btn_solid">Back To Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 border-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <p class="text-muted">Made with <span class="text-danger">&hearts;</span> by <a href="https://github.com/abhishekjnvk">
                            <span class="text-primary">Abhishek</span>
                        </a>
                    </p>
                </div>

                <div class="col-lg-8 text-end">
                    <a class="footer-link" target="_blank" href="https://instagram.com/abhishekjnvk"><i class="fab fa-instagram text-danger"></i></a>
                    <a class="footer-link" target="_blank" href="https://github.com/abhishekjnvk"><i class="fab fa-github"></i></a>
                    <a class="footer-link" target="_blank" href="https://linkedin.com/in/abhishekjnvk"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>