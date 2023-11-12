-- 003_create_actors_table.sql

-- Create the "actors" table
CREATE TABLE IF NOT EXISTS actor (
    actor_id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50),
    birth_date DATE NOT NULL,
    gender VARCHAR(10) NOT NULL
);