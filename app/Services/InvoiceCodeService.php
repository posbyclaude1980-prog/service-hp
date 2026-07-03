<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Picqer\Barcode\BarcodeGeneratorPNG;

class InvoiceCodeService
{
    public function qrCodeBase64(string $data, int $size = 140): string
    {
        $result = (new Builder())->build(
            data: $data,
            size: $size,
            margin: 8,
        );

        return base64_encode($result->getString());
    }

    public function barcodeBase64(string $code, int $widthFactor = 2, int $height = 50): string
    {
        $generator = new BarcodeGeneratorPNG();

        return base64_encode(
            $generator->getBarcode($code, $generator::TYPE_CODE_128, $widthFactor, $height)
        );
    }
}
