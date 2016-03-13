<?php

class AdminController {

    private $category_repository;

    public function __construct() {
        $this->category_repository = new CategoryRepo();
    }

    public function run() {
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "open_navigation_panel":
                    $this->open_navigation_panel();
                    break;
                case "open_category_overview":
                    $this->open_category_overview();
                    break;
                case "open_new_category":
                    $this->open_new_edit_category(true);
                    break;
                case "open_edit_category":
                    $this->open_edit_category();
                    break;
                case "go_back";
                    $this->admin_home();
                    break;
                case "add_category":
                    $this->add_category();
                    break;
                case "edit_category":
                    $this->edit_category();
                    break;
                case "delete_category":
                    $this->delete_category();
                    break;
                case "view_category":
                    $this->view_category();
                    break;
//                case "open_product_panel";
//                    (new ProductController())->run();
//                    break;
                default:
                    apologize("404 not found, Go back to my admin panel");
                    break;
            }
        } else {
            $this->admin_home();
            exit();
        }
    }

    public function open_navigation_panel() {
        render("admin_navigation_panel.php", ["title" => "Navigatie beheer"]);
    }

    public function open_category_overview() {
        $allCategories = $this->category_repository->getAllCategories();
        render("admin_category_overview.php", ["title" => "Categorieën beheer", "allCategories" => $allCategories]);
    }

    public function open_new_edit_category($new) {
        if ($new) {
            $allCategories = $this->category_repository->getAllCategories();
            render("admin_category_new_edit.php", ["title" => "Categorieën beheer", "allCategories" => $allCategories]);
        }
    }

    public
    function open_edit_category() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $id = $_GET["editCategoryID"];
            $tempCategory = new Category($id, "", "", "");
            $category = $this->category_repository->getCategoryById($tempCategory);

            $name = $category->getName();
            $description = $category->getDescription();
            $curParentCategory = $category->getParentCategoryId();

            $cat = "";
            if (!empty($curParentCategory)) {
                $cat = $this->category_repository->getCategoryById(new Category($curParentCategory, "", "", 0))
                    ->getName()
                ;
            }

            $allCategories = $this->category_repository->getAllCategories();

            render("admin_category_new_edit.php",
                ["title" => "Categorieën beheer", "curCategory" => $cat, "allCategories" => $allCategories, "categorieID" => $id, "name" => $name, "description" => $description, "edit" => "isset"]);
        }
    }

    public
    function open_product_panel() {
        render("admin_product_overview.php", ["title" => "Producten beheer"]);
    }

    public
    function admin_home() {
        $this->open_category_overview();
    }


    public
    function add_category() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $category_name = $_POST["category_name"];
            $category_description = $_POST["category_description"];
//            $parent_category = $_POST["parentcategory"];
//
//            $parent_cat = "";
//            if ($parent_category != " ") {
//                $parent_cat = $this->category_repository->getCategoryIDByName(
//                    new Category(0, $parent_category, "", ""))->getId()
//                ;
//            }

            $list = array();
            $list[] = $category_name;
            $list[] = $category_description;

            if ($this->has_empty_values($list)) {
                render("admin_category_new_edit.php", ["error" => "vul aub alles in"]);
            } else {
                $newCategory = new Category(0, $category_name, $category_description, 70);
                $this->category_repository->add_category($newCategory);
                $this->open_category_overview();
            }
        } else {
            $this->admin_home();
        }
    }

    public
    function edit_category() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $category_id = $_GET["category_id"];
            $category_name = $_GET["category_name"];
            $category_description = $_GET["category_description"];
//            $parent_category = $_GET["parentcategory"];
//
//            $parent_cat = "";
//            if ($parent_category != "[geen]") {
//                $parent_cat = $this->category_repository->getCategoryIDByName(
//                    new Category(0, $parent_category, "", ""))->getId()
//                ;
//            }

            $list = array();
            $list[] = $category_name;
            $list[] = $category_description;

            if ($this->has_empty_values($list)) {
                render("admin_category_new_edit.php", ["error" => "vul aub alles in"]);
            } else {
                $editCategory = new Category($category_id, $category_name, $category_description, 70);
                $this->category_repository->editCategory($editCategory);
                $this->open_category_overview();
            }
        }
    }

    public
    function delete_category() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $categoryID = $_GET["deleteCategoryID"];
            $temp = new Category($categoryID, "", "", "");
            $this->category_repository->deleteCategory($temp);
            $this->open_category_overview();
        }
    }

    public
    function view_category() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $categoryID = $_GET["viewCategoryID"];
            $tempCategory = new Category($categoryID, "", "", "");
            $category = $this->category_repository->getCategoryById($tempCategory);

            $category_name = $category->getName();
            $category_description = $category->getDescription();

            render("admin_category_view.php",
                ["title" => "$category_name", "category_name" => $category_name, "category_description" => $category_description]);
        }
    }

    public
    function has_empty_values($list) {
        foreach ($list as $item) {
            if (empty($item)) {
                return true;
            }
        }
        return false;
    }

}