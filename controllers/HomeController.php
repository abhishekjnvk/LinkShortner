<?php

class HomeController extends BaseController
{
    public function index(): void
    {
        $urlModel = new Url();

        $this->render('home/index', [
            'pageTitle' => 'Shorten URLs with premium analytics',
            'status' => FlashBag::get('status'),
            'result' => FlashBag::get('short_result'),
            'trendingLinks' => $urlModel->getTrending(),
            'mostClickedLinks' => $urlModel->getMostClicked(),
            'userSummary' => is_authenticated() ? $urlModel->userSummary((int) auth_user_id()) : null,
        ]);

        clear_old_input();
    }
}
