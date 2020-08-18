<?php


namespace App\Console\Commands;


use App\Models\Good;
use Illuminate\Console\Command;
use Elasticsearch\Client;

class ReindexCommand  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all goods to Elasticsearch';
    /** @var Client */
    private $elasticsearch;

    /**
     * Create a new command instance.
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all goods. This might take a while...');
        foreach (Good::cursor() as $good)
        {
            $this->elasticsearch->index([
                'index' => $good->getSearchIndex(),
                'type' => $good->getSearchType(),
                'id' => $good->getKey(),
                'body' => $good->toSearchArray(),
            ]);
            $this->output->write('.');
        }
        $this->info('\nDone!');
    }
}
