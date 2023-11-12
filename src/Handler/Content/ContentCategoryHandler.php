<?php
namespace Handler\Content;

use Core\Application\Services\ContentService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class ContentCategoryHandler extends BaseHandler {
    
    protected static ContentCategoryHandler $instance;
    protected ContentService $service;

    private function __construct(ContentService $contentService)
    {
        $this->service = $contentService;
    }

    public static function getInstance($contentService): ContentCategoryHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $contentService
            );
        }
        return self::$instance;
    }

    /*
     * route formats:
     * /api/content/category?content_id={cid} 
     */
    protected function get($params = null)
    {
        $categories = $this->service->getCategories($params['content_id']);
        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray[] = $category->toArray();
        }

        $response = new Response(true, HttpStatusCode::OK ,"Category(s) retrieved successfully", $categoriesArray);
        $response->encode_to_JSON();
    }

    protected function post($params = null)
    {
        try {
            $content_id = $_POST['content_id'];
            $category_id = $_POST['category_id'];

            $this->service->addCategory($content_id, $category_id);

            $response = new Response(true, HttpStatusCode::OK ,"Category(s) added successfully", null);
            $response->encode_to_JSON();
            
        } catch (Exception $e) {
            $response = new Response(true, HttpStatusCode::BAD_REQUEST ,"Failed to add category(s)", null);
            $response->encode_to_JSON();
        }
    }

     /*
     * route formats:
     * /api/content/category?content_id={cid}&category_id={cgid} => delete category with id=cgid from content with id=cid
     */
    protected function delete($params = null)
    {
        try {
            $this->service->removeCategory($params['content_id'], $params['category_id']);
            $response = new Response(true, HttpStatusCode::OK, "Category deleted successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Category deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}