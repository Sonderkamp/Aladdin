<?php

class ProductRepo {

    private $category_repository;

    public function __construct() {
        $this->category_repository = new CategoryRepo();
    }

    public function addProduct(Product $product) {
        $catid = $product->getCatId();
        $name = $product->getName();
        $des_short = $product->getDescriptionShort();
        $des_long = $product->getDescriptionLong();
        $price = $product->getPrice();
        $picture = $product->getPicture();

        $sql = "INSERT INTO `Product` (`catID`, `name`,`description_long`,`description_short`,`price`,`picture`)
                VALUES (?,?,?,?,?,?)";
        $parameter = array($catid, $name, $des_short, $des_long, $price, $picture);

        Database::query_safe($sql, $parameter);
    }

    public function getAllProducts() {
        $sql = "SELECT * from `Product` ORDER BY `catID` ASC ";
        $result = Database::query($sql);

        $productList = array();
        foreach ($result as $item) {
            $id = $item['productID'];
            $cat_id = $item['catID'];
            $name = $item['name'];
            $desc_long = $item['description_long'];
            $desc_short = $item['description_short'];
            $price = $item['price'];
            $picture = $item['picture'];

            $category = new Category($cat_id, "", "","");
            $returnCategory = $this->category_repository->getCategoryById($category);

            $productList[] = new Product($id, $cat_id, $name, $desc_long, $desc_short, $price, $picture,
                $returnCategory->getName());
        }
        return $productList;
    }

    public function deleteProduct(Product $product) {
        $sql = "DELETE from Product where productID=?";
        $parameters = array($product->getId());
        Database::query_safe($sql, $parameters);
    }

    public function editProduct(Product $product) {
        $id = $product->getId();
        $catID = $product->getCatId();
        $name = $product->getName();
        $description_short = $product->getDescriptionShort();
        $description_long = $product->getDescriptionLong();
        $price = $product->getPrice();
        $picture = $product->getPicture();

        $sql = "UPDATE `Product` SET `name`=?,`catID`=?, `description_long`=?, `description_short`=?,
        `price`=?, `picture`=? WHERE `productID`=?";
        $parameters = array($name, $catID, $description_long, $description_short, $price, $picture, $id);
        Database::query_safe($sql, $parameters);
    }

    public function getProductById($productid) {
        $sql = "SELECT * FROM `Product` WHERE `productID` = ?";
        $parameters = array($productid);
        $result = Database::query_safe($sql, $parameters);

        $id = $result[0]["productID"];
        $catId = $result[0]["catID"];
        $name = $result[0]["name"];
        $descr_short = $result[0]["description_short"];
        $descr_long = $result[0]["description_long"];
        $price = $result[0]["price"];
        $picture = $result[0]["picture"];

        $category = new Category($catId, "", "","");
        $returnCategory = $this->category_repository->getCategoryById($category);

        $product = new Product($id, $catId, $name, $descr_long, $descr_short, $price, $picture,
            $returnCategory->getName());
        return $product;
    }

}