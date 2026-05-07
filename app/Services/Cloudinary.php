<?php

namespace App\Services;

class Cloudinary
{
    public static function upload($file, array $options = [])
    {
        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
        $response = $cloudinary->uploadApi()->upload($file, $options);

        return new class($response) {
            private $response;

            public function __construct($response)
            {
                $this->response = $response;
            }

            public function getSecurePath()
            {
                return $this->response['secure_url'] ?? null;
            }
        };
    }
}
