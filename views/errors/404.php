<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-950 px-6 text-white">
    <div class="max-w-md rounded-[2rem] border border-white/10 bg-white/5 p-8 text-center backdrop-blur">
        <p class="text-sm font-semibold text-cyan-300">404</p>
        <div class="mx-auto mt-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-white/10 text-cyan-200">
            <?= icon('link', 'h-8 w-8')?>
        </div>
        <h1 class="mt-3 text-4xl font-extrabold">That short link is not here.</h1>
        <p class="mt-3 text-sm text-slate-300">It may have been mistyped, removed, or never created in this copy of the
            app.</p>
        <a href="<?= e(app_url())?>"
            class="mt-6 inline-flex items-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950">
            <?= icon('home', 'mr-2 h-4 w-4')?>Go home
        </a>
    </div>
</body>

</html>