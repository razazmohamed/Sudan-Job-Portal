-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS sudan_jobs_portal;

USE sudan_jobs_portal;

-- Drop tables if they already exist (for development purposes)
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Employers;
DROP TABLE IF EXISTS Jobs;

-- Create Users table
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Employers table
CREATE TABLE Employers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Jobs table
CREATE TABLE Jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    employer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES Employers(id) ON DELETE CASCADE
);

-- Insert sample data (optional)
INSERT INTO Employers (company_name, email, password) VALUES
('شركة السودان للتوظيف', 'employer@example.com', 'hashed_password');

INSERT INTO Jobs (title, description, employer_id) VALUES
('وظيفة مطور ويب', 'نبحث عن مطور ويب ذو خبرة.', 1),
('محاسب', 'مطلوب محاسب للعمل في شركة.', 1);
