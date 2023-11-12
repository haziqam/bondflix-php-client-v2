<?php
namespace Handler\Content;

use Core\Application\Services\ContentService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class ContentDirectorHandler extends BaseHandler {
    
    protected static ContentDirectorHandler $instance;
    protected ContentService $service;

    private function __construct(ContentService $contentService)
    {
        $this->service = $contentService;
    }

    public static function getInstance($contentService): ContentDirectorHandler
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
     * /api/content/director?content_id={cid} 
     */
    protected function get($params = null)
    {
        $directors = $this->service->getDirectors($params['content_id']);
        $directorsArray = [];
        foreach ($directors as $director) {
            $directorsArray[] = $director->toArray();
        }

        $response = new Response(true, HttpStatusCode::OK ,"Director(s) retrieved successfully", $directorsArray);
        $response->encode_to_JSON();
    }

    protected function post($params = null)
    {
        try {
            $content_id = $_POST['content_id'];
            $director_id = $_POST['director_id'];

            $this->service->addDirector($content_id, $director_id);

            $response = new Response(true, HttpStatusCode::OK ,"Director(s) added successfully", null);
            $response->encode_to_JSON();
            
        } catch (Exception $e) {
            $response = new Response(true, HttpStatusCode::BAD_REQUEST ,"Failed to add director(s)", null);
            $response->encode_to_JSON();
        }
    }

     /*
     * route formats:
     * /api/content/director?content_id={cid}&director_id={did} => delete director with id=did from content with id=cid
     */
    protected function delete($params = null)
    {
        try {
            $this->service->removeDirector($params['content_id'], $params['director_id']);
            $response = new Response(true, HttpStatusCode::OK, "Director deleted successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Director deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}