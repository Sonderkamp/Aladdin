<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 19-5-2016
 * Time: 14:14
 */
class Controller
{
    /*
    * Renders template, passing in values.
    */
    public function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("View/$template")) {
            // extract variables into local scope
            $smarty = new Smarty();

            $smarty->assign($values);

            if (!empty($_SESSION["user"])) {
                $smarty->assign("user", $_SESSION["user"]);
            }

            if (!empty($_SESSION["admin"])) {
                $smarty->assign("admin", $_SESSION["admin"]);
            }

            $quote = new Quote();
            $_SESSION["quote"] = $quote->getQuote();

            // render header
            $smarty->display("View/header.tpl");

            // render template
            $smarty->display("View/$template");

            // render footer
            $smarty->display("View/footer.tpl");

        }
    }

    public function apologize($message)
    {
        $err = new ErrorController();
        $err->message = $message;
        $err->render();
        exit;
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
   public  function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination)) {
            header("Location: " . $destination);
        } // handle absolute path
        else if (preg_match("/^\//", $destination)) {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        } // handle relative path
        else {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }
}