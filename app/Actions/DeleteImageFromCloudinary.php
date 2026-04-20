<?php

namespace App\Actions;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use GuzzleHttp\Client;

class DeleteImageFromCloudinary
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(config('services.cloudinary.url'));
    }

    public function execute(string $publicId): void
    {
        $verifySsl = config('app.env') !== 'local';

        $uploadApi = $this->cloudinary->uploadApi();

        if (! $verifySsl) {
            \Closure::bind(function ($api) {
                $config = $api->apiClient->httpClient->getConfig();
                $config['verify'] = false;
                $api->apiClient->httpClient = new Client($config);
            }, null, UploadApi::class)($uploadApi);
        }

        $uploadApi->destroy($publicId, ['verify' => $verifySsl]);
    }
}
