<?php

namespace App\Console\Commands;

use App\Hotel;
use App\Cadena;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateNotValidMandrillEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:update-not-valid-mandrill-emails {domains*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The list of requested domains is reviewed with the mandrill API and all the hotels that use them are updated with an email from hotelinking.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $output = new ConsoleOutput();
        $domains = $this->argument('domains');
        $totalDomains = count($domains);
        $notValidDomains = [];
        $progress = new ProgressBar($output, $totalDomains);
        $progress->start();

        foreach ($domains as $domain) {
            try {
                $response = $client->post('https://mandrillapp.com/api/1.0/senders/check-domain', [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'body' => '{"key":"' . config("services.mandrill.key") . '","domain":"' . $domain . '"}'
                ]);
        
                if ($response->getStatusCode() === 200) {
                    $response = json_decode($response->getBody(), true);
                    $keysToCheck = ['spf.valid', 'dkim.valid', 'dkim2.valid', 'dmarc.valid', 'valid_signing'];
        
                    $isValidDomain = collect($keysToCheck)->every(function ($key) use ($response) {
                        return Arr::get($response, $key, false) === true;
                    });
        
                    if (!$isValidDomain) {
                        $hotels = Hotel::where('sending_email', 'LIKE', '%' . $domain);
                        $previousHotelsValues = $hotels->get()->map->only(['id', 'sending_email'])->values();
                        $hotels->update(['sending_email' => 'noreply@hotelinking.com']);

    
                        $chains = Cadena::where('email_envio', 'LIKE', '%' . $domain);
                        $previousChainValues = $chains->get()->map->only(['id', 'email_envio'])->values();
                        $chains->update(['email_envio' => 'noreply@hotelinking.com']);

                        array_push($notValidDomains, ["domain" => $domain, "hotels_updated" => $previousHotelsValues, "chains_updated" => $previousChainValues]);
                    }
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                Log::error("Error on mandrill api call", ["domain" => $domain, "exception" => $e]);
            }
            

            $progress->advance();
        }

        $output->write(PHP_EOL);
        $values = [$totalDomains, $totalDomains - count($notValidDomains), count($notValidDomains)];
        $this->table(["Total", "Valid", "Not valid"], [$values]);
        $output->write(PHP_EOL);

        $filename = '/invalid-domains' . str_replace('.', '-', microtime(true)) . '.tmp';
        $localFile = sys_get_temp_dir() . $filename;
        $csv = fopen($localFile, 'w+');
        fprintf($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($csv, ["Domain", "Hotels Updated", "Chains Updated"], ";");

        foreach ($notValidDomains as $domain) {
            $row = [
                data_get($domain, 'domain'),
                data_get($domain, 'hotels_updated'),
                data_get($domain, 'chains_updated'),
            ];

            fputcsv($csv, $row, ";");
        }

        fclose($csv);

        $output->write(file_get_contents($localFile));

        $progress->finish();
        $output->write(PHP_EOL);
    }
}
