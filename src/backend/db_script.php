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


// INDEXES AND ARRAY REFERENCES FUNCTION - 0.0055 seconds
$indexes = [];
foreach ($categories as $category) {
    if ($category["parent_id"] === 0) {
        $indexes[$category["categories_id"]] = 0;
    } else {
        $indexes[$category["categories_id"]] = $indexes[$category["parent_id"]] . "/" . $category["parent_id"];
    }
}

function addToMultiDimensionalArray(&$array, $path, $value) {
    if (!$path) {
        $array[$value] = [];
        return;
    }

    $keys = explode('/', $path);
    $current = &$array;

    foreach ($keys as $key) {
        if ($key == 0) {
            continue;
        }
        $current = &$current[$key];
    }
    $current = [$value => $value];
}

$tree = [];
foreach ($indexes as $categoryId => $path) {
    addToMultiDimensionalArray($tree, $path, $categoryId);
}

// RECURSIVE FUNCTION - 0.0958 seconds
//function buildCategoryTree($categories, $parentId = 0): array
//{
//    $categoryTree = [];
//    foreach ($categories as $category) {
//        if ($category['parent_id'] === $parentId) {
//            $children = buildCategoryTree($categories, $category['categories_id']);
//            $categoryTree[$category['categories_id']] = !empty($children) ? $children : $category['categories_id'];
//        }
//    }
//    return $categoryTree;
//}
//
//$tree = buildCategoryTree($categories);

$endTime = microtime(true);
echo json_encode($tree, JSON_PRETTY_PRINT) . PHP_EOL;
$execTime = ($endTime - $startTime);
echo "Script have been executed in " . $execTime . " seconds" . PHP_EOL;