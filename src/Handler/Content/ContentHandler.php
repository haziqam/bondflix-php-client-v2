<?php
namespace Handler\Content;

use Core\Application\Services\ContentService;
use Core\Application\Services\GenreService;
use Exception;
use Handler\BaseHandler;
use Utils\ArrayMapper\ArrayMapper;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

//TODO: exception handling

class ContentHandler extends BaseHandler {
    
    protected static ContentHandler $instance;
    protected ContentService $service;
    protected GenreService $genreService;
    private function __construct(ContentService $contentService, GenreService $genreService)
    {
        $this->service = $contentService;
        $this->genreService = $genreService;
    }

    public static function getInstance(ContentService $contentService, GenreService $genreService): ContentHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $contentService,
                $genreService
            );
        }
        return self::$instance;
    }

    /*
     * route formats: 
     * /api/content => get all content data
     * /api/content?page={p} => get content data at page p
     * /api/content?content_id={id} => get a content with a specific id
     */
    protected function get($params = null): void
    {
        try {
            $page = isset($params['page']) ? intval($params['page']) : 1;
            $pageSize = isset($params['pageSize']) ? intval($params['pageSize']) : 10;

            if (isset($params['query']) && isset($params['sortAscending'])) {
                $query = $params['query'];
                $sortAscending = filter_var($params['sortAscending'], FILTER_VALIDATE_BOOLEAN);

                $result = $this->service->processContentQuery($query);

                $filteredResult = $result;

                if (isset($params["genre_id"])) {
                    $genre_id = filter_var($params["genre_id"], FILTER_VALIDATE_INT);
                    if ($genre_id) {
                        $content_ids = $this->genreService->getAllContentIdFromGenreId($genre_id);
                        $filteredResult = array_filter($filteredResult, function ($item) use ($content_ids) {
                            return in_array($item->getContentId(), $content_ids);
                        });
                    }
                }

                if (isset($params['released_before'])) {
                    $released_before = $params['released_before'];
                    if (!empty($released_before)) {
                        $filteredResult = array_filter($filteredResult, function ($item) use ($released_before) {
                            return strtotime($item->getReleaseDate()) <= strtotime($released_before);
                        });
                    }
                }

                if ($sortAscending) {
                    usort($filteredResult, function ($a, $b) {
                        return strcmp($a->getTitle(), $b->getTitle());
                    });
                } else {
                    usort($filteredResult, function ($a, $b) {
                        return strcmp($b->getTitle(), $a->getTitle());
                    });
                }
            } else {
                $result = $this->service->getAllContents(null);
                $filteredResult = $result;
            }

            $totalContents = count($filteredResult);
            $totalPages = ceil($totalContents / $pageSize);
            header("X-Total-Pages: " . $totalPages);
            $page = max(1, min($page, $totalPages));

            $startIndex = ($page - 1) * $pageSize;
            $pagedResult = array_slice($filteredResult, $startIndex, $pageSize);

            $resultArray = ArrayMapper::mapObjectsToArray($pagedResult);

            if (!empty($resultArray)) {
                $response = new Response(true, HttpStatusCode::OK, "data retrieved successfully", $resultArray);
            } else {
                $response = new Response(false, HttpStatusCode::OK, "data not found", null);
            }
            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::NOT_FOUND, "Request failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }




    protected function post($params = null): void
    {
        try {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $release_date = $_POST['release_date'];
            $content_file_path = $_POST['content_file_path'];
            $thumbnail_file_path = $_POST['thumbnail_file_path'];

            $content = $this->service->createContent(
                $title,
                $description,
                $release_date,
                $content_file_path,
                $thumbnail_file_path
            );

            $response = new Response(true, HttpStatusCode::OK, "Content created successfully", $content->toArray());
            $response->encode_to_JSON();

        } catch (Exception $e) {

            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Content creation failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }

    protected function put($params = null): void
    {
        try {
            $content_id = $params['content_id'];

            if (is_null($this->service->getContentById($content_id))) {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Content not found", null);
                $response->encode_to_JSON();
                return;
            }

            $title = $params['title'] ?? null;
            $description = $params['description'] ?? null;
            $release_date = $params['release_date'] ?? null;
            $content_file_path = $params['content_file_path'] ?? null;
            $thumbnail_file_path = $params['thumbnail_file_path'] ?? null;

            $content = $this->service->updateContent(
                $content_id,
                $title,
                $description,
                $release_date,
                $content_file_path,
                $thumbnail_file_path
            );

            $response = new Response(true, HttpStatusCode::OK, "Content updated successfully", $content->toArray());
            $response->encode_to_JSON();

        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Content update failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }

    }

    /*
     * route formats: 
     * /api/content?content_id={id} => delete a content with a specific id
     */
    protected function delete($params = null): void
    {
        try {
            $content = $this->service->getContentById($params['contentId']);
            if (is_null($content)) {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Content not found", null);
                $response->encode_to_JSON();
                return;
            }
            $this->service->removeContent($params['contentId'], $content->getThumbnailFilePath(), $content->getContentFilePath());
            $response = new Response(true, HttpStatusCode::OK, "Content deleted successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Content deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}