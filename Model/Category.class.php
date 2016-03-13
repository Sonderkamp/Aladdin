<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 12-03-16
 * Time: 13:23
 */
class Category {


    private $id, $name, $description, $parentCategoryId;

    public function __construct($id, $name, $description, $parentCategoryId) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->parentCategoryId = $parentCategoryId;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getParentCategoryId() {
        return $this->parentCategoryId;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setParentCategoryId($parentCategoryId) {
        $this->parentCategoryId = $parentCategoryId;
    }
}