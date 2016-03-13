<?php

class NavigationController {

    public function __construct() {

    }

    public function run() {
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "view_category":
                    $this->view_category();
                    break;
                default:
                    apologize("404 not found, Go back to my admin panel");
                    break;
            }
        } else {
            $this->navigation_home();
            exit();
        }
    }

    public function navigation_home(){
        echo "CHECK";
    }


}