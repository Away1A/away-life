BEGIN;

CREATE TABLE IF NOT EXISTS sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    user_id UUID NOT NULL,

    session_id VARCHAR(255) NOT NULL,

    remember_token VARCHAR(255),

    ip_address INET NOT NULL,

    user_agent TEXT,

    device_name VARCHAR(255),

    platform VARCHAR(100),

    browser VARCHAR(100),

    country VARCHAR(100),

    city VARCHAR(100),

    is_current BOOLEAN NOT NULL DEFAULT FALSE,

    last_activity_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    expires_at TIMESTAMP NOT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    revoked_at TIMESTAMP,

    CONSTRAINT fk_sessions_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

ALTER TABLE sessions
ADD CONSTRAINT uq_sessions_session_id
UNIQUE(session_id);

CREATE INDEX idx_sessions_user
ON sessions(user_id);

CREATE INDEX idx_sessions_current
ON sessions(is_current);

CREATE INDEX idx_sessions_last_activity
ON sessions(last_activity_at);

CREATE INDEX idx_sessions_expires
ON sessions(expires_at);

CREATE INDEX idx_sessions_revoked
ON sessions(revoked_at);

COMMENT ON TABLE sessions IS 'User login sessions';

COMMENT ON COLUMN sessions.session_id IS 'PHP Session ID';

COMMENT ON COLUMN sessions.is_current IS 'Current active session';

COMMENT ON COLUMN sessions.revoked_at IS 'Logout timestamp';

COMMIT;