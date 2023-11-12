
CREATE TABLE IF NOT EXISTS actor_content (
    actor_id INT,
    content_id INT,
    PRIMARY KEY (actor_id, content_id),
    FOREIGN KEY (actor_id) REFERENCES actor(actor_id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES content(content_id) ON DELETE CASCADE
);