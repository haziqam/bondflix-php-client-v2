<?php
namespace Handler\Content;

use Core\Application\Services\ContentService;
use Core\Application\Services\GenreService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class ContentGenreHandler extends BaseHandler {
    
    protected static ContentGenreHandler $instance;
    protected ContentService $contentService;
    protected GenreService $genreService;

    private function __construct(ContentService $contentService, GenreService $genreService)
    {
        $this->contentService = $contentService;
        $this->genreService = $genreService;
    }

    public static function getInstance(ContentService $contentService, GenreService $genreService): ContentGenreHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $contentService,
                $genreService,
            );
        }
        return self::$instance;
    }

    /*
     * route formats:
     * /api/content/genre?content_id={cid} 
     */
    protected function get($params = null): void
    {
        $genresArray = [];
        if (isset($params['content_id'])){
            if (is_null($this->contentService->getContentById($params['content_id']))) {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST ,"Content id not found", null);
                $response->encode_to_JSON();
                return;
            }
            $genres = $this->contentService->getGenres($params['content_id']);
        } else {
            $genres = $this->genreService->getAllGenre();
        }

        foreach ($genres as $genre) {
            $genresArray[] = $genre->toArray();
        }

        if (!empty($genresArray)){
            $response = new Response(true, HttpStatusCode::OK ,"Genre(s) retrieved successfully", $genresArray);

        } else {
            $response = new Response(true, HttpStatusCode::OK, "There is no genre", []);
        }
        $response->encode_to_JSON();
    }

    protected function post($params = null): void
    {
        try {
            $content_id = $_POST['content_id'];
            $genre_id = $_POST['genre_id'];

            $this->contentService->addGenre($content_id, $genre_id);

            $response = new Response(true, HttpStatusCode::OK ,"Genre(s) added successfully", null);
            $response->encode_to_JSON();
            
        } catch (Exception $e) {
            $response = new Response(true, HttpStatusCode::BAD_REQUEST ,"Failed to add genre(s)", null);
            $response->encode_to_JSON();
        }
    }

     /*
     * route formats:
     * /api/content/genre?content_id={cid}&genre_id={did} => delete genre with id=did from content with id=cid
     */
    protected function delete($params = null): void
    {
        try {
            $this->contentService->removeGenre($params['content_id'], $params['genre_id']);
            $response = new Response(true, HttpStatusCode::OK, "Genre deleted successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Genre deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}