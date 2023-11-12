-- 002_create_content_table.sql

-- Create the "content" table
CREATE TABLE IF NOT EXISTS content (
    content_id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(1023),
    release_date DATE NOT NULL,
    content_file_path VARCHAR(255) NOT NULL,
    thumbnail_file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT NOW()
);