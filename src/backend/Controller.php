<?php
require "Category.php";
require "Product.php";
class Controller
{
    protected string $route;

    public array $routes = [
        "categories" => [Category::class, 'getAllCategories'],
        "productsByCategory" => [Product::class, 'getProductsByCategory']
    ];

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function action(): void
    {
        $action = $this->routes[$this->route];
        $object = new $action[0];
        $method = $action[1];

        $result = $object->$method();

        echo json_encode($result);
    }

}