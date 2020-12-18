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

        <h1 class="text-center mt-5">
            <a href="<?= getenv('app.baseURL') ?>">
                <span class="theme-gradient">Link Shortener</span> <span class="text-light">|</span><span class="theme-gradient"> Analytics</span>
            </a>
        </h1>
        <div class="mb-3 col-lg-3 mx-auto py-5 mt-5">
            <form class="d-flex" method="post">
                <input type="password" class="form-control me-2" name="password" value="<?php
                                                                                        if (!empty($_POST['password'])) {
                                                                                            echo $_POST['password'];
                                                                                        } ?>" placeholder="Enter your analytics password">
                <button class="btn btn-info btn-bg" type="submit">Get</button>
            </form>

        </div>

        <?php if (!empty($all_data)) {  ?>
            <div class="text-center">
                <h3 class="title theme-gradient text-center mb-4">Total Clicks: <?= count($all_data) ?></h3>
            </div>
            <div class="bg-light p-2 rounded">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">IP</th>
                            <th scope="col">Device</th>
                            <th scope="col">Browser<small> (Version)</small></th>
                            <th scope="col">OS</th>
                            <th scope="col text-center">Repetitive Count</th>
                            <th scope="col">City</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($all_data as $data) {
                            $dat_object = json_decode($data->data);
                            $device = $dat_object->device;
                            if (!empty($dat_object->brand)) {
                                $device = $device . ", " . $dat_object->brand;
                            }
                            if (!empty($dat_object->modal)) {
                                $device = $device . ", " . $dat_object->modal;
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
                                <td class="text-center"><?= $data->times ?></td>

                                <td><?= getCity($data->ip) ?></td>
                                <td title="Epoch: <?= $data->time ?>"><?= date('d/m/Y h:i A', $data->time) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <?php if (!empty($server_message)) { ?>
                <div class="alert alert-danger col-lg-4 mx-auto" role="alert">
                    <?= $server_message ?>
                </div>
            <?php } ?>
        <?php } ?>
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