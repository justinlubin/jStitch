CREATE TABLE IF NOT EXISTS tb_user (
    pk_username VARCHAR(255) NOT NULL,
    hash BINARY(60) NOT NULL,
    current_stitch_count INT NOT NULL DEFAULT 0,
    total_stitch_count INT NOT NULL DEFAULT 0,
    PRIMARY KEY (pk_username)
)
