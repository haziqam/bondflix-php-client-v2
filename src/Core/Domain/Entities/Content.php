<?php

namespace Core\Domain\Entities;

class Content {
    private int $content_id;
    private string $title;
    private string $description;
    private string $release_date;
    private string $content_file_path;
    private string $thumbnail_file_path;

    /**
     * @param int $content_id
     * @param string $title
     * @param string $description
     * @param string $release_date
     * @param string $content_file_path
     * @param string $thumbnail_file_path
     * 
     */
    public function __construct(
        int $content_id = -1,
        string $title = '',
        string $description = '',
        string $release_date = '',
        string $content_file_path = '',
        string $thumbnail_file_path = ''
    )
    {
        $this->content_id = $content_id;
        $this->title = $title;
        $this->description = $description;
        $this->release_date = $release_date;
        $this->content_file_path = $content_file_path;
        $this->thumbnail_file_path = $thumbnail_file_path;
    }


    public function getContentId(): int
    {
        return $this->content_id;
    }

    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getReleaseDate(): string
    {
        return $this->release_date;
    }

    public function setReleaseDate(string $release_date): void
    {
        $this->release_date = $release_date;
    }

    public function getContentFilePath(): string
    {
        return $this->content_file_path;
    }

    public function setContentFilePath(string $content_file_path): void
    {
        $this->content_file_path = $content_file_path;
    }

    public function getThumbnailFilePath(): string
    {
        return $this->thumbnail_file_path;
    }

    public function setThumbnailFilePath($thumbnail_file_path): void
    {
        $this->thumbnail_file_path = $thumbnail_file_path;
    }

    public function toArray(): array
    {
        return [
            'content_id' => $this->content_id,
            'title' => $this->title,
            'description' => $this->description,
            'release_date' => $this->release_date,
            'content_file_path' => $this->content_file_path,
            'thumbnail_file_path' => $this->thumbnail_file_path,
        ];
    }
}