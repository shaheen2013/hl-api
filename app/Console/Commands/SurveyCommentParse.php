<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use App\Services\Products\SatisfactionService;

class SurveyCommentParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:survey-comment-parse {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear json parsed strings';
    protected $satisfactionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SatisfactionService $satisfactionService)
    {
        parent::__construct();
        $this->satisfactionService = $satisfactionService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fromID = (int)$this->option('from');
        $toID = (int)$this->option('to');

        if (!$fromID) {
            $style = new OutputFormatterStyle('white', 'black', ['bold']);
            $this->output->getFormatter()->setStyle('example', $style);
            $this->line('<fg=red>Initial ID for parsing data is empty</>');
            $this->line("The ID is the value of the primary key of the user_satisfaction table from where the analysis is going to start\n");
            $this->line("Example:\n<example>php artisan hotelinking:survey-comment-parse --from=330440</example>");

            return false;
        }

        $this->satisfactionService->parseComments($fromID, $toID);
    }
}
