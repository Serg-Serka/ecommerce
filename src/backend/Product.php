<?php

class Product
{

    public function getProductsByCategory($categoryId): array
    {
        $db = Database::getInstance();

        $sql = "SELECT * FROM products WHERE category_id = $categoryId";
        return $db->performQuery($sql);
    }

}