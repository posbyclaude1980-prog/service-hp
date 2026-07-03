<?php

namespace App\Enums;

enum ServiceWorkAction: string
{
    case Diagnosis = 'diagnosis';
    case StatusChange = 'status_change';
    case SparePart = 'spare_part';
    case ServiceFee = 'service_fee';
    case Photo = 'photo';
    case Video = 'video';
    case Note = 'note';
    case QcPass = 'qc_pass';
    case QcFail = 'qc_fail';

    public function label(): string
    {
        return match ($this) {
            self::Diagnosis => 'Diagnosa',
            self::StatusChange => 'Perubahan Status',
            self::SparePart => 'Spare Part',
            self::ServiceFee => 'Biaya Jasa',
            self::Photo => 'Upload Foto',
            self::Video => 'Upload Video',
            self::Note => 'Catatan',
            self::QcPass => 'QC Lulus',
            self::QcFail => 'QC Gagal',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Diagnosis => 'bi-search',
            self::StatusChange => 'bi-arrow-repeat',
            self::SparePart => 'bi-box-seam',
            self::ServiceFee => 'bi-currency-dollar',
            self::Photo => 'bi-camera',
            self::Video => 'bi-camera-video',
            self::Note => 'bi-chat-left-text',
            self::QcPass => 'bi-check-circle',
            self::QcFail => 'bi-x-circle',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Diagnosis => 'info',
            self::StatusChange => 'primary',
            self::SparePart => 'secondary',
            self::ServiceFee => 'warning',
            self::Photo => 'dark',
            self::Video => 'dark',
            self::Note => 'light',
            self::QcPass => 'success',
            self::QcFail => 'danger',
        };
    }
}
