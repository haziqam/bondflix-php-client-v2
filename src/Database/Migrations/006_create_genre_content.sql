CREATE TABLE IF NOT EXISTS genre_content (
    content_id INT,
    genre_id INT,
    PRIMARY KEY (content_id, genre_id),
    FOREIGN KEY (content_id) REFERENCES content(content_id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genre(genre_id) ON DELETE CASCADE
);