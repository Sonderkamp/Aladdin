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

        if ((new AdminRepository())->getCurrentAdmin() !== false) {
            $this->render("guestbook.tpl",
                ["title" => "Gastenboek",
                    "comments" => (new WishRepository())->getComments(),
                    "isAdmin" => true]);
        } else {
            $this->render("guestbook.tpl",
                ["title" => "Gastenboek",
                    "comments" => (new WishRepository())->getComments(),
                    "isAdmin" => false]);
        }

        exit(0);
    }

    // This method only removes the comment from the guestbook, so it does NOT remove the entire comment
    public function removeComment() {
        
        if(!empty($_POST["wishId"]) && !empty($_POST["creationDate"]) && !empty($_POST["username"])){
            (new AdminController())->guaranteeAdmin("/guestbook");

            $id = htmlspecialchars($_POST["wishId"]);
            $date = htmlspecialchars($_POST["creationDate"]);
            $name = htmlspecialchars($_POST["username"]);
            
            (new WishRepository())->removeFromGuestbook($date,$name,$id);
        }

        $this->redirect("/guestbook");
    }
}