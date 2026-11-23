-- Additive/idempotent MariaDB migration. No existing rows are deleted.
ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_encrypted TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE chat_participants ADD COLUMN IF NOT EXISTS last_read_message_id INT NOT NULL DEFAULT 0;
ALTER TABLE tasks ADD COLUMN IF NOT EXISTS id_chats INT NULL;
