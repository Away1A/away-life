BEGIN;

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    name VARCHAR(100) NOT NULL,

    email VARCHAR(255) NOT NULL,

    password VARCHAR(255) NOT NULL,

    avatar VARCHAR(500),

    timezone VARCHAR(100) NOT NULL DEFAULT 'Asia/Jakarta',

    locale VARCHAR(10) NOT NULL DEFAULT 'id',

    theme VARCHAR(20) NOT NULL DEFAULT 'light',

    remember_token VARCHAR(255),

    email_verified_at TIMESTAMP,

    last_login_at TIMESTAMP,

    last_login_ip INET,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP
);

ALTER TABLE users
ADD CONSTRAINT uq_users_email UNIQUE(email);

CREATE INDEX idx_users_email
ON users(email);

CREATE INDEX idx_users_deleted_at
ON users(deleted_at);

CREATE INDEX idx_users_is_active
ON users(is_active);

CREATE INDEX idx_users_created_at
ON users(created_at);

COMMENT ON TABLE users IS 'Application users';

COMMENT ON COLUMN users.id IS 'Primary UUID';

COMMENT ON COLUMN users.email IS 'Unique email address';

COMMENT ON COLUMN users.deleted_at IS 'Soft delete timestamp';

COMMIT;