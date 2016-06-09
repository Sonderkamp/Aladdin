<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 25-4-2016
 * Time: 12:11
 */
class ForbiddenwordsController extends Controller
{
    // Instant variables
    private $wordRepo, $words, $error, $success, $wordsCount, $page, $search, $talentRepo;

    public function __construct()
    {

        // Guarentee if an admin is logged in
        (new AdminController())->guaranteeAdmin("/forbiddenwords");

        // Set the wordRepo
        $this->wordRepo = new ForbiddenWordRepository();
        $this->talentRepo = new TalentRepository();

        // wordsCount is the number of words in total devided by 10 and rounded upwards.
        // page is the current page of the pagination. if page is 2 then show words 10 to 20
        $this->wordsCount = ceil(count($this->wordRepo->getForbiddenWords()) / 10);
    }

    public function run()
    {
        // Check the session variables
        $this->checkPaginationSession();
        // Check if a post method is sent
        $this->checkPaginationStatus();
        // Set the words variable
        $this->setWords();

        // Render the .tpl
        $this->render("Admin/forbiddenWord.tpl",
            ["title" => "Verboden woorden",
                "forbiddenWords" => $this->words,
                "errorMessage" => $this->error,
                "successMessage" => $this->success,
                "wordsCount" => $this->wordsCount,
                "page" => $this->page,
                "search" => $this->search]);
        // Exit with succes status
        exit(0);
    }

    public function addWord()
    {

        if (!Empty($_GET["newWord"])) {
            // Secure the value sent by get
            $word = htmlspecialchars(trim($_GET["newWord"]));

            // Set succes succeeded or failed depending on the result of checkWord
            $success = $this->checkWord($word);

            // if $succes is succeeded create the word.
            if ($success == "succeeded") {

                $talent = $this->talentRepo->searchTalents($word, null, null, true);
                if (!empty($talent)) {
                    $this->talentRepo->permanentDeleteTalent($talent[0]->id);
                }
                // Set the word in the database
                $this->wordRepo->createForbiddenWord($word);
                // Create a succes message
                $this->success = 'Het woord "' . $word . '" is succesvol toegevoegd!';
            }
        }

        $this->run();
    }

    public function removeWord()
    {

        if (!Empty($_GET["word"])) {
            // Secure the value sent by get
            $word = htmlentities(trim($_GET["word"]), ENT_QUOTES);

            // Delete the word from the database
            $this->wordRepo->deleteForbiddenWord($word);

            // Check if the word cannot be received from the database
            // ELSE set error message
            if (Empty($this->wordRepo->getForbiddenWord($word))) {

                // Set the succes message
                $this->success = 'Het woord "' . $word . '" is succesvol verwijderd!';
            } else {

                // Set the error message
                $this->error = 'Het woord "' . $word . '" is niet verwijderd!';
            }
        }

        $this->run();
    }

    public function editWord()
    {

        if (!Empty($_GET["editedWord"]) && !Empty($_GET["oldWord"])) {

            // Secure the edited word sent by POST
            $newWord = htmlentities(trim($_GET["editedWord"]), ENT_QUOTES);
            // Secure the old word sent by POST
            $oldWord = htmlentities(trim($_GET["oldWord"]), ENT_QUOTES);

            // Set succes succeeded or failed depending on the result of checkWord
            $succes = $this->checkWord($newWord);

            // if $succes is succeeded than continue
            // ELSE set error message
            if ($succes == "succeeded") {

                // Update the word in the database
                $this->wordRepo->updateForbiddenWord($oldWord, $newWord);
                // Set the succes message
                $this->success = 'Het woord "' . $oldWord . '" is succesvol gewijzigd naar "' . $newWord . '"!';
            } else {

                // Set the error message
                $this->success .= ' Het woord "' . $oldWord . '" blijft ongewijzigd!';
            }
        }

        $this->run();
    }

    private function checkPaginationStatus()
    {

        // Check if request method of the server is POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Check if the request method sent is for pagination
            if (!Empty($_POST["pagination"])) {

                // If the method sent equals off than turn the pagination off
                // ELSE turn the pagination on
                if ($_POST["pagination"] == "off") {

                    // Set pagination off
                    $_SESSION["wordsPagination"] = "off";
                } else {

                    // Set pagination on
                    $_SESSION["wordsPagination"] = "on";
                }

                // Reload page without post requests
                if (!Empty($_POST["page"])) {
                    $this->redirect("/forbiddenwords/wordsPage=" . htmlspecialchars(trim($_POST["page"])));
                } else {
                    $this->redirect("/forbiddenwords/wordsPage=1");
                }
            }
        }
    }

    private function setWords()
    {

        // Check if the request method of the server is GET
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            // Check search is set and not empty
            if (!Empty($_GET["search"])) {

                // Make $search secure
                $this->search = htmlentities(trim($_GET["search"]), ENT_QUOTES);

                // Change wordsCount to the words that fit the search criteria
                $this->wordsCount = ceil(count($this->wordRepo->getForbiddenWords(null, $this->search)) / 10);
            } else {

                // Set search null, because of optional variables.
                $this->search = null;
            }

            // If pagination is not switched off, than continue
            // ELSE load all the words
            if ($_SESSION["wordsPagination"] != "off") {

                // Check if a page is requested
                // ELSE get the first page
                if (!Empty($_GET["wordsPage"])) {

                    // Check if the requested page is higher than zero and the same or under the maximum word count.
                    // ELSE get the first page
                    if ($_GET["wordsPage"] > 0 && $_GET["wordsPage"] <= $this->wordsCount) {

                        // Fill words with the requested page and if isset search
                        $this->words = $this->wordRepo->getForbiddenWords($_GET["wordsPage"], $this->search);

                        // Set the page to load in the .tpl
                        $this->page = $_GET["wordsPage"];
                    } else {

                        // Fill words with the first page an if isset search
                        $this->words = $this->wordRepo->getForbiddenWords(1, $this->search);
                        // Set the page to load in the .tpl
                        $this->page = 1;
                    }
                } else {

                    // Fill words with the first page an if isset search
                    $this->words = $this->wordRepo->getForbiddenWords(1, $this->search);
                    // Set the page to load in the .tpl
                    $this->page = 1;
                }
            } else {

                // Fill words with all the words and if isset search
                $this->words = $this->wordRepo->getForbiddenWords(null, $this->search);
            }
        }
    }

    private function checkPaginationSession()
    {
        // Check if the pagination is set or not. If the variable isn't declared yet then set it.
        if (Empty($_SESSION["wordsPagination"])) {

            // Turn pagination on
            $_SESSION["wordsPagination"] = "on";
        }
    }

    private function checkWord($forbiddenWord)
    {

        // Set success as succeeded. It will only be changed if adding will result in failure.
        $success = "succeeded";

        // Loop through all the words
        foreach ($this->wordRepo->getForbiddenWords() as $word) {

            // Check if the word for adding and the word from the loop are the same
            if (strtolower($word) == strtolower($forbiddenWord)) {

                // Set succes on failed.
                $success = "failed";
                // Set error message to show in the .tpl
                $this->error = 'Het woord "' . $forbiddenWord . '" bestaat al!';

                // Break free from the loop.
                break;
            }
        }

        // Check on whitespace
        if(preg_match('/\s/', $forbiddenWord)) {
            $this->error .= ' Het woord "' . $forbiddenWord . '" bevat spaties!';

            // Set succes on failed.
            $success = "failed";
        }

        // Check if the maximum length of 150 isn't exceeded.
        if (strlen($forbiddenWord) > 150) {

            // Set succes on failed.
            $success = "failed";
            // Add to the error message to show in the .tpl
            $this->error .= ' Het woord "' . $forbiddenWord . '" is ' . (strlen($forbiddenWord) - 150) . ' karakters te lang! Het woord mag maar 150 lang zijn!';
        }

        // Return succeeded or failed.
        return $success;
    }
}