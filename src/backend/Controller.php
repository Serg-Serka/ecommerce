<?php
require "Category.php";
require "Product.php";
class Controller
{
    protected string $route;

    protected array $params;

    public array $routes = [
        "categories" => [Category::class, 'getAllCategories'],
        "productsByCategory" => [Product::class, 'getProductsByCategory']
    ];

    public function __construct($route, $params = [])
    {
        $this->route = $route;
        $this->params = $params;
    }

    public function action(): void
    {
        $action = $this->routes[$this->route];
        $object = new $action[0];
        $method = $action[1];

        $result = $object->$method(...$this->params);

        echo json_encode($result);
    }

}