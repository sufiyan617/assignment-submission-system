-- Assignment System schema + demo data
-- Import into an EMPTY database (phpMyAdmin > Import). Local: CREATE DATABASE assignment_db first.

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS assignments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  deadline DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  assignment_id INT NOT NULL,
  student_id INT NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  marks INT NULL,
  UNIQUE KEY one_per_student (assignment_id, student_id),
  FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo logins: admin / admin123   student1 / student123  (change after first login!)
INSERT INTO users (username, password, role) VALUES
 ('admin',    '$2y$10$cVQRhMhAK8RwJAcOVWuyP.T2DgrNqqxpFeKBATWmwohtpzOLqU8yi', 'admin'),
 ('student1', '$2y$10$246df8WNqFB4uSdCwZg1Pe3jMQQqJ8/ZoJKIy1jXZN4bgDeemCczy', 'student');

INSERT INTO assignments (title, description, deadline) VALUES
 ('PHP CRUD Project', 'Build a small CRUD app using PHP and MySQL.', DATE_ADD(CURDATE(), INTERVAL 14 DAY));
