CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    is_admin BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    input TEXT,
    output TEXT,
    model_used VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE api_keys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service ENUM('chatgpt', 'deepseek'),
    api_key VARCHAR(255),
    models TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);