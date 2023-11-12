CREATE TABLE IF NOT EXISTS my_list (
    my_list_id SERIAL PRIMARY KEY,
    user_id int REFERENCES users(user_id) ON DELETE CASCADE,
    content_id int REFERENCES content(content_id) ON DELETE CASCADE
);