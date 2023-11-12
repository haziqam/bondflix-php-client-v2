CREATE TABLE IF NOT EXISTS director_content (
    content_id INT,
    director_id INT,
    PRIMARY KEY (content_id, director_id),
    FOREIGN KEY (content_id) REFERENCES content(content_id) ON DELETE CASCADE,
    FOREIGN KEY (director_id) REFERENCES director(director_id) ON DELETE CASCADE
);