-- Fix attendance table status column size
USE kwikster_cms;

-- Increase status column size to handle longer values
ALTER TABLE attendance MODIFY COLUMN status VARCHAR(50) NOT NULL;