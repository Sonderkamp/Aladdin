<?php

/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 12-03-16
 * Time: 22:35
 */
class Product {

    private $id, $catid, $name, $description_long, $description_short, $price, $picture, $catname;

    public function __construct($id, $catid, $name, $description_long, $description_short, $price, $picture, $catname) {
        $this->id = $id;
        $this->catid = $catid;
        $this->name = $name;
        $this->description_long = $description_long;
        $this->description_short = $description_short;
        $this->price = $price;
        $this->picture = $picture;
        $this->catname = $catname;
    }

    public function getId() {
        return $this->id;
    }

    public function getCatId() { // getCatid
        return $this->catid;
    }

    public function getCatName() {
        return $this->catname;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescriptionLong() {
        return $this->description_long;
    }

    public function getDescriptionShort() {
        return $this->description_short;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getPrice() {
        return $this->price;
    }


}