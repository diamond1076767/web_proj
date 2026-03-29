-- Run this on your existing tpamc database to fix "Data too long" errors
-- These columns store AES-256-CBC encrypted + base64 encoded values that exceed their original size limits

ALTER TABLE `user` MODIFY `fullName` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;

ALTER TABLE `request_user` MODIFY `fullName` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
