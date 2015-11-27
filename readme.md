# Laravel Integration

Add this line in the `providers` array in `config/app.php`:

    'providers' => [
        // Other Service Providers
    
        'Coreproc\Enom\Providers\EnomServiceProvider::class',
    ],
    
Add these lines in the `facades` array in `config/app.php`:

    'facades' => [
        // Other Facades
    
        'Tld' => 'Coreproc\Enom\Facades\Tld::class',
        'Domain' => 'Coreproc\Enom\Facades\Domain::class',
    ],
    
Then run this command to publish the config file:

    php artisan vendor:publish --provider="Coreproc\Enom\Providers\EnomServiceProvider" --tag="config"