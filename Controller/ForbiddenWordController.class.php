<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 25-4-2016
 * Time: 12:11
 */
class ForbiddenWordController
{
    private $forbidden_word_repository, $forbidden_words, $error_message, $success_message, $number_of_words, $current_number_of_words, $pagination;

    public function __construct() {

        guaranteeAdmin("forbiddenwords");

        $this->forbidden_word_repository = new ForbiddenWordRepository();
        $this->number_of_words = ceil($this->forbidden_word_repository->countForbiddenWords()/10);
    }

    public function run() {

        $this->checkSession();
        $this->checkPost();
        $this->checkGet();

        render("Admin/forbiddenWord.tpl",
            ["title" => "Verboden woorden",
            "forbidden_words" => $this->forbidden_words,
            "error_message" => $this->error_message,
            "success_message" => $this->success_message,
            "number_of_words" => $this->number_of_words,
            "current_number_of_words" => $this->current_number_of_words]);
        exit(0);
    }

    private function checkPost() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!Empty($_POST["add_forbidden_word"])) {

                $forbidden_word = htmlentities(trim($_POST["add_forbidden_word"]),ENT_QUOTES);

                $success = $this->checkWordForAdding($forbidden_word);

                if($success != false) {

                    $this->forbidden_word_repository->createForbiddenWord($forbidden_word);
                    $_SESSION["forbidden_words_success"] = 'Het woord "'.$forbidden_word.'" is succesvol toegevoegd!';
                }

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }

            if (!Empty($_POST["remove_forbidden_word"])) {

                $forbidden_word = htmlentities(trim($_POST["remove_forbidden_word"]),ENT_QUOTES);

                $this->forbidden_word_repository->deleteForbiddenWord($forbidden_word);

                if(Empty($this->forbidden_word_repository->getForbiddenWords(null,$forbidden_word))) {

                    $_SESSION["forbidden_words_success"] = 'Het woord "'.$forbidden_word.'" is succesvol verwijderd!';
                } else {

                    $_SESSION["forbidden_words_error"] = 'Het woord "'.$forbidden_word.'" is niet verwijderd!';
                }

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }

            if (!Empty($_POST["edit_forbidden_word_new"]) && !Empty($_POST["edit_forbidden_word_old"])) {

                $forbidden_word_new = htmlentities(trim($_POST["edit_forbidden_word_new"]),ENT_QUOTES);
                $forbidden_word_old = htmlentities(trim($_POST["edit_forbidden_word_old"]),ENT_QUOTES);

                $success = $this->checkWordForAdding($forbidden_word_new);

                if($success != false) {

                    $this->forbidden_word_repository->updateForbiddenWord($forbidden_word_old,$forbidden_word_new);
                    $_SESSION["forbidden_words_success"] = 'Het woord "'.$forbidden_word_old.'" is succesvol gewijzigd naar "'.$forbidden_word_new.'"!';
                } else {

                    $_SESSION["forbidden_words_error"] .= ' Het woord "'.$forbidden_word_old.'" blijft ongewijzigd!';
                }

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }

            if (!Empty($_POST["pagination"])) {

                if($_POST["pagination"] == "off") {

                    $_SESSION["forbidden_words_pagination"] = "off";
                } else {

                    $_SESSION["forbidden_words_pagination"] = "on";
                }

                header("HTTP/1.1 303 See Other");
                header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
    }

    private function checkGet() {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            if (!Empty($_GET["search"])) {

                $search = htmlentities(trim($_GET["search"]),ENT_QUOTES);
                $this->number_of_words = ceil($this->forbidden_word_repository->countForbiddenWords($search)/10);
            } else {

                $search = null;
            }

            if($_SESSION["forbidden_words_pagination"] != "off") {

                if (!Empty($_GET["words_page"])) {

                    if ($_GET["words_page"] > 0 && $_GET["words_page"] <= $this->number_of_words) {

                        $this->forbidden_words = $this->forbidden_word_repository->getForbiddenWords($_GET["words_page"], null, $search);
                        $this->current_number_of_words = $_GET["words_page"];
                    } else {

                        $this->forbidden_words = $this->forbidden_word_repository->getForbiddenWords(1, null, $search);
                        $this->current_number_of_words = 1;
                    }
                } else {

                    $this->forbidden_words = $this->forbidden_word_repository->getForbiddenWords(1, null, $search);
                    $this->current_number_of_words = 1;
                }
            } else {

                $this->forbidden_words = $this->forbidden_word_repository->getForbiddenWords(null, null, $search);
            }
        }
    }

    private function checkSession() {

        if(!Empty($_SESSION["forbidden_words_error"])) {

            $this->error_message = $_SESSION["forbidden_words_error"];
            $_SESSION["forbidden_words_error"] = "";
        }

        if(!Empty($_SESSION["forbidden_words_success"])) {

            $this->success_message = $_SESSION["forbidden_words_success"];
            $_SESSION["forbidden_words_success"] = "";
        }

        if(Empty($_SESSION["forbidden_words_pagination"])) {

            $_SESSION["forbidden_words_pagination"] = "on";
        }
    }

    private function checkWordForAdding($forbidden_word) {

        $success = "";

        foreach ($this->forbidden_word_repository->getForbiddenWords() as $word) {

            if(strtolower($word) == strtolower($forbidden_word)) {

                $success = false;
                $_SESSION["forbidden_words_error"] = 'Het woord "'.$forbidden_word.'" bestaat al!';

                break;
            }
        }

        if(strlen($forbidden_word) > 150) {

            $success = false;
            $_SESSION["forbidden_words_error"] .= ' Het woord "'.$forbidden_word.'" is '.(strlen($forbidden_word) - 150).' karakters te lang! Het woord mag maar 150 lang zijn!';
        }

        return $success;
    }
}