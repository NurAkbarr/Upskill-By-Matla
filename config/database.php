<?php
/**
 * ==========================================================
 * UPSKILL by MATLA - Database Configuration & Connection
 * ==========================================================
 * Konfigurasi koneksi MySQL menggunakan PDO dengan pola Singleton.
 * Sangat cocok untuk shared hosting konvensional (efisien & aman).
 */

// Konfigurasi Kredensial Database
// Ganti nilai default ini sesuai dengan database di cPanel / Shared Hosting Anda
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', '3306');
if (!defined('DB_NAME')) define('DB_NAME', 'upskill_matla');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

class Database
{
    /**
     * Menyimpan instance koneksi PDO tunggal (Singleton)
     * @var PDO|null
     */
    private static ?PDO $instance = null;

    /**
     * Private constructor untuk mencegah instansiasi langsung dari luar
     */
    private function __construct() {}

    /**
     * Mencegah duplikasi object melalui cloning
     */
    private function __clone() {}

    /**
     * Mencegah unserialize object
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton instance.");
    }

    /**
     * Mengambil instance tunggal koneksi PDO
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lempar Exception jika terjadi error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Kembalikan array asosiatif secara default
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Gunakan native prepared statement untuk proteksi SQL Injection
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Catat log error teknis di sisi server
                error_log('[Database Connection Error] ' . $e->getMessage());

                // Tampilkan pesan yang ramah kepada pengguna tanpa mengekspos kredensial server
                http_response_code(500);
                die('<!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Koneksi Database Gagal - UPSKILL by MATLA</title>
                    <style>
                        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                        .card { background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 480px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
                        h2 { margin-top: 0; color: #dc2626; font-size: 1.25rem; }
                        p { font-size: 0.95rem; line-height: 1.5; color: #475569; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <h2>Gangguan Koneksi Database</h2>
                        <p>Sistem tidak dapat terhubung ke basis data. Pastikan konfigurasi database di <code>config/database.php</code> sudah sesuai dengan server MySQL Anda.</p>
                    </div>
                </body>
                </html>');
            }
        }

        return self::$instance;
    }
}
