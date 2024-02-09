<?php
require "Database.php";
class Category
{

    public function getAllCategories(): array
    {
        $db = Database::getInstance();

        $sql = "SELECT categories.id, categories.name, COUNT(products.id) AS product_count FROM categories 
                LEFT JOIN products ON categories.id = products.category_id GROUP BY categories.id;";
        return $db->performQuery($sql);
    }

}