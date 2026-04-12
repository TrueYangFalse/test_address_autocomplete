CREATE EXTENSION IF NOT EXISTS pg_trgm;

CREATE TABLE IF NOT EXISTS fias_addresses (
    id BIGSERIAL PRIMARY KEY,
    full_address TEXT NOT NULL,
    region TEXT,
    city TEXT,
    street TEXT,
    house TEXT
);

CREATE INDEX IF NOT EXISTS idx_fias_addresses_full_address
    ON fias_addresses USING gin (full_address gin_trgm_ops);
