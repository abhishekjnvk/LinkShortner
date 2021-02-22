<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://abhishekjnvk.github.io/Font-Awesome-Pro/include/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= getenv('app.baseURL') ?>public/styles.css">
    <script>
        const base_url = "<?= getenv('app.baseURL') ?>";
    </script>
</head>

<body class="d-flex flex-column">
    <div class="container">
        <div class="col-lg-5 mx-auto py-5">
            <div class="mt-5 border border-secondary shadow-lg p-3 py-2 rounded">
                <h1 class="text-center theme-gradient header-title">Link Shortener</h1>
                <center><small class="text-light">&<br /> Analytics</small></center>
                <div id="alert_box"></div>
                <form class="requires-validation mt-3" id="shortener_form" novalidate>
                    <div class="col-md-12">
                        <label for="link_address">URL</label>
                        <input class="form-control mt-1" type="url" autocomplete="no-fill" id="link_address" name="link" placeholder="Url to Shorten" required>
                        <div class="invalid-feedback">Please enter a valid url</div>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input hand-pointer" type="checkbox" id="advance_options">
                        <label class="form-check-label hand-pointer" for="advance_options">Show Advance Options</label>
                    </div>
                    <div id="advance_options_div" class="hidden-item">
                        <div class="col-md-12 mt-3">
                            <label for="link_expiry">URL Title</label>
                            <input class="form-control mt-1" type="text" name="title" id="link_title" placeholder="Title">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="link_expiry">URL Expiry</label>
                            <select name="expiry" id="link_expiry" class="form-select mt-1" required>
                                <option selected value="0">No Expiry</option>
                                <option value="86400">24Hrs</option>
                                <option value="604800">7 Days</option>
                                <option value="2592000">30 Days</option>
                                <option value="31536000">1 Year</option>
                            </select>
                            <div class="invalid-feedback">Please select link expiry!</div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <input type="radio" class="btn-check" name="analytics" id="analytics_disabled" autocomplete="no-fill" checked required>
                            <label class="btn btn-sm btn-outline-secondary" for="analytics_disabled">Analytics Disabled</label>
                            <input type="radio" class="btn-check" name="analytics" id="analytics_enabled" autocomplete="no-fill" required>
                            <label class="btn btn-sm btn-outline-secondary" for="analytics_enabled">Analytics Enabled</label><br />
                            <div class="invalid-feedback mt-2">Please a choice</div>
                        </div>

                        <div class="col-md-12 mt-3 hidden-item" id="password_div">
                            <label for="analytics_password">Analytics Password</label>
                            <input class="form-control mt-1" autocomplete="no-fill" id="analytics_password" type="password" name="password" placeholder="Password">
                            <div class="valid-feedback">Password field is valid!</div>
                            <div class="invalid-feedback">Password field cannot be blank!</div>
                        </div>
                    </div>


                    <div class="form-button text-center mt-3">
                        <button type="submit" id="btn-submit" class="btn btn-lg btn-primary">Shorten URL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php echo view('components/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>public/home.js"></script>
</body>

</html>