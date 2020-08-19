<?php


namespace App\Console\Commands;


use App\Models\Good;
use Illuminate\Console\Command;
use Elasticsearch\Client;
use Manticoresearch\Client as ManticoreClient;

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

    /** @var ManticoreClient */
    private $client;

    /**
     * ReindexCommand constructor.
     * @param Client $elasticsearch
     * @param ManticoreClient $client
     */
    public function __construct(Client $elasticsearch, ManticoreClient $client)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
        $this->client = $client;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all goods. This might take a while...');

        $index = $this->client->index('goods');
        $index->drop();
        $index->create([
            'name' => ['type'=>'text'],
            'description' => ['type'=>'text'],
            'quantity' => ['type'=>'integer'],
            'categories' => ['type'=>'json']
        ]);

        foreach (Good::cursor() as $good)
        {
            $this->elasticsearch->index([
                'index' => $good->getSearchIndex(),
                'type' => $good->getSearchType(),
                'id' => $good->getKey(),
                'body' => $good->toSearchArray(),
            ]);
            $this->output->write('.');

            $index->addDocument([
                'name' => $good->name,
                'description' => $good->description,
                'quantity' => (int)$good->quantity,
                'categories' => json_encode($good->categories)
            ], (int)$good->id);
        }
        $this->info('\nDone!');
    }
}
