<?php

class CategoryRepo {

    public function add_category(Category $category) {

        $sql = "INSERT INTO `Category` (`name`, `description`,`parentcategoryID`) VALUES(?,?,?)";
        $parameters = array($category->getName(), $category->getDescription(), 70);
        Database::query_safe($sql, $parameters);
    }

    public function getAllCategories() {
        $sql = "SELECT * from `Category`";
        $result = Database::query($sql);

        $categoryList = array();
        foreach ($result as $item) {
            $id = $item['categoryID'];
            $name = $item['name'];
            $description = $item['description'];
            $categoryList[] = new Category($id, $name, $description, "");
        }
        return $categoryList;
    }

    public function getCategoryById(Category $category) {
        $sql = "SELECT * FROM `Category` WHERE `categoryID` = ?";
        $parameters = array($category->getId());
        $result = Database::query_safe($sql, $parameters);

        $id = $result[0]["categoryID"];
        $name = $result[0]["name"];
        $description = $result[0]["description"];
        $parentID = 70; //$result[0]["parentcategoryID"];

        $category = new Category($id, $name, $description, $parentID);
        return $category;
    }

    public function getCategoryIDByName(Category $category) {
        $sql = "SELECT * FROM `Category` WHERE `name` = ?";
        $parameters = array($category->getName());
        $result = Database::query_safe($sql, $parameters);

        $id = $result[0]["categoryID"];
        $name = $result[0]["name"];
        $description = $result[0]["description"];
        return new Category($id, $name, $description, "");
    }

    public function editCategory(Category $category) {
        $name = $category->getName();
        $description = $category->getDescription();
        $id = $category->getId();
        $parentid = 70; //$category->getParentCategoryId();

        $sql = "UPDATE `Category` SET `name`=?, `description`=?, `parentcategoryID`=? WHERE `categoryID`=?";
        $parameters = array($name, $description, $parentid, $id);
        Database::query_safe($sql, $parameters);
    }

    public function deleteCategory(Category $category) {
        $sql = "DELETE from Category where categoryID=?";
        $parameters = array($category->getId());
        Database::query_safe($sql, $parameters);
    }

}