-- Database: menu_system
-- ============================================
-- 1. menu table - stores all available menu items
CREATE TABLE
    IF NOT EXISTS menu (
        menu_id INT AUTO_INCREMENT PRIMARY KEY,
        menu_name VARCHAR(100) NOT NULL,
        menu_link VARCHAR(255) NOT NULL,
        menu_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- 2. role_management table - stores user roles
CREATE TABLE
    IF NOT EXISTS role_management (
        role_id INT AUTO_INCREMENT PRIMARY KEY,
        role_type VARCHAR(50) NOT NULL UNIQUE,
        role_description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- 3. privilege_management table - controls menu access per role
CREATE TABLE
    IF NOT EXISTS privilege_management (
        privilege_id INT AUTO_INCREMENT PRIMARY KEY,
        role_id INT NOT NULL,
        menu_id INT NOT NULL,
        isActive TINYINT (1) DEFAULT 0, -- 0 = inactive, 1 = active
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_role_menu (role_id, menu_id),
        FOREIGN KEY (role_id) REFERENCES role_management (role_id) ON DELETE CASCADE,
        FOREIGN KEY (menu_id) REFERENCES menu (menu_id) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- 4. user_management table - stores user information
CREATE TABLE
    IF NOT EXISTS user_management (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        fullname VARCHAR(100) NOT NULL,
        role_id INT NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        is_active TINYINT (1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES role_management (role_id)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ============================================
-- INSERT INITIAL DATA
-- ============================================
-- Insert default roles
INSERT INTO
    role_management (role_type, role_description)
VALUES
    ('Admin', 'Full system access'),
    ('User', 'Regular user with limited access'),
    ('Viewer', 'View-only access');

-- Insert default menus with links and order
INSERT INTO
    menu (menu_name, menu_link, menu_order)
VALUES
    ('Dashboard', 'Dashboard.php', 1),
    ('Reports', 'Reports.php', 2),
    ('Menu Management', 'menu_management.php', 3),
    ('Role Management', 'role_management.php', 4),
    (
        'Privilege Management',
        'privilege_management.php',
        5
    ),
    ('User Management', 'user_management.php', 6);

-- Insert default admin user (password: admin123)
-- Hash password using PHP's password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO
    user_management (username, fullname, role_id, password_hash)
VALUES
    (
        'admin',
        'Administrator',
        1,
        '$2y$10$YourHashedPasswordHere'
    );

-- Triggers remain the same as your original