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

        // Fetch product data
        $productRange = 'Products!A2:D';
        $productResponse = $service->spreadsheets_values->get($spreadsheetId, $productRange);
        $productValues = $productResponse->getValues();

        $products = [];
        foreach ($productValues as $row) {
            $product = Product::updateOrCreate(
                ['product_code' => $row[3]], // Assuming product code is unique
                [
                    'product_name' => $row[0], 
                    'description' => $row[1], 
                    'country' => $row[2]
                ]
            );
            $products[$row[3]] = $product->id; // Store product ID
        }
    
        Log::info('Products fetched:', $products);
    
        // Fetch order data
        $orderRange = 'Orders!A2:F';
        $orderResponse = $service->spreadsheets_values->get($spreadsheetId, $orderRange);
        $orderValues = $orderResponse->getValues();

        foreach ($orderValues as $row) {
            $orderData = [
                'client_name' => $row[1],
                'phone_number' => $row[2],
                'product_code' => $row[3],
                'final_price' => $row[4],
                'quantity' => $row[5]
            ];

            $order = Order::where('phone_number', $orderData['phone_number'])->first();

            if ($order) {
                // Update existing order
                $order->update($orderData);
            } else {
                // Create a new order
                $order = Order::create($orderData);
            }

            $productCode = $row[3];
            if (isset($products[$productCode])) {
                $order->products()->syncWithoutDetaching($products[$productCode]);
            }
        }

        Log::info('Data fetched and stored successfully.');

        $this->info('Data fetched and stored successfully.');
    }}
