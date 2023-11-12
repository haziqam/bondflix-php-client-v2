<?php
namespace Handler\Content;

use Core\Application\Services\ContentService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

//TODO: Exception handling

class ContentActorHandler extends BaseHandler {
    
    protected static ContentActorHandler $instance;
    protected ContentService $service;

    private function __construct(ContentService $contentService)
    {
        $this->service = $contentService;
    }

    public static function getInstance($contentService): ContentActorHandler
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
     * /api/content/actor?content_id={cid} 
     */
    protected function get($params = null)
    {
        $actors = $this->service->getActors($params['content_id']);
        $actorsArray = [];
        foreach ($actors as $actor) {
            $actorsArray[] = $actor->toArray();
        }

        $response = new Response(true, HttpStatusCode::OK ,"Actors(s) retrieved successfully", $actorsArray);
        $response->encode_to_JSON();
    }

    protected function post($params = null)
    {
        try {
            $content_id = $_POST['content_id'];
            $actor_id = $_POST['actor_id'];

            $this->service->addActor($content_id, $actor_id);

            $response = new Response(true, HttpStatusCode::OK ,"Actors(s) added successfully", null);
            $response->encode_to_JSON();
            
        } catch (Exception $e) {
            $response = new Response(true, HttpStatusCode::BAD_REQUEST ,"Failed to add actors(s)", null);
            $response->encode_to_JSON();
        }
    }

     /*
     * route formats:
     * /api/content/actor?content_id={cid}&actor_id={aid} => delete actor with id=aid from content with id=cid
     */
    protected function delete($params = null)
    {
        try {
            $this->service->removeActor($params['content_id'], $params['actor_id']);
            $response = new Response(true, HttpStatusCode::OK, "Actor deleted successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Actor deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}