-- Fix attendance table constraint
USE kwikster_cms;

-- Drop old unique constraint
ALTER TABLE attendance DROP INDEX attendance_employee_id_attendance_date_unique;

-- Add new unique constraint with shift
ALTER TABLE attendance ADD UNIQUE KEY attendance_employee_date_shift_unique (employee_id, attendance_date, shift);