<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25-Feb-16
 * Time: 15:08
 */
class WishController
{

    public
        $completedWishes,
        $incompletedWishes,
        $wishRepo,
        $talentRepository,
        $reportRepository,
        $userRepostitory,
        $title,
        $description,
        $tag,
        $isAccepted,
        $wishContentId,
        $maxContentLength = 50,
        $currentPage;

    public function __construct()
    {
        $this->wishRepo = new WishRepository();
        $this->talentRepository = new TalentRepository();
        $this->userRepostitory = new UserRepository();
        $this->reportRepository = new ReportRepository();
    }

    public function run()
    {
        guaranteeLogin("/Wishes");

        if(isset($_GET["show"])){
            switch(strtolower($_GET["show"])){
                case "mywishes":
                    guaranteeProfile();
                    $this->renderOverview("myWishes");
                    break;
                case "incompletedwishes":
                    guaranteeProfile();
                    $this->renderOverview("incompletedWishes");
                    break;
                case "completedwishes":
                    guaranteeProfile();
                    $this->renderOverview("completedWishes");
                    break;
                case "open_edit_wish":
                    $this->open_wish_view(false);
                    break;
                case "open_wish":
                    $this->open_wish_view(true);
                    break;
            }

        }

        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                //remove refrences to match show=openeditwish
                case "open_edit_wish":
                    $this->open_wish_view(false);
                    exit(0);
                    break;
                case "open_wish":
                    $this->open_wish_view(true);
                    exit(0);
                    break;
                case "addwish":
                    $this->add_wish();
                    break;
                case "editwish":
                    $this->edit_wish();
                    break;
                case "remove":
                    $this->remove();
                    break;
                case "go_back":
                    $this->go_back();
                    break;
                case "report":
                    break;
                case "back":
                    $this->back();
                    break;
                default:
                    apologize("404 not found, Go back to my wishes");
                    break;
            }
        } else if (isset($_GET["wish_id"])) {
//            guaranteeProfile();
            if (isset($_POST["page"])) {
                $this->getSpecificWish($_GET["wish_id"], $_POST["page"]);
            } else {
                $this->getSpecificWish($_GET["wish_id"], null);
            }
        }

        //werkt nog niet todat de hosting gefixt is
        if(isset($_GET["search"])){
            $this->searchWish($_GET["search"]);
        }

        else if (isset($_POST["match/wish_id"])) {
            guaranteeProfile();
            $this->requestMatch($_POST["match/wish_id"]);
        } else {
            guaranteeProfile();
            $this->renderOverview("myWishes");
        }
    }

    private function renderOverview($currentPage)
    {
        $myWishes = $this->wishRepo->getMyWishes();
        $completedWishes = $this->wishRepo->getCompletedWishes();
        $myCompletedWishes = $this->wishRepo->getMyCompletedWishes();
        $incompletedWishes = $this->wishRepo->getIncompletedWishes();
        $matchedWishes = $this->wishRepo->getMatchedWishes();
        $canAddWish = $this->wishRepo->canAddWish($_SESSION["user"]->email);
        $this->setCurrent($currentPage);

        $report = $this->reportRepository->getReportedUsers();
        $displayNames = array();
        $amountReports = count($report);

        if ($amountReports !== 0) {
            foreach ($report as $item) {
                $displayNames[] = $item->getReported()->getDisplayName();
            }
        }


        render("wishOverview.tpl" , ["title" => "Wensen Overzicht",
            "myWishes"          => $myWishes,
            "completedWishes"   => $completedWishes,
            "myCompletedWishes" => $myCompletedWishes,
            "incompletedWishes" => $incompletedWishes,
            "matchedWishes"     => $matchedWishes,
            "currentPage"       => $currentPage,
            "canAddWish"        => $canAddWish,

            //Might be deprecated
            "reported"          => $displayNames
        ]);

        exit(0);
    }

    private function searchWish($key)
    {
        //Werkt als de sql versie geupdate wordt.
        $searchReturn = $this->wishRepo->searchMyWishes($key);
//        render("wishOverview.tpl", ["title" => "Wensen overzicht", "wishes" => $searchReturn]);
    }

    //used to shorten string if need be
    private function checkWishContent($string)
    {
        if (strlen($string) > $this->maxContentLength) {
            $returnString = substr($string, 0, $this->maxContentLength);
            $returnString = $returnString . '...';
            return $returnString;
        }
        return $string;
    }


    private function open_wish_view($open)
    {
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepo->canAddWish($_SESSION["user"]->email);
            if (!$canAddWish) {
                $this->go_back();
                exit(1);
            }

            render("addWish.tpl", ["title" => "Wens toevoegen"]);

        } else {
            $this->wishContentId = $_GET["editwishbtn"];
            $_SESSION["wishcontentid"] = $_GET["editwishbtn"];

            $wish = $this->wishRepo->getWish($this->wishContentId);

//            $id = $wish[0]["wish_Id"];
//            $returnWish = $this->wishRepo->getAllWishesByEmail($_SESSION["user"]->email);
//
//            if (!in_array($id, $returnWish)) {
//                $this->go_back();
//                exit(1);
//            }
            $title = $wish->title;
            $description = $wish->content;
            $tempTag = $this->wishRepo->getWishTalent($this->wishContentId);
            $tag = $this->prepend("#", implode(" #", $tempTag));

            render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "edit" => "isset", "tag" => $tag, "previousPage"]);
        }
    }

    function prepend($string, $chunk)
    {
        if (!empty($chunk) && isset($chunk)) {
            return $string . $chunk;
        } else {
            return $string;
        }
    }

    private function add_wish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // boolean if user has less than 3 wishes
            $canAddWish = $this->wishRepo->canAddWish($_SESSION["user"]->email);

            // check if user has 3 wishes
            if (!$canAddWish) {
                render("addWish.tpl", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $this->title = $_POST["title"];
            $this->description = $_POST["description"];
            $this->tag = $_POST["tag"];

            $firstcharOfTag = substr($this->tag, 0, 1);

            if ($firstcharOfTag != "#") {
                $tag = "#";
                $tag .= $this->tag;
                $this->tag = $tag;
            }

            $size = strlen($this->gethashtags($this->tag));

            // check if input of form is not null
            if (Empty($this->title)
                || Empty($this->description)
                || Empty($this->tag) || $size == 0
            ) {
                render("addWish.tpl", ["error" => "Vul AUB alles in", "wishtitle" => $this->title,
                    "description" => $this->description,"tag" => $this->tag, /*"edit" => "isset"*/]);
                exit(1);
            }


            $myWishes = $this->wishRepo->getMyWishes();

            foreach ($myWishes as $item){
                if($item instanceof Wish){
                    similar_text($item->title, $this->title, $percent);

                    /* Check the percentage of the matches between the title */ 
                    if($percent > 80){
                        render("addWish.tpl", ["error" => "U heeft al een wens met een soort gelijke titel.", "wishtitle" => $this->title,
                            "description" => $this->description,"tag" => $this->tag, /*"edit" => "isset"*/]);
                        exit(1);
                        break;
                    }
                    
                }
            }

            $allTags = $this->gethashtags($this->tag);
            $myArray = explode(',', $allTags);
            $new_array = array_map('ucfirst', $myArray);

            // create an array with the wish
            $newWish = array();
            $newWish["title"] = $this->title;
            $newWish["description"] = $this->description;
            $newWish["tag"] = $new_array;

            // send the array to the repository to add to the database
            $this->wishRepo->addWish($newWish);

            $this->currentPage = "mywishes";
            $this->go_back();
        }
    }


    private function getSpecificwish($id, $previousPage)
    {
        $selectedWish = $this->wishRepo->getWish($id);
        render("wishSpecificView.tpl",
            ["title" => "Wens: " . $id, "selectedWish" => $selectedWish, "previousPage" => $previousPage]);
        exit(0);
    }

    private function requestMatch($id)
    {
        apologize($id);
    }

    private function edit_wish()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $_POST["tag"];

            $firstcharOfTag = substr($tag, 0, 1);

            if ($firstcharOfTag != "#") {
                $tempTag = "#";
                $tempTag .= $tag;
                $tag = $tempTag;
            }

            $valid = true;
            $validTag = true;

            if (!Empty($title)) {
                $this->title = $title;
            } else {
                $valid = false;
            }
            if (!Empty($description)) {
                $this->description = $description;
            } else {
                $valid = false;
            }
            if (!Empty($tag)) {
                $this->tag = $tag;
                if (strlen($this->gethashtags($this->tag)) == 0) {
                    $validTag = false;
                }
            } else {
                $validTag = false;
            }

            $tagErrorMessage = "Ongelidige tag #";
            if (!$validTag) {
                render("addWish.tpl", ["error" => "vul AUB alles in!", "wishtitle" => $this->title,
                    "description" => $this->description, "tag" => $this->tag, "tagerror" => $tagErrorMessage, "edit" => "isset"]);
                exit(1);
            }

            if (!$valid) {
                render("addWish.tpl", ["error" => "vul AUB alles in!", "wishtitle" => $this->title,
                    "description" => $this->description, "tag" => $this->tag, "edit" => "isset"]);
                exit(1);
            }


            $allTags = $this->gethashtags($this->tag);
            $myArray = explode(',', $allTags);
            $new_array = array_map('ucfirst', $myArray);


            // create an array with the wish
            $editWish = array();
            $editWish["title"] = $this->title;
            $editWish["description"] = $this->description;
            $editWish["tag"] = $new_array;

            if (isset($_SESSION["wishcontentid"])) {
                $id = $_SESSION["wishcontentid"];
                $this->wishRepo->wishContentQuery($editWish, $id);

                $head = "Beste, \n\n";
                $msg = "Uw wensweiziging is ingedient, uw wens zal na goedkeuring zichtbaar zijn voor anderen, we houden u hiervan nog op de hoogte.\n\n";
                $wish = "Uw nieuwe wens is als volgt: \n";
                $wishName = "Naam van de wens: \t\t" . $this->title . " \n";
                $wishDescription = "Beschrijving van de wens: \t" . $this->description . "\n";
                $allTagsForMail = implode(' #', $new_array);
                $wishTags = "Uw tags zijn: \t\t\t\t#" . $allTagsForMail . "\n\n";
                $end = "Vriendelijke groeten, \n\n Alladin";

                $message = $head . $msg . $wish . $wishName . $wishDescription . $wishTags . $end;

                $mail = new Email();
                $mail->fromName = "Alladin";
                $mail->subject = "Wens is gewijzigd";
                $mail->message = $message;
                $mail->to = $_SESSION["user"]->email;
                $mail->sendMail();

                $newmail = new messageRepository();
                $msgID = $newmail->sendMessage("Admin", $mail->to, $mail->subject, $mail->message);
                $newmail->setLink($id, "Wens", $msgID);
            }

            $this->go_back();
        }
    }


    private function go_back()
    {
        guaranteeProfile();
        $this->renderOverview("myWishes");
    }

    private function remove()
    {
        $id = $_GET["wishID"];
        if (isset($id)) {
            $this->wishRepo->DeleteWish($id);
        }
        $this->currentPage = "mywishes";
        $this->go_back();
    }

    function gethashtags($text)
    {
        //Match the hashtags
        preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $text, $matchedHashtags);
        $hashtag = '';
        // For each hashtag, strip all characters but alpha numeric
        if (!empty($matchedHashtags[0])) {
            foreach ($matchedHashtags[0] as $match) {
                $hashtag .= preg_replace("/[^a-z0-9]+/i", "", $match) . ',';
            }
        }
        //to remove last comma in a string
        return rtrim($hashtag, ',');
    }

    public function back()
    {
        if(!empty($this->getCurrent())){
            redirect("/wishes/show=" . $this->getCurrent());
            exit(0);
        }

        redirect("/wishes");
        exit(0);
    }

    public function setCurrent($page)
    {
        $_SESSION["current"] = $page;
    }

    public function getCurrent()
    {
        return $_SESSION["current"];
    }


}
