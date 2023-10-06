<?php

declare(strict_types=1);

namespace App\Services;

final class KrakenMessageSigner
{
    public static function signMessage(string $path, array $request, int $nonce): string
    {
        $message = http_build_query($request);
        $secret_buffer = base64_decode(config('app.kraken.apiSecret'));
        $hash = hash_init('sha256');
        hash_update($hash, $nonce.$message);
        $hash_digest = hash_final($hash, true);
        $hmac = hash_hmac('sha512', $path.$hash_digest, $secret_buffer, true);

        return base64_encode($hmac);
    }
}
