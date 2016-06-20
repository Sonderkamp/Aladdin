<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 20/06/2016
 * Time: 17:10
 */
class WishCreationController extends Controller
{
    private $wishRepo, $userRepo, $talentRepo, $forbiddenWordRepo, $wishController;

    public function __construct(WishesController $wish)
    {
        $this->wishRepo = new WishRepository();
        $this->userRepo = new UserRepository();
        $this->talentRepo = new TalentRepository();
        $this->forbiddenWordRepo = new ForbiddenWordRepository();
        $this->wishController = $wish;
    }

    /**
     * Open corresponding view based on $open param
     */
    public function openWishView($open)
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        if ($open) {
            // Check if users has 3 wishes, true if wishes are [<] 3
            $canAddWish = $this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email);
            if (!$canAddWish) {
                $this->wishController->back();
                exit(1);
            }

            $this->render("addWish.tpl", ["title" => "Wens toevoegen"]);

        } else {
            $wishContentId = $_GET["Id"];
            $_SESSION["wishcontentid"] = $_GET["Id"];

            $wish = $this->wishRepo->getWish($wishContentId);

            $title = $wish->title;
            $description = $wish->content;
            $tempTag = $this->talentRepo->getWishTalents($wish);

            $returnArray = array();
            foreach ($tempTag as $item) {
                if ($item instanceof Talent) {
                    $returnArray[] = $item->name;
                }
            }

            $tag = $this->prepend("#", implode(" #", $returnArray));

            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "edit" => "isset", "tag" => $tag, "previousPage"]);
        }
    }


    /**
     * @param $string
     * @param $chunk
     * @return string
     * ?
     */
    private function prepend($string, $chunk)
    {
        if (!empty($chunk) && isset($chunk)) {
            return $string . $chunk;
        } else {
            return $string;
        }
    }


    /**
     * add's wish to database
     */
    public function addWish()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // check if user can add a wish
            if (!($this->wishRepo->canAddWish($this->userRepo->getCurrentUser()->email))) {
                $this->render("addWish.tpl", ["wishError" => "U heeft al 3 wensen, u kunt geen wensen meer toevoegen."]);
                exit(1);
            }

            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $this->validateWish($title, $description, $tag, $returnVal);

            if ($returnVal === 0) {
                $myTags = array_map('ucfirst', explode(',', $this->getHashTags($tag)));

                $wish = new Wish();
                $wish->title = $title;
                $wish->content = $description;
                $wish->tags = $myTags;
                $this->wishRepo->addWish($wish);

                $this->wishController->back();
            }
        }
    }

    /** edit's wish */
    public function editWish()
    {
        (new AccountController())->guaranteeLogin("/Wishes");
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tag = $this->addHashTag($_POST["tag"]);

            $message = "Ongelidige tag #";
            if (strlen($this->getHashTags($tag)) == 0) {
                $this->renderWishView($title, $description, $tag, $message);
            }

            $tempContent = preg_replace('/\s+/', '', $description);
            $tempTitle = preg_replace('/\s+/', '', $title);

            $this->validateWish($tempTitle, $tempContent, $tag, $returnVal, true);

            if ($returnVal === 1) {
                $this->renderWishView($title, $description, $tag);
            } else if ($returnVal === 3) {
                $this->renderWishView($title, $description, $tag, "U heeft verboden woorden in uw wens staan!", null, true);
            }

            // set a comma , between the tags.
            $myTags = array_map('ucfirst', explode(',', $this->getHashTags($tag)));

            // create a wish
            $wish = new Wish();
            $wish->title = $title;
            $wish->content = $description;
            $wish->tags = $myTags;

            if (isset($_SESSION["wishcontentid"])) {
                $wish->id = $_SESSION["wishcontentid"];
                $this->wishRepo->editWishContent($wish);

//                /* uitgecomment anders wordt je volgespamt
//                $this->wishRepo->sendEditMail($wish->id, $title, $description, $myTags);
//                */
            }
            $this->wishController->back();
        }
    }

    /** checks if wish is valid, returns number, 0 is valid.
     * @param $title = wish title
     * @param $content = wish content
     * @param $tag = wish tag's
     * @param &$returnVal = reference variable
     * @param $edit , set if editing a wish
     * @return number
     */
    private function validateWish($title, $content, $tag, &$returnVal, $edit = null)
    {
        $input = array([$title, $content, $tag]);
        $size = strlen($this->getHashTags($tag));
        $tempContent = preg_replace('/\s+/', '', $content);
        $tempTitle = preg_replace('/\s+/', '', $title);

        $returnVal = 0;

        if (!$this->isValid($input) || (strlen($tempTitle) === 0) || strlen($tempContent) === 0 || ($size == 0)) {
            if (isset($edit)) {
                $returnVal = 1;
                return;
            }
            $this->renderWishView($title, $content, $tag, "Vul aub alles in.", true);
        }

        if (!isset($edit)) {
            $myWishes = $this->wishRepo->getMyWishes();
            if ($this->hasSameWish($myWishes, $title)) {
                $this->renderWishView($title, $content, $tag, "U heeft al een wens met een soortgelijke titel.", true);
            }
        }

        if ($this->inForbiddenWords($title, $tempContent, $tag)) {
            if (isset($edit)) {
                $returnVal = 3;
                return;
            }
            $this->renderWishView($title, $content, $tag, "U heeft verboden woorden in uw wens staan!", true);
        }
    }

    /** check if user has a wish with the same title
     * @param $wishes = all wishes of user
     * @param $title = title to check
     * @return true if title is similar for more then 80%
     */
    private function hasSameWish($wishes, $title)
    {
        if (count($wishes) > 1) {
            foreach ($wishes as $item) {
                if ($item instanceof Wish) {
                    similar_text($item->title, $title, $percent);
                    if ($percent > 80) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function inForbiddenWords($title, $content, $tag)
    {
        $forbiddenWords = $this->forbiddenWordRepo->getForbiddenWords();
        $tag = str_replace("#", "", $tag);

        foreach ($forbiddenWords as $word) {
            if (strpos($title, $word) !== FALSE) {
                return true;
            }
            if (strpos($content, $word) !== FALSE) {
                return true;
            }
            if (strpos($tag, $word) !== FALSE) {
                return true;
            }
        }
        return false;
    }


    /** check if there are empty values in an array
     * @param $array = the array to check
     * @return true if there are no empty values
     */
    private function isValid($array)
    {
        foreach ($array as $item) {
            if (empty($item)) {
                return false;
            }
        }
        return true;
    }


    /**
     * //    ** renders to edit page
     * //     * @param $title = title of the wish
     * //     * @param $description = content of the wish
     * //     * @param $tag = the tag's of the wish
     * //     * @param $message = to show
     * //     * @param $add (optional), set if users want to add a wish
     * //     * @param $edit (optional), set if users want to edit a wish
     */
    private function renderWishView($title, $description, $tag, $message = null, $add = null, $edit = null)
    {

        if (isset($add)) {
            $this->render("addWish.tpl", ["wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message]);
            exit();
        }

        if (isset($edit)) {
            $this->render("addWish.tpl", ["error" => $message, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
            exit();
        }

        $error = "Vul aub alles in!";

        if (isset($message)) {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "tagerror" => $message, "edit" => "isset"]);
        } else {
            $this->render("addWish.tpl", ["error" => $error, "wishtitle" => $title,
                "description" => $description, "tag" => $tag, "edit" => "isset"]);
        }

        exit();
    }


    /** adds hashtags to a string with spaces
     * @return string with hashtags
     */
    private function addHashTag($string)
    {
        if (substr($string, 0, 1) != "#") {
            $tempTag = "#";
            $tempTag .= $string;
            return $tempTag;
        } else {
            return $string;
        }
    }

    /**
     * @param $text
     * @return string
     */
    private function getHashTags($text)
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
}