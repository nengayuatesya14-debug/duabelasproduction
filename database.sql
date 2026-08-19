CREATE DATABASE IF NOT EXISTS duabelas_fotografi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE duabelas_fotografi;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(25) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS packages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(80) NOT NULL,
    price DECIMAL(14,2) NOT NULL DEFAULT 0,
    dp_percentage TINYINT UNSIGNED NOT NULL DEFAULT 30,
    duration_hours DECIMAL(5,1) NOT NULL DEFAULT 2,
    description TEXT NOT NULL,
    features TEXT DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    package_id INT UNSIGNED NOT NULL,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    notes TEXT DEFAULT NULL,
    total_price DECIMAL(14,2) NOT NULL,
    dp_amount DECIMAL(14,2) NOT NULL,
    status ENUM('pending','awaiting_payment','payment_review','confirmed','completed','cancelled') NOT NULL DEFAULT 'awaiting_payment',
    admin_note TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_booking_package FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_booking_date (event_date),
    INDEX idx_booking_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(14,2) NOT NULL,
    bank_name VARCHAR(80) NOT NULL,
    account_name VARCHAR(120) NOT NULL,
    proof VARCHAR(255) NOT NULL,
    verification_status ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    admin_note TEXT DEFAULT NULL,
    verified_by INT UNSIGNED DEFAULT NULL,
    verified_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_payment_admin FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_payment_status (verification_status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS schedules (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL UNIQUE,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    status ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_schedule_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_schedule_date (event_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(140) NOT NULL,
    category VARCHAR(80) NOT NULL,
    description TEXT DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    total_bookings INT UNSIGNED NOT NULL DEFAULT 0,
    total_revenue DECIMAL(14,2) NOT NULL DEFAULT 0,
    created_by INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_report_admin FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name, email, phone, address, password, role, status)
VALUES
('Administrator', 'admin@duabelas.local', '081234567890', 'Pasaman Barat', '$2y$12$kwSVOzYhTyaCGTVQ1k3Sgeb4e53uI8uKmR6PiX1t0oN5dqEdalDSi', 'admin', 'active'),
('Pelanggan Demo', 'pelanggan@demo.local', '081298765432', 'Pasaman Barat', '$2y$12$D1pJqg8UWXrXIasBEVnL0./Jhfg5CWajjQfOurFCozh.eosiipyG.', 'customer', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name), status = 'active';

INSERT INTO packages (id, name, category, price, dp_percentage, duration_hours, description, features, status)
VALUES
(1, 'Paket Wedding Signature', 'Wedding', 4500000, 30, 8, 'Dokumentasi lengkap hari pernikahan dengan tim foto dan video profesional.', '2 fotografer\n1 videografer\nAlbum eksklusif\nVideo highlight\nFile digital', 'active'),
(2, 'Paket Prewedding Story', 'Prewedding', 2500000, 30, 5, 'Sesi prewedding dengan konsep personal, pengarahan pose, dan editing profesional.', '2 lokasi\n2 konsep busana\n30 foto edit\nVideo reels\nFile digital', 'active'),
(3, 'Paket Wisuda Ceria', 'Wisuda', 650000, 30, 2, 'Dokumentasi wisuda untuk individu atau keluarga dengan hasil foto pilihan.', '1 lokasi\n1 fotografer\n20 foto edit\nFile digital', 'active'),
(4, 'Paket Event Profesional', 'Event', 1800000, 30, 4, 'Dokumentasi seminar, ulang tahun, gathering, dan acara organisasi.', '1 fotografer\nDokumentasi candid\n50 foto edit\nFile digital', 'active'),
(5, 'Paket Produk UMKM', 'Produk', 850000, 30, 3, 'Foto produk yang bersih dan menarik untuk katalog, marketplace, serta media sosial.', '10 produk\n2 angle per produk\nBackground studio\nFile siap unggah', 'active'),
(6, 'Paket Drone Aerial', 'Drone', 1500000, 30, 2, 'Pengambilan gambar udara untuk lokasi, event, properti, dan kebutuhan promosi.', 'Pilot drone\nVideo 4K\nColor grading\n1 video pendek', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name), category = VALUES(category), price = VALUES(price), description = VALUES(description), features = VALUES(features), status = 'active';

INSERT INTO gallery (id, title, category, description, status)
VALUES
(1, 'Cerita Hari Bahagia', 'Wedding', 'Dokumentasi momen akad dan resepsi.', 'active'),
(2, 'Senja Berdua', 'Prewedding', 'Konsep prewedding outdoor bernuansa hangat.', 'active'),
(3, 'Langkah Baru', 'Wisuda', 'Potret kelulusan bersama keluarga.', 'active'),
(4, 'Rasa yang Menjual', 'Produk', 'Foto produk kuliner untuk kebutuhan promosi.', 'active')
ON DUPLICATE KEY UPDATE title = VALUES(title), category = VALUES(category), status = 'active';
