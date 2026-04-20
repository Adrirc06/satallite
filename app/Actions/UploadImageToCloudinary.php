<?php

namespace App\Actions;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class UploadImageToCloudinary
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(config('services.cloudinary.url'));
    }

    public function execute(UploadedFile $file, string $folder = 'default'): array
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

        $result = $uploadApi->upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }
}
