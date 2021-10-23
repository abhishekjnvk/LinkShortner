<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expired Link</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-950 px-6 text-white">
    <div class="max-w-md rounded-[2rem] border border-white/10 bg-white/5 p-8 text-center backdrop-blur">
        <p class="text-sm font-semibold text-amber-300">410</p>
        <div class="mx-auto mt-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-white/10 text-amber-200">
            <?= icon('clock', 'h-8 w-8')?>
        </div>
        <h1 class="mt-3 text-4xl font-extrabold">This link has expired.</h1>
        <p class="mt-3 text-sm text-slate-300">The expiration date for this short URL has passed, so it no longer
            redirects visitors.</p>
        <a href="<?= e(app_url())?>"
            class="mt-6 inline-flex items-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950">
            <?= icon('sparkles', 'mr-2 h-4 w-4')?>Create a new link
        </a>
    </div>
</body>

</html>