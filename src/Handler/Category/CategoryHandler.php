<?php

namespace Handler\Category;

use Core\Application\Services\CategoryService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class CategoryHandler extends BaseHandler
{
    protected static CategoryHandler $instance;
    protected CategoryService $service;
    private function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public static function getInstance(CategoryService $categoryService): CategoryHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $categoryService
            );
        }
        return self::$instance;
    }


    /* 
     * format: 
     * /api/category
     * /api/category?category_id={cid} 
     */
    public function get($params = null) {
        
        if (isset($params['category_id'])) {
            try {
                $category = $this->service->getCategoryById($params['category_id']);
                $categoryArray = $category->toArray();
                $response = new Response(true, HttpStatusCode::OK, "Category found successfully", $categoryArray);
                $response->encode_to_JSON();
            } catch (Exception $e) {
                $response = new Response(false, HttpStatusCode::NOT_FOUND, "Category id not found", null);
                $response->encode_to_JSON();
            } finally {
                return;
            }
        }

        $allCategorys = $this->service->getAllCategory();
        $allCategorysArray = [];

        foreach($allCategorys as $category) {
            $categoryArray = $category->toArray();
            $allCategorysArray[] = $categoryArray;
        }
        
        $response = new Response(true, HttpStatusCode::OK, "Category found successfully", $allCategorysArray);
        $response->encode_to_JSON();
    }
    public function post($params = null): void
    {
        $category_name = $_POST['category_name'];

        try {
            $category = $this->service->addCategory($category_name);
            $response = new Response(true, HttpStatusCode::OK ,"New category successfully added", $category->toArray());
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::FORBIDDEN, "Invalid credentials", null);
            $response->encode_to_JSON();
        }
    }

    public function put($params = null) {
        try {
            $putData = file_get_contents('php://input');
            parse_str($putData, $_PUT);

            $category_id = $_PUT['category_id'];
            $category_name = $_PUT['category_name'];
            $updatedCategory = $this->service->updateCategory($category_id, $category_name);
            $response = new Response(true, HttpStatusCode::OK, "Category updated successfully", $updatedCategory->toArray());
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Category update failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }

    }

    public function delete($params = null) {
        if (!isset($params['category_id'])) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Insufficient parameter: category_id", null);
            $response->encode_to_JSON();
            return;
        }

        $this->service->removeCategory($params['category_id']);
        $response = new Response(true, HttpStatusCode::OK, "Category deleted successfully", null);
    }
}