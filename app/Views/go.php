<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Shortener</title>
    <link href="https://abhishekjnvk.github.io/Font-Awesome-Pro/include/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/styles.css">
</head>

<body class="d-flex flex-column">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mx-auto">
                <div class="form-body">
                    <div class="form-holder">
                        <div class="form-content">
                            <div class="form-items text-center">
                                <a href="<?= $redirect_url ?>" class="rn-button-style--2">
                                    <h1 class=" theme-gradient">Redirect Me</h1>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo view('components/footer.php'); ?>
</body>

</html>