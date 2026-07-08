<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database {--keep=14 : Number of most recent backups to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump the database to storage/app/backups and prune old backups';

    public function handle(): int
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($config['driver'] !== 'mysql') {
            $this->error("Backup command currently only supports mysql (connection driver: {$config['driver']}).");

            return self::FAILURE;
        }

        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);

        $filename = sprintf('%s_%s.sql', $config['database'], now()->format('Y_m_d_His'));
        $path = "{$directory}/{$filename}";

        $process = new Process([
            'mysqldump',
            '--host='.$config['host'],
            '--port='.$config['port'],
            '--user='.$config['username'],
            '--password='.$config['password'],
            '--single-transaction',
            '--skip-lock-tables',
            $config['database'],
        ]);

        $process->setTimeout(300);
        $process->run(function ($type, $buffer) use ($path) {
            if ($type === Process::OUT) {
                File::append($path, $buffer);
            }
        });

        if (! $process->isSuccessful()) {
            $this->error('Backup failed: '.$process->getErrorOutput());
            File::delete($path);

            return self::FAILURE;
        }

        $this->info("Backup written to {$path}");

        $this->pruneOldBackups($directory, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    private function pruneOldBackups(string $directory, int $keep): void
    {
        $files = collect(File::files($directory))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values();

        foreach ($files->slice($keep) as $old) {
            File::delete($old->getPathname());
            $this->line("Pruned old backup: {$old->getFilename()}");
        }
    }
}
