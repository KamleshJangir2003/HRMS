-- SQL for Attendance Management System

-- Employees Table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attendance Table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Leave', 'Half Day') DEFAULT 'Present',
    in_time TIME NULL,
    out_time TIME NULL,
    reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (employee_id, attendance_date)
);

-- Sample Data for Employees
INSERT INTO employees (employee_code, name, department, job_title) VALUES
('EMP001', 'John Doe', 'IT', 'Software Developer'),
('EMP002', 'Jane Smith', 'HR', 'HR Manager'),
('EMP003', 'Mike Johnson', 'Finance', 'Accountant'),
('EMP004', 'Sarah Wilson', 'IT', 'System Admin'),
('EMP005', 'David Brown', 'Marketing', 'Marketing Executive');