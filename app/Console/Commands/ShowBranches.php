<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;

class ShowBranches extends Command
{
    protected $signature = 'branches:show';
    protected $description = 'Show all branches';

    public function handle()
    {
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->info('No branches found.');
        } else {
            foreach ($branches as $branch) {
                $this->line("{$branch->id}: {$branch->location}, {$branch->city}");
            }
        }

        return 0;
    }
}
