<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
use Throwable;

class Maintenance extends BaseController
{
    private const MIN_TOKEN_LENGTH = 16;
    private const MAX_FAILED_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 900; // 15 menit

    /**
     * Halaman Maintenance Tools khusus Super Admin (tanpa perlu akses terminal hosting).
     */
    public function index()
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $configuredToken = $this->getConfiguredToken();
        $tokenReady      = strlen($configuredToken) >= self::MIN_TOKEN_LENGTH;

        $migrationStatus = $this->inspectMigrations();
        $db              = Database::connect();
        $trialTableReady = $db->tableExists('trial_quizzes');
        $trialQuizCount  = $trialTableReady ? $db->table('trial_quizzes')->countAllResults() : 0;

        return view('admin/maintenance/index', [
            'title'             => 'Maintenance Tools - MUSLIM UPSKILL ACADEMY',
            'admin_name'        => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'       => session()->get('email') ?? 'admin@matla.id',
            'token_ready'       => $tokenReady,
            'is_https'          => $this->request->isSecure(),
            'environment'       => ENVIRONMENT,
            'php_version'       => PHP_VERSION,
            'migrations_list'   => $migrationStatus['items'],
            'pending_count'     => $migrationStatus['pending_count'],
            'applied_count'     => $migrationStatus['applied_count'],
            'migration_error'   => $migrationStatus['error'],
            'trial_table_ready' => $trialTableReady,
            'trial_quiz_count'  => $trialQuizCount,
        ]);
    }

    /**
     * Menjalankan seluruh migrasi database yang tertunda menggunakan CI4 MigrationRunner native
     * (tidak bergantung pada fungsi shell exec/shell_exec yang sering diblokir di shared hosting).
     */
    public function runMigration()
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        if ($error = $this->validateMaintenanceToken('migrate')) {
            return redirect()->to(base_url('admin/maintenance'))->with('error', $error);
        }

        try {
            $before = $this->getAppliedVersions();

            $runner = Services::migrations();
            $runner->setNamespace('App');
            $runner->latest('default');

            $after      = $this->getAppliedVersions();
            $newlyRan   = array_values(array_diff($after, $before));
            $executedAt = date('d M Y, H:i:s') . ' WIB';

            $this->logAudit('migrate', 'success', count($newlyRan) . ' migrasi dijalankan');

            if (empty($newlyRan)) {
                return redirect()->to(base_url('admin/maintenance'))
                    ->with('info', 'Database sudah mutakhir. Tidak ada file migrasi baru yang perlu dijalankan.')
                    ->with('maintenance_log', [
                        'action' => 'Database Migration (latest)',
                        'time'   => $executedAt,
                        'status' => 'info',
                        'lines'  => ['Status: Seluruh file migrasi sudah terpasang di database.'],
                    ]);
            }

            $lines = array_map(static fn (string $v): string => '[OK] Migrated: ' . $v, $newlyRan);

            return redirect()->to(base_url('admin/maintenance'))
                ->with('success', count($newlyRan) . ' migrasi database berhasil dijalankan!')
                ->with('maintenance_log', [
                    'action' => 'Database Migration (latest)',
                    'time'   => $executedAt,
                    'status' => 'success',
                    'lines'  => $lines,
                ]);
        } catch (Throwable $e) {
            $this->logAudit('migrate', 'failed', $e->getMessage());

            return redirect()->to(base_url('admin/maintenance'))
                ->with('error', 'Migrasi gagal dijalankan: ' . $e->getMessage())
                ->with('maintenance_log', [
                    'action' => 'Database Migration (latest)',
                    'time'   => date('d M Y, H:i:s') . ' WIB',
                    'status' => 'error',
                    'lines'  => ['[ERROR] ' . $e->getMessage()],
                ]);
        }
    }

    /**
     * Membersihkan cache aplikasi CodeIgniter 4, berkas sementara di writable/cache & writable/debugbar, serta OPcache.
     */
    public function clearCache()
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        if ($error = $this->validateMaintenanceToken('clear_cache')) {
            return redirect()->to(base_url('admin/maintenance'))->with('error', $error);
        }

        $lines = [];

        try {
            // 1. Bersihkan cache handler aktif CI4 (kecuali counter rate-limit tidak masalah direset oleh admin terverifikasi)
            Services::cache()->clean();
            $lines[] = '[OK] CodeIgniter Cache handler dibersihkan.';

            // 2. Hapus file cache di writable/cache & writable/debugbar dengan mempertahankan index.html & .htaccess
            $removedCache    = $this->cleanDirectoryFiles(WRITEPATH . 'cache');
            $removedDebugbar = $this->cleanDirectoryFiles(WRITEPATH . 'debugbar');
            $lines[]         = "[OK] Berkas writable/cache dibersihkan ({$removedCache} file).";
            $lines[]         = "[OK] Berkas writable/debugbar dibersihkan ({$removedDebugbar} file).";

            // 3. Reset PHP OPcache jika tersedia di server
            if (function_exists('opcache_reset') && @opcache_reset()) {
                $lines[] = '[OK] PHP OPcache berhasil di-reset.';
            } else {
                $lines[] = '[INFO] PHP OPcache tidak aktif atau tidak memerlukan reset.';
            }

            $this->logAudit('clear_cache', 'success', "cache={$removedCache}, debugbar={$removedDebugbar}");

            return redirect()->to(base_url('admin/maintenance'))
                ->with('success', 'Cache aplikasi berhasil dibersihkan!')
                ->with('maintenance_log', [
                    'action' => 'Clear Application Cache',
                    'time'   => date('d M Y, H:i:s') . ' WIB',
                    'status' => 'success',
                    'lines'  => $lines,
                ]);
        } catch (Throwable $e) {
            $this->logAudit('clear_cache', 'failed', $e->getMessage());

            return redirect()->to(base_url('admin/maintenance'))
                ->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Menjalankan TrialQuizSeeder secara idempoten untuk mengisi contoh 5 soal Ilmu Tauhid & Fiqih.
     */
    public function runTrialQuizSeeder()
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        if ($error = $this->validateMaintenanceToken('seed_trial_quiz')) {
            return redirect()->to(base_url('admin/maintenance'))->with('error', $error);
        }

        $db = Database::connect();
        if (! $db->tableExists('trial_quizzes')) {
            return redirect()->to(base_url('admin/maintenance'))
                ->with('warning', 'Tabel trial_quizzes belum ada. Silakan jalankan Database Migration terlebih dahulu.');
        }

        try {
            $beforeCount = $db->table('trial_quizzes')->countAllResults();
            Database::seeder()->call('TrialQuizSeeder');
            $afterCount  = $db->table('trial_quizzes')->countAllResults();
            $added       = max(0, $afterCount - $beforeCount);

            $this->logAudit('seed_trial_quiz', 'success', "added={$added}");

            if ($added === 0) {
                return redirect()->to(base_url('admin/maintenance'))
                    ->with('info', 'Data contoh Trial Quiz sudah tersedia sebelumnya (tidak ada duplikasi dibuat).');
            }

            return redirect()->to(base_url('admin/maintenance'))
                ->with('success', "{$added} contoh Trial Quiz berhasil diisi ke database!")
                ->with('maintenance_log', [
                    'action' => 'TrialQuizSeeder',
                    'time'   => date('d M Y, H:i:s') . ' WIB',
                    'status' => 'success',
                    'lines'  => [
                        '[OK] Tes Pemahaman Ilmu Tauhid Dasar (5 soal + pembahasan)',
                        '[OK] Evaluasi Fiqih Sholat Sehari-hari (3 soal + pembahasan)',
                    ],
                ]);
        } catch (Throwable $e) {
            $this->logAudit('seed_trial_quiz', 'failed', $e->getMessage());

            return redirect()->to(base_url('admin/maintenance'))
                ->with('error', 'Gagal menjalankan seeder: ' . $e->getMessage());
        }
    }

    /**
     * Pengaman berlapis di dalam controller (selain filter auth & role:super_admin).
     */
    private function ensureSuperAdmin()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'super_admin') {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Akses ditolak. Menu Maintenance Tools hanya untuk Super Admin.');
        }

        return null;
    }

    /**
     * Mengambil MAINTENANCE_TOKEN dari .env tanpa fallback hardcoded (fail-closed).
     */
    private function getConfiguredToken(): string
    {
        $token = (string) env('MAINTENANCE_TOKEN', '');
        if ($token === '') {
            $token = (string) env('maintenance.token', '');
        }

        return trim($token);
    }

    /**
     * Memvalidasi token dengan hash_equals (anti timing-attack) + rate limit brute-force (5x gagal / 15 menit).
     */
    private function validateMaintenanceToken(string $action): ?string
    {
        $expectedToken = $this->getConfiguredToken();
        if (strlen($expectedToken) < self::MIN_TOKEN_LENGTH) {
            $this->logAudit($action, 'blocked', 'MAINTENANCE_TOKEN belum dikonfigurasi atau terlalu pendek');

            return 'MAINTENANCE_TOKEN belum diatur di file .env (wajib minimal 16 karakter acak). Fitur dinonaktifkan demi keamanan.';
        }

        $rateKey  = $this->getRateLimitKey();
        $cache    = Services::cache();
        $attempts = (int) ($cache->get($rateKey) ?? 0);

        if ($attempts >= self::MAX_FAILED_ATTEMPTS) {
            $this->logAudit($action, 'rate_limited', 'Melebihi batas percobaan token salah');

            return 'Terlalu banyak percobaan token salah. Silakan tunggu 15 menit sebelum mencoba lagi.';
        }

        $submittedToken = trim((string) $this->request->getPost('maintenance_token'));
        if ($submittedToken === '' || ! hash_equals($expectedToken, $submittedToken)) {
            $newAttempts = $attempts + 1;
            $cache->save($rateKey, $newAttempts, self::LOCKOUT_SECONDS);
            $remaining = max(0, self::MAX_FAILED_ATTEMPTS - $newAttempts);

            $this->logAudit($action, 'invalid_token', "attempt={$newAttempts}/" . self::MAX_FAILED_ATTEMPTS);

            if ($remaining === 0) {
                return 'MAINTENANCE_TOKEN tidak sesuai. Akses dikunci sementara selama 15 menit.';
            }

            return "MAINTENANCE_TOKEN tidak sesuai. Sisa percobaan: {$remaining}x.";
        }

        // Token valid -> reset counter kegagalan
        $cache->delete($rateKey);

        return null;
    }

    private function getRateLimitKey(): string
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        $ip     = (string) $this->request->getIPAddress();

        return 'maint_fail_' . md5($userId . '|' . $ip);
    }

    /**
     * Memeriksa daftar file migrasi di app/Database/Migrations dibandingkan dengan tabel migrations.
     */
    private function inspectMigrations(): array
    {
        $items        = [];
        $pendingCount = 0;
        $appliedCount = 0;
        $error        = null;

        try {
            $runner = Services::migrations();
            $runner->setNamespace('App');
            $allFiles = $runner->findMigrations();
            $applied  = array_flip($this->getAppliedVersions());

            foreach ($allFiles as $uid => $mig) {
                $version   = (string) ($mig->version ?? $uid);
                $className = (string) ($mig->class ?? $uid);
                $shortName = basename(str_replace('\\', '/', $className));
                $isApplied = isset($applied[$version]);

                if ($isApplied) {
                    $appliedCount++;
                } else {
                    $pendingCount++;
                }

                $items[] = [
                    'version' => $version,
                    'name'    => $shortName,
                    'applied' => $isApplied,
                ];
            }

            usort($items, static fn (array $a, array $b): int => strcmp($b['version'], $a['version']));
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        return [
            'items'         => $items,
            'pending_count' => $pendingCount,
            'applied_count' => $appliedCount,
            'error'         => $error,
        ];
    }

    private function getAppliedVersions(): array
    {
        $db = Database::connect();
        if (! $db->tableExists('migrations')) {
            return [];
        }

        $rows = $db->table('migrations')
            ->select('version')
            ->where('namespace', 'App')
            ->get()
            ->getResultArray();

        return array_map(static fn (array $r): string => (string) $r['version'], $rows);
    }

    private function cleanDirectoryFiles(string $dir): int
    {
        if (! is_dir($dir)) {
            return 0;
        }

        $count = 0;
        $files = @scandir($dir);
        if (! is_array($files)) {
            return 0;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === 'index.html' || $file === '.htaccess') {
                continue;
            }

            $fullPath = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            if (is_file($fullPath) && @unlink($fullPath)) {
                $count++;
            }
        }

        return $count;
    }

    private function logAudit(string $action, string $status, string $detail): void
    {
        $level = in_array($status, ['invalid_token', 'rate_limited', 'blocked', 'failed'], true) ? 'warning' : 'notice';
        log_message($level, '[MaintenanceTools] action={action} status={status} user_id={uid} email={email} ip={ip} detail={detail}', [
            'action' => $action,
            'status' => $status,
            'uid'    => (int) (session()->get('user_id') ?? 0),
            'email'  => (string) (session()->get('email') ?? '-'),
            'ip'     => (string) $this->request->getIPAddress(),
            'detail' => $detail,
        ]);
    }
}

