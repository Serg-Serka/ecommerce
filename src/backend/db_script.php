<?php
$startTime = microtime(true);

$connection = new PDO("mysql:host=db_mysql;dbName=ecommerce", 'root', 'rootSuperPass1!');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->exec("USE ecommerce;");
$query = $connection->query("SELECT * FROM categories_2", PDO::FETCH_ASSOC);


$categories = [];
foreach ($query as $category) {
    $categories[] = $category;
}

// RECURSIVE FUNCTION
function buildCategoryTree($categories, $parentId = 0): array
{
    $categoryTree = [];
    foreach ($categories as $category) {
        if ($category['parent_id'] === $parentId) {
            $children = buildCategoryTree($categories, $category['categories_id']);
            $categoryTree[$category['categories_id']] = !empty($children) ? $children : $category['categories_id'];
        }
    }
    return $categoryTree;
}

$tree = buildCategoryTree($categories);
echo json_encode($tree, JSON_PRETTY_PRINT);

$endTime = microtime(true);
$execTime = ($endTime - $startTime);
echo "Script have been executed in " . $execTime . " seconds" . PHP_EOL;