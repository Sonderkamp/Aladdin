<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 13-6-2016
 * Time: 16:05
 */
class GuestbookController extends Controller
{
    public function run() {
        $this->render("guestbook.tpl",
            ["title" => "Gastenboek",
            "comments" => (new WishRepository())->getComments()]);
        exit(0);
    }
}