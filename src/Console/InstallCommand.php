<?php

namespace LaraZeus\Replies\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'replies:install';

    protected $description = 'install replies plugin';

    public function handle(): void
    {
        $this->info('publishing migrations...');
        $this->call('vendor:publish', ['--tag' => 'zeus-replies-migrations']);

        $this->info('publishing configuration...');
        $this->call('vendor:publish', ['--tag' => 'zeus-replies-config']);

        if ($this->confirm('Do you want to run the migration now?', true)) {
            $this->info('running migrations...');
            $this->call('migrate');
        }

        $this->output->success('Zeus Replies has been Installed successfully, consider ⭐️ the package in filament site :)');
    }
}
