<?php

namespace Handler\Genre;

use Core\Application\Services\GenreService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class GenreHandler extends BaseHandler
{
    protected static GenreHandler $instance;
    protected GenreService $service;

    private function __construct(GenreService $service)
    {
        $this->service = $service;
    }

    public static function getInstance(GenreService $genreService): GenreHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $genreService
            );
        }
        return self::$instance;
    }

    /* 
     * format: 
     * /api/genre
     * /api/genre?genre_id={gid} 
     */
    public function get($params = null): void
    {
        if (isset($params['genre_id'])) {
            try {
                $genre = $this->service->getGenreById($params['genre_id']);
                $genreArray = $genre->toArray();
                $response = new Response(true, HttpStatusCode::OK, "Genre found successfully", $genreArray);
                $response->encode_to_JSON();
            } catch (\Throwable $th) {
                $response = new Response(false, HttpStatusCode::NOT_FOUND, "Genre id not found", null);
                $response->encode_to_JSON();
            } finally {
                return;
            }
        }

        $allGenres = $this->service->getAllGenre();
        $allGenresArray = [];

        foreach($allGenres as $genre) {
            $genreArray = $genre->toArray();
            $allGenresArray[] = $genreArray;
        }
        
        $response = new Response(true, HttpStatusCode::OK, "Genre found successfully", $allGenresArray);
        $response->encode_to_JSON();
    }
    public function post($params = null): void
    {
        $genre_name = $_POST['genre_name'];

        try {

            $genre = $this->service->addGenre($genre_name);
            $response = new Response(true, HttpStatusCode::OK ,"New genre successfully added", $genre->toArray());
            $response->encode_to_JSON();

        } catch (Exception) {
            $response = new Response(false, HttpStatusCode::FORBIDDEN, "Invalid credentials", null);
            $response->encode_to_JSON();

        }
    }

    public function put($params = null): void
    {
        try {
            $putData = file_get_contents('php://input');
            parse_str($putData, $_PUT);

            $genre_id = $_PUT['genre_id'];
            $genre_name = $_PUT['genre_name'];

            if (is_null($this->service->getGenreById($genre_id))) {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Genre not found", null);
                $response->encode_to_JSON();
                return;
            }

            $updatedGenre = $this->service->updateGenre($genre_id, $genre_name);

            $response = new Response(true, HttpStatusCode::OK, "Genre updated successfully", $updatedGenre->toArray());
            $response->encode_to_JSON();

        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Genre update failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }

    }

    public function delete($params = null): void
    {
        if (!isset($params['genre_id'])) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Insufficient parameter: genre_id", null);
            $response->encode_to_JSON();
            return;
        }

        $this->service->removeGenre($params['genre_id']);
        $response = new Response(true, HttpStatusCode::OK, "Genre deleted successfully", null);
        $response->encode_to_JSON();
    }
}