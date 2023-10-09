<?php

declare(strict_types=1);

namespace App\Utils;

final class KrakenMessageSigner
{
    /**
     * @param  array<string, mixed>  $request
     */
    public static function signMessage(string $path, array $request, int $nonce): string
    {
        $privateKey = config('app.kraken.apiSecret');

        if (!\is_string($privateKey)) {
            throw new \InvalidArgumentException('Invalid private key');
        }

        $message = http_build_query($request);
        $secret_buffer = base64_decode($privateKey);
        $hash = hash_init('sha256');
        hash_update($hash, $nonce.$message);
        $hash_digest = hash_final($hash, true);
        $hmac = hash_hmac('sha512', $path.$hash_digest, $secret_buffer, true);

        return base64_encode($hmac);
    }
}
