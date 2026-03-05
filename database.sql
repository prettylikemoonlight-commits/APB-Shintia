CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    kelas VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun YEAR NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_buku INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE DEFAULT NULL,
    status ENUM('dipinjam', 'kembali') DEFAULT 'dipinjam',
    denda INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
);

-- Insert default admin (password: admin123)
INSERT INTO users (nama, username, password, role) VALUES 
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert example user (password: user123)
INSERT INTO users (nama, username, password, role, kelas) VALUES 
('Shintia Kurniawan', 'shinta', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'XII RPL 2');

-- Insert dummy books
INSERT INTO buku (judul, pengarang, penerbit, tahun, stok, kategori) VALUES 
('Filosofi Teras', 'Henry Manampiring', 'Kompas', 2019, 10, 'Self Improvement'),
('Atomic Habits', 'James Clear', 'Gramedia', 2018, 5, 'Self Improvement'),
('Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Scholastic', 1997, 3, 'Fantasy'),
('Clean Code', 'Robert C. Martin', 'Pearson', 2008, 2, 'Programming'),
('The Psychology of Money', 'Morgan Housel', 'Harriman House', 2020, 7, 'Finance'),
('Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 12, 'Novel');
