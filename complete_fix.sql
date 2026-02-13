-- Complete fix for attendance table
USE kwikster_cms;

-- First check current structure
DESCRIBE attendance;

-- Drop existing constraints if any
ALTER TABLE attendance DROP INDEX IF EXISTS attendance_employee_id_attendance_date_unique;

-- Modify status column to handle all values
ALTER TABLE attendance MODIFY COLUMN status ENUM('P','A','HD','PL','CO','UL','H','WO') NOT NULL;

-- Add proper unique constraint with shift
ALTER TABLE attendance ADD UNIQUE KEY attendance_employee_date_shift_unique (employee_id, attendance_date, shift);

-- Show final structure
DESCRIBE attendance;