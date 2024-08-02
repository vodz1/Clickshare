<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Sheets;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class FetchGoogleSheetsData extends Command
{
    protected $signature = 'fetch:googlesheets';
    protected $description = 'Fetch data from Google Sheets and store it in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Fetching data from Google Sheets started');

        $client = new Client();
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS_READONLY);

        $service = new Sheets($client);
        $spreadsheetId = config('google-sheets.spreadsheet_id');

        $productRange = 'Products!A2:D';
        $productResponse = $service->spreadsheets_values->get($spreadsheetId, $productRange);
        $productValues = $productResponse->getValues();

        $products = [];
        foreach ($productValues as $index => $row) {
            if ($index === 0) {
                continue;
            }
            if (count($row) >= 4) {
                if (empty($row[0]) || empty($row[3])) {
                    Log::error('Missing name or product code in row:', $row);
                    continue; // Skip this row
                }
    
                $product = Product::updateOrCreate(
                    ['product_code' => $row[3]], // Product Code is in the 4th column (index 3)
                    [
                        'product_name' => $row[0], // Name is in the 1st column (index 0)
                        'description' => $row[1], // Description is in the 2nd column (index 1)
                        'country' => $row[2] // Country is in the 3rd column (index 2)
                    ]
                );
                $products[$row[3]] = $product->id; // Store product ID
            } else {
                Log::error('Row does not have enough columns:', $row);
            }
        }
    
        Log::info('Products fetched:', $products);
    
        $orderRange = 'Orders!A2:F';
        $orderResponse = $service->spreadsheets_values->get($spreadsheetId, $orderRange);
        $orderValues = $orderResponse->getValues();

        foreach ($orderValues as $row) {
            $order = Order::updateOrCreate(
                ['id' => $row[0]],
                [
                    'client_name' => $row[1],
                    'phone_number' => $row[2],
                    'product_code' => $row[3],
                    'final_price' => $row[4],
                    'quantity' => $row[5]
                ]
            );

            $productCode = $row[3];
            if (isset($products[$productCode])) {
                $order->products()->syncWithoutDetaching($products[$productCode]);
            }
        }

        Log::info('Data fetched and stored successfully.');

        $this->info('Data fetched and stored successfully.');
    }
}
