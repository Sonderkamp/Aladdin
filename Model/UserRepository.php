<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 26-04-16
 * Time: 16:39
 */
class UserRepository
{

    private $UserQueryBuilder;

    function __construct()
    {
        $this->UserQueryBuilder = new UserQueryBuilder();
    }

    public function getUser($emailOrDisplayName)
    {
        return $this->userCreator($this->UserQueryBuilder->getUser($emailOrDisplayName));
    }

    public function blockUser($username, $reason = null)
    {
        $current = $this->getCurrentUser();

        if ($current === false || $current->email !== $username) {

            // if user is not blocked
            if (!$this->isBlocked($username) !== false)
                $this->UserQueryBuilder->setblock(1, $username, $reason);
        }
    }

    public function unblockUser($username, $reason = null)
    {
        $current = $this->getCurrentUser();

        if ($current === false || $current->email !== $username) {

            // if user is blocked
            if ($this->isBlocked($username) !== false)
                $this->UserQueryBuilder->setblock(0, $username, $reason);
        }

    }


    public function validate($username, $password)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            $res = $this->getUser($username);

            if ($res === false || $res === null) {
                return false;
            } else if ($res->checkPassword($password)) {
                $_SESSION["user"] = $res;
                return true;
            }
        }
        return false;
    }


    public function isBlocked($username)
    {
        return $this->UserQueryBuilder->isBlocked($username);
    }


    public function getAllBlocks($user)
    {
        return $this->UserQueryBuilder->getAllBlocks($user);
    }


    public function resetHash($username)
    {
        if ($this->validateUsername($username)) {
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            $this->UserQueryBuilder->clearToken($username, "recovery");

        }
    }

    public function CanRecover()
    {
        return $this->UserQueryBuilder->IPlog("check");
    }

    public function logRecovery()
    {
        return $this->UserQueryBuilder->IPlog();
    }

    public function validateToken($token)
    {
        $res = $this->UserQueryBuilder->getMailByToken($token, "recovery");

        if ($res == null)
            return false;
        if ($this->hoursPassed($res[0]["RecoveryDate"]) >= 24)
            return false;

        return $res[0]["Email"];
    }

    public function setActivateMail($mail, $username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        $val = $this->getUser($username);
        if ($val === false || $val === null)
            return false;

        $mail->to = $username;
        $mail->toName = $val->name . " " . $val->surname;
        $mail->subject = "Activeer Account Aladdin";
        $mail->message =
            "Beste " . $val->name . ",\n
            Deze mail is verstuurd omdat u een nieuw account aan heeft gemaakt.\n
            Om uw account te activeren, ga naar deze link:\n
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=activate/token=" . $this->UserQueryBuilder->getTokenByName($username, "validation")["ValidationHash"] . "\n

            Met vriendelijke groet,\n
            Aladdin";
        return true;

    }

    public function validateActivateToken($token)
    {

        $res = $this->UserQueryBuilder->getMailByToken($token, "validation");
        if ($res == null || $res === false)
            return false;
        $res = $res[0];

        // Clear
        $this->UserQueryBuilder->clearToken($res["Email"], "validation");


        return $res["Email"];
    }

    public function updateUser($user)
    {

        if ($this->validateUserObject($user) === false) {
            return "Validatie mislukt. check uw gegevens. Voor interactieve validatie, zet uw javascipt aan.";
        }

        $us = $this->getUser($user->email);
        if (!(strtolower($us->initials) == strtolower($user->initials) && strtolower($us->surname) == strtolower($user->surname)) && empty($us->companyName)) {
            $arr = [];
            $arr["initial"] = $user->initials;
            $arr["surname"] = $user->surname;
            $arr["dob"] = $user->dob;

            // create array
            $user->displayName = $this->createDislay($arr);
        }

        //save changes
        $this->UserQueryBuilder->saveUser($user);
        if ($user->email === $_SESSION["user"]->email) {
            // Update
            $_SESSION["user"] = $this->getUser($user->email);
        }

        return null;
    }

    public function validateUser($array)
    {

        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["dob"] = trim($array["dob"]);
        $array["initial"] = strtoupper(trim($array["initial"]));
        $array["gender"] = strtolower(trim($array["gender"]));

        // USERNAME
        // valid email
        // NAME
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["name"]) == false)
            return false;


        // SURNAME
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["surname"]) == false)
            return false;

        // ADDRESS
        if (preg_match("/^[a-zA-Z][A-Za-z0-9\\- ]+$/", $array["address"]) == false)
            return false;


        // INITIALS
        // data-validation-regexp="^([a-zA-Z\.]+)$"
        if (preg_match("/^([a-zA-Z\.]+)$/", $array["initial"]) == false)
            return false;

        // DOB

        $d = DateTime::createFromFormat('d-m-Y', $array["dob"]);
        if (($d && $d->format('d-m-Y') == $array["dob"]) === false)
            return false;

        // GENDER
        if ($array["gender"] != "male" && $array["gender"] != "female" && $array["gender"] != "other")
            return false;

        if (is_numeric($array["Lat"]) == false)
            return false;
        if (is_numeric($array["Lon"]) == false)
            return false;
        // is number lat lon
        return true;

    }

    public function validateUserObject($user)
    {

        // USERNAME
        // valid email
        // NAME
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $user->name) == false)
            return false;

        // SURNAME
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $user->surname) == false)
            return false;

        // ADDRESS
        if (preg_match("/^[a-zA-Z][A-Za-z0-9\\- ]+$/", $user->address) == false)
            return false;


        // INITIALS
        if (preg_match("/^([a-zA-Z\.]+)$/", $user->initials) == false)
            return false;

        if (is_numeric($user->lat) == false)
            return false;
        if (is_numeric($user->lon) == false)
            return false;
        // is number lat lon
        return true;

    }


    public function validateCompany($array)
    {
//        var_dump($array["dob"]);
        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["initial"] = strtoupper(trim($array["initial"]));

        // USERNAME
        // valid email
        // NAME
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["name"]) == false)
            return false;


        // SURNAME
        //[a-zA-Z][a-zA-Z ]+$
        if (preg_match("/^[a-zA-Z][A-Za-z\\- ]+$/", $array["surname"]) == false)
            return false;

        // ADDRESS
        if (preg_match("/^[a-zA-Z][A-Za-z0-9\\- ]+$/", $array["address"]) == false)
            return false;


        // INITIALS
        // data-validation-regexp="^([a-zA-Z\.]+)$"
        if (preg_match("/^([a-zA-Z\.]+)$/", $array["initial"]) == false)
            return false;


        if (is_numeric($array["Lat"]) == false)
            return false;
        if (is_numeric($array["Lon"]) == false)
            return false;
        // is number lat lon
        return true;

    }

    private function hoursPassed($time)
    {
        if ($time == null) {
            return 0;
        }

        $date1 = new DateTime();
        $date2 = new DateTime($time);

        $diff = $date2->diff($date1);
        $diff = ($diff->y * 12 * 30 * 24) + ($diff->m * 30 * 24) + ($diff->d * 24) + ($diff->h);
        return $diff;

    }

    public function checkUsernameJSON($username)
    {
        header('Content-Type: application/json');
        if (!Empty($username)) {
            // htmlspecialchar

            if ($this->getUser($username) !== null) {

                echo json_encode(array('result' => true));
                exit();
            }
            echo json_encode(array('result' => false));
            exit();
        }
        echo json_encode(array('result' => false));
    }

    public function setRecoveryMail($mail, $username, &$websiteMessage)
    {


        if ($this->validateUsername($username)) {
            // getName
            $val = $this->getUser($username);
            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            // Get
            $mail->to = $username;
            $mail->toName = $val->name . " " . $val->surname;
            $mail->subject = "Wachtwoord vergeten Aladdin";
            $mail->message =
                "Beste " . $val->name . ",\n
            Deze mail is verstuurd omdat u uw wachtwoord vergeten bent.\n
            Om een nieuw wachtwoord in te stellen, ga naar deze link:\n
            http://" . $_SERVER["SERVER_NAME"] . "/account/action=recover/token=" . $val->RecoveryHash . "\n
            Deze link is 24 uur geldig \n

            Met vriendelijke groet,\n
            Aladdin";

            $websiteMessage = "Er is een email verstuurd naar " . $username .
                "met een link om uw wachtwoord te resetten.Deze link verschijnt binnen drie minuten.
                                als u niks binnenkrijgt, kijk alstublieft in uw spam folder.";

            return true;
        } else {
            return false;
        }
    }

    public function recover($username)
    {

        if ((Empty($_POST["username"])
            || !$this->validateUsername($_POST["username"])
            || ($username != $_POST["username"]))
        ) {
            return "Invalid form.";
        }

        // check passwords
        if (Empty($_POST["password1"]) || Empty($_POST["password2"])) {
            return "Niet alles ingevuld.";
        }
        if ($_POST["password1"] != $_POST["password2"]) {
            return "Wachtwoorden komen niet overeen.";
        }

        // save password
        if (!$this->newPassword($_POST["username"], $_POST["password1"])) {
            return "Wachtwoord moet minimaal 8 tekens lang zijn en
                        een hoofdletter, een kleine letter, een nummer bevatten.";

        }
        return true;
    }


    public function newHash($username)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
        if ($this->validateUsername($username)) {

            $res = $this->getUser($username);
            if ($res === false || $res === null)
                return false;

            if ($res->RecoveryHash == null || $this->hoursPassed($res->RecoveryDate) >= 24) {
                return $this->UserQueryBuilder->setToken($username, $token, "recovery");
            }

        }
        return false;
    }


    public function tryRegister($array)
    {


        if ($array["type"] != "business" && $array["type"] != "child") {

            if (empty($array["dob"])) {
                return "Niet alles ingevuld";
            }

            $age = DateTime::createFromFormat('d-m-Y', $array["dob"]);
            $to = new DateTime('today');
            $age = $age->diff($to)->y;
            if ($age < 18) {
                return "Je moet minimaal 18 jaar oud zijn. Ben je jonger? Registreer je als een kind.";
            }
        }
        $method = "tryRegister" . $array["type"];
        return $this->$method($array);
    }

    private function tryRegisteradult($array)
    {


        if (Empty($array["username"])
            || Empty($array["password"])
            || Empty($array["name"])
            || Empty($array["surname"])
            || Empty($array["address"])
            || Empty($array["postalcode"])
            || Empty($array["country"])
            || Empty($array["city"])
            || Empty($array["dob"])
            || Empty($array["initial"])
            || Empty($array["gender"])
            || Empty($array["Lat"])
            || Empty($array["Lon"])
            || !isset($array["handicap"])
        ) {
            return "Niet alles is ingevuld.";
        }

        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["dob"] = trim($array["dob"]);
        $array["initial"] = strtoupper(trim($array["initial"]));
        $array["gender"] = strtolower(trim($array["gender"]));
        $array["initial"] = trim($array["initial"], '.');
        $array["username"] = strtolower(filter_var($array["username"], FILTER_SANITIZE_EMAIL));

        if (!$this->validPass($array["password"])) {
            return "het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en
            een nummer bevatten.";
        }
        if (!preg_match("/^[A-Za-z\\- ]+$/", $array["name"]) || !preg_match("/^[A-Za-z\\- ]+$/", $array["surname"])) {
            return "Naam mag alleen alphabetische characters, spaties en streepjes(-) bevatten.";
        }

        if ($this->getUser($array["username"]) !== null) {
            return "Dit emailadress heeft al een account.";
        }


        $displayname = $this->createDislay($array);
        $array["postalcode"] = preg_replace('/\s+/', '', $array["postalcode"]);


        if ($this->validateUser($array) === false) {
            return "Validatie mislukt. check uw gegevens. Voor interactieve validatie, zet uw javascipt aan.";
        }

        $d = DateTime::createFromFormat('d-m-Y', $array["dob"]);


        // SQL
        $hashed = password_hash($array["password"], PASSWORD_DEFAULT);
        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $ret = $this->UserQueryBuilder->addUser(array(strtolower($array["username"]), $hashed, strtolower($array["name"]),
            $array["surname"], $token, $array["address"],
            $array["postalcode"], $array["country"], $array["city"],
            $d->format('Y-m-d'), $array["gender"], $array["handicap"], $displayname, $array["initial"], $array["Lat"], $array["Lon"], $array["handicap_info"]));

        if ($ret === false)
            return "Er was een error bij het toevoegen van uw gegevens aan onze database. Probeer dit alstublieft opnieuw.";

        return true;
    }

    private
    function tryRegisterchild($array)
    {

        $guardian = $this->getUser($array["guardian"]);
        if ($guardian == null) {
            return "Voogd emailadres bestaat niet.";
        };

        if ($guardian->dob == null) {
            return "Voogd moet een persoon zijn.";
        }

        $age = strtotime($guardian->dob) / 60 / 60 / 24 / 365;

        if ($age < 18) {
            return "Voogd moet minimaal 18 jaar oud zijn.";
        }

        $res = $this->tryRegisteradult($array);

        if ($res === true) {

            // set guardian
            $this->UserQueryBuilder->setGuardian($array["username"], $guardian->email);

            $messageRepo = new MessageRepository();
            $kid = $this->getUser($array["username"]);

            $message = "Beste " . $guardian->name . ", \n\n De gebruiker: " . $kid->name . " " . $kid->surname
                . " heeft zich aangemeld met u als voogd."
                . " Als voogd heeft u toegang tot uw kind zijn account. "
                . "Omdat wij het wachtwoord van gebruikers niet opslaan, is dit het enige mailtje waar wij deze aan u kunnen geven.\n\n"
                . "Gebruikersnaam: " . $kid->email . "\nWachtwoord: "
                . $array["password"] . "\n \n "
                . "Wij hopen u hiermee voldoende te hebben geinformeerd. "
                . "Indien u niet de voogd bent van deze gebruiker, dan kan U hem rapporteren via dit bericht op de website.";
            $messageRepo->sendMessage($kid->email, $guardian->email, "Iemand heeft u als voogd aangegeven", $message);

            // Remove from inbox kid
            $messageRepo->deleteMessagesUser($kid->email);
        }

        return $res;

    }

    private
    function tryRegisterelder($array)
    {

        // heeft niks anders dan een volwassenen.
        return $this->tryRegisteradult($array);
    }

    private
    function tryRegisterdisabled($array)
    {
        $array["handicap"] = 1;
        $this->tryRegisteradult($array);
        exit();
    }

    private
    function tryRegisterbusiness($array)
    {


        if (Empty($array["username"])
            || Empty($array["password"])
            || Empty($array["name"])
            || Empty($array["surname"])
            || Empty($array["address"])
            || Empty($array["postalcode"])
            || Empty($array["country"])
            || Empty($array["city"])
            || Empty($array["initial"])
            || Empty($array["Lat"])
            || Empty($array["Lon"])
            || Empty($array["companyName"])
        ) {
            return "Niet alles is ingevuld.";
        }

        $array["username"] = strtolower(trim($array["username"]));
        $array["name"] = strtolower(trim($array["name"]));
        $array["surname"] = trim($array["surname"]);
        $array["address"] = strtolower(trim($array["address"]));
        $array["postalcode"] = strtoupper(trim($array["postalcode"]));
        $array["country"] = strtolower(trim($array["country"]));
        $array["city"] = strtolower(trim($array["city"]));
        $array["initial"] = strtoupper(trim($array["initial"]));
        $array["initial"] = trim($array["initial"], '.');
        $array["username"] = strtolower(filter_var($array["username"], FILTER_SANITIZE_EMAIL));
        $array["companyName"] = strtolower(trim($array["companyName"]));

        if (!preg_match("/^([0-9a-zA-Z][A-Za-z0-9\- ]+)$/", $array["companyName"])) {
            return "Illegale characters in bedrijfsnaam";
        }

        if (!$this->validPass($array["password"])) {
            return "het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en
            een nummer bevatten.";
        }
        if (!preg_match("/^[A-Za-z\\- ]+$/", $array["name"]) || !preg_match("/^[A-Za-z\\- ]+$/", $array["surname"])) {
            return "Contactpersoon naam mag alleen alphabetische characters, spaties en streepjes(-) bevatten.";
        }

        if ($this->getUser($array["username"]) !== null) {
            return "Dit emailadress heeft al een account.";
        }

        $array["postalcode"] = preg_replace('/\s+/', '', $array["postalcode"]);


        $displayName = $this->createDislayCompany($array);

        if ($this->validateCompany($array) === false) {
            return "Validatie mislukt. check uw gegevens. Voor interactieve validatie, zet uw javascipt aan.";
        }

        // SQL
        $hashed = password_hash($array["password"], PASSWORD_DEFAULT);
        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $ret = $this->UserQueryBuilder->addCompany(array(strtolower($array["username"]), $hashed, strtolower($array["name"]),
            $array["surname"], $token, $array["address"],
            $array["postalcode"], $array["country"], $array["city"],
            $displayName, $array["initial"], $array["Lat"], $array["Lon"], $array["companyName"]));

        if ($ret === false)
            return "Er was een error bij het toevoegen van uw gegevens aan onze database. Probeer dit alstublieft opnieuw.";

        return true;
    }

    public
    function login()
    {
        if (!Empty($_POST["username"]) && !Empty($_POST["password"])) {

            if ($this->validate(htmlspecialchars($_POST["username"]), htmlspecialchars($_POST["password"]))) {

                if ($this->isBlocked($_POST["username"]) !== false) {
                    $_SESSION["user"] = null;
                    return "gebruiker is geblokkeerd. Reden: " . htmlspecialcharsWithNL($this->isBlocked($_POST["username"]));
                }
                return true;
            }
            return "gebruikersnaam/wachtwoord combinatie is niet geldig";

        }
        return "Niet alle gegevens zijn ingevuld";
    }

    public
    function newRecover($username, &$websiteMessage)
    {
        if (!$this->validateUsername($_POST["username"])) {
            $this->recoverError("Invalid username");
        }

        if ($this->newHash($_POST["username"])) {
            $mailer = new Email();

            if ($this->setRecoveryMail($mailer, $_POST["username"], $websiteMessage)) {
                $mailer->sendMail();
                return true;

            } else {
                $this->recoverError("Email send error.");
            }
        }
        return "deze gebruiker heeft afgelopen 24 uur al een recovery aangevraagd.";
    }

    public
    function createDislay($arr)
    {

        $arr["initial"] = strtoupper(trim($arr["initial"], '.'));
        $names = explode(" ", $arr["surname"]);
        $name = $arr["initial"];
        foreach ($names as $str) {
            $name .= strtoupper($str[0]);
        }
        $from = new DateTime($arr["dob"]);
        $name .= " - " . $from->format('Y');
        // first try
        $res = Database::query_safe("SELECT count(*) AS Counter FROM `user` WHERE DisplayName LIKE ? ", array($name));
        $res = $res[0];
        $count = $res["Counter"];
        if ($count == 0)
            return $name;

        $i = 0;
        while (true) {
            $tmp = $name . " (" . ($count + $i) . ")";

            $res = Database::query_safe("SELECT count(*) AS Counter FROM `user` WHERE DisplayName = ? ", array($tmp));
            $res = $res[0];
            if ($res["Counter"] == 0)
                return $tmp;

            $i++;

        }

    }

    public
    function createDislayCompany($arr)
    {

        $name = $arr["companyName"];
        // first try
        $res = Database::query_safe("SELECT count(*) AS Counter FROM `user` WHERE DisplayName LIKE ? ", array($name));
        $res = $res[0];
        $count = $res["Counter"];
        if ($count == 0)
            return $name;

        $i = 0;
        while (true) {
            $tmp = $name . " (" . ($count + $i) . ")";

            $res = Database::query_safe("SELECT count(*) AS Counter FROM `user` WHERE DisplayName = ? ", array($tmp));
            $res = $res[0];
            if ($res["Counter"] == 0)
                return $tmp;

            $i++;

        }

    }

    private
    function validPass($password)
    {
        if (strlen($password) < 8
            || !preg_match('/[0-9]/', $password)
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
        )
            return false;
        return true;
    }


    public
    function validateUsername($username)
    {
        $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));

        // Validate e-mail
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false) {


            $res = $this->UserQueryBuilder->checkExistence($username);
            if ($res == null)
                return false;
            return true;
        }
        return false;

    }

    public
    function getAllMatchedDislaynames(User $user)
    {
        return $this->UserQueryBuilder->getDisplaynames($user);
    }

    public
    function getAllDislaynames()
    {
        return $this->UserQueryBuilder->getDisplaynames();
    }

    public
    function newPassword($username, $password)
    {
        if ($this->validateUsername($username)) {

            $username = strtolower(filter_var($username, FILTER_SANITIZE_EMAIL));
            // validate password
            // Wachtwoord moet minimaal 8 tekens lang, een nummer, een hoofdletter, een kleine letter en een speciaal teken bevatten.
            if (!$this->validPass($password))
                return false;

            // save password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $this->UserQueryBuilder->setPassword($hashed, $username);
            return true;
        }
        return false;

    }

    public
    function getCurrentUser()
    {

        if (empty($_SESSION["user"])) {
            return false;
        }
        return $_SESSION["user"];
    }

//<<<<<<< HEAD
//    /** returns all users */
//    public function getAllUsers(){
//        return $this->UserQueryBuilder->getAllUsers();
//    }
//
//    /** search users
//     * @param $keyword = keyword to search user this can be a name for example
//     * @return array with user objects */
//    public function searchUsers($keyword){
//=======

    /** returns all users */
    public
    function getAllUsers()
    {
        return $this->createUsers($this->UserQueryBuilder->getAllUsers());
    }

    /** search users
     * @param $keyword = keyword to search user this can be a name for example
     * @return array with user objects
     */
    public
    function searchUsers($keyword)
    {
        return $this->userCreator($this->UserQueryBuilder->getAllUsers($keyword));
    }

    private
    function userCreator($result)
    {
        if (count(($result)) === 0) {
            return null;
        }

        if (count($result) === 1) {
            return $this->createUser($result);
        } else {
            return $this->createUsers($result);
        }
    }

    private
    function createUser($result)
    {

        if ($result == null || $result == false || count($result) == 0) {
            return false;
        }

        $newUser = new User();

        $newUser->email = $result[0]["Email"];
        $newUser->isAdmin = $result[0]["Admin"];
        $newUser->name = $result[0]["Name"];
        $newUser->surname = $result[0]["Surname"];
        $newUser->address = $result[0]["Address"];
        $newUser->handicap = $result[0]["Handicap"];
        $newUser->postalcode = $result[0]["Postalcode"];
        $newUser->country = $result[0]["Country"];
        $newUser->city = $result[0]["City"];
        $newUser->dob = $result[0]["Dob"];
        $newUser->gender = $result[0]["Gender"];
        $newUser->hash = $result[0]["ValidationHash"];
        $newUser->displayName = $result[0]["DisplayName"];
        $newUser->initials = $result[0]["Initials"];
        $newUser->RecoveryHash = $result[0]["RecoveryHash"];
        $newUser->RecoveryDate = $result[0]["RecoveryDate"];
        $newUser->companyName = $result[0]["CompanyName"];
        $newUser->guardian = $result[0]["Guardian"];
        $newUser->handicapInfo = $result[0]["HandicapInfo"];
        $newUser->lat = $result[0]["Lat"];
        $newUser->lon = $result[0]["Lon"];

        if (isset($result[0]["isBlocked"])) {
            $newUser->blocked = $result[0]["isBlocked"];
        }

        return $newUser;
    }

    /** creates multipe user objects */
    public
    function createUsers($result)
    {
        $users = array();
        foreach ($result as $item) {
            $users[] = $this->createUser(array($item));
        }

        return $users;
    }

}