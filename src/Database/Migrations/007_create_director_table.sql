CREATE TABLE IF NOT EXISTS director (
    director_id SERIAL PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(50)
);