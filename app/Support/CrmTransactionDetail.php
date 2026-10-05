<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Shared field formatting + section layout for transaction detail pages.
 * Used by POS / Branch / Direct receive & send detail screens.
 */
class CrmTransactionDetail
{
    public static function formatAmount($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $raw = trim((string) $value);
        $normalized = str_replace([',', ' '], '', $raw);

        if (! is_numeric($normalized)) {
            return $raw;
        }

        return number_format((float) $normalized, 0, '.', ',');
    }

    public static function formatGender($value): string
    {
        $raw = strtoupper(trim((string) $value));

        return match ($raw) {
            'F', 'FEMALE', 'P', 'PEREMPUAN' => 'Female',
            'M', 'MALE', 'L', 'LAKI-LAKI', 'LAKI_LAKI' => 'Male',
            default => self::humanizeLabel($value),
        };
    }

    public static function humanizeLabel($value): string
    {
        $text = trim((string) ($value ?? ''));
        if ($text === '') {
            return '';
        }

        return ucwords(strtolower(str_replace(['_', '-'], ' ', $text)));
    }

    public static function formatDateOnly($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $text = trim((string) $value);

        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $text, $m)) {
            return $m[1];
        }

        try {
            return Carbon::parse($text)->format('Y-m-d');
        } catch (\Throwable $e) {
            return $text;
        }
    }

    public static function formatDateMinute($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $text = trim((string) $value);

        if (preg_match('/^(\d{4}-\d{2}-\d{2})[ T](\d{2}):(\d{2})/', $text, $m)) {
            return $m[1].' '.$m[2].':'.$m[3];
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
            return $text;
        }

        try {
            return Carbon::parse($text)->format('Y-m-d H:i');
        } catch (\Throwable $e) {
            return $text;
        }
    }

    public static function receiveSections($tx, array $extra = []): array
    {
        $amount = $tx->bsubanyak ?? $tx->amount ?? null;
        $branch = $extra['branch'] ?? ($tx->agent_nama ?? null);
        if (is_string($branch)) {
            $branch = str_replace('ARTHAPOS', 'BRANCH', $branch);
        }

        $transactionFields = [
            ['label' => 'Reference No', 'value' => $tx->no_referensi ?? null],
            ['label' => 'BSU', 'value' => self::formatAmount($amount)],
            ['label' => 'Date', 'value' => self::formatDateMinute($tx->tanggal ?? null)],
            ['label' => 'Id Pos', 'value' => $tx->id_pos ?? null],
            ['label' => 'Purpose of Funds', 'value' => $tx->tujuan_penggunaan_dana ?? null, 'wide' => true],
            ['label' => 'Relationship', 'value' => self::humanizeLabel($tx->hubungan ?? null)],
        ];

        if ($branch !== null && $branch !== '') {
            $transactionFields[] = ['label' => 'Branch', 'value' => $branch];
        }
        if (! empty($extra['maker']) || ! empty($tx->maker)) {
            $transactionFields[] = ['label' => 'Maker', 'value' => $extra['maker'] ?? $tx->maker];
        }
        if (! empty($extra['approver']) || ! empty($tx->approver)) {
            $transactionFields[] = ['label' => 'Approver', 'value' => $extra['approver'] ?? $tx->approver];
        }

        return [
            [
                'title' => __('crm.tx_section_transaction'),
                'fields' => $transactionFields,
            ],
            [
                'title' => __('crm.tx_section_recipient'),
                'fields' => [
                    ['label' => 'Name', 'value' => $tx->nama_penerima ?? null],
                    ['label' => 'Phone', 'value' => $tx->telepon_penerima ?? null],
                    ['label' => 'City', 'value' => $tx->kota_penerima ?? null],
                    ['label' => 'Nationality', 'value' => $tx->kewarganegaraan ?? null],
                    ['label' => 'Identity', 'value' => self::humanizeLabel($tx->identitas ?? null)],
                    ['label' => 'ID Number', 'value' => $tx->nomor_identitas ?? null],
                    ['label' => 'Date of Birth', 'value' => self::formatDateOnly($tx->tanggal_lahir ?? null)],
                    ['label' => 'Place of Birth', 'value' => $tx->kota_kelahiran ?? null],
                    ['label' => 'Gender', 'value' => self::formatGender($tx->jenis_kelamin ?? null)],
                    ['label' => 'Occupation', 'value' => self::humanizeLabel($tx->pekerjaan ?? null)],
                    ['label' => 'ID Address', 'value' => $tx->alamat_penerima ?? null, 'wide' => true],
                    ['label' => 'Domicile Address', 'value' => $tx->alamat_domisili_penerima ?? null, 'wide' => true],
                ],
            ],
            [
                'title' => __('crm.tx_section_sender'),
                'fields' => [
                    ['label' => 'Name', 'value' => $tx->nama_pengirim ?? null],
                    ['label' => 'Country', 'value' => $tx->negara_pengirim ?? null],
                ],
            ],
        ];
    }

    public static function sendSections($tx, array $extra = []): array
    {
        $branch = $extra['branch'] ?? ($tx->agent_nama ?? null);
        if (is_string($branch)) {
            $branch = str_replace('ARTHAPOS', 'BRANCH', $branch);
        }

        $transactionFields = [
            ['label' => 'Reference No', 'value' => $tx->no_referensi ?? null],
            ['label' => 'Recipient Country', 'value' => $tx->negara_penerima ?? null],
            ['label' => 'BSU', 'value' => self::formatAmount($tx->amount ?? null)],
            ['label' => 'Fee', 'value' => self::formatAmount($tx->sending_fee ?? null)],
            ['label' => 'Total Collect', 'value' => self::formatAmount($tx->total_collect_amount ?? null)],
            ['label' => 'Nopend', 'value' => $tx->id_pos ?? null],
            ['label' => 'Transfer Type', 'value' => $tx->opt_display ?? null],
            ['label' => 'Date', 'value' => self::formatDateMinute($tx->tanggal ?? null)],
            ['label' => 'Purpose of Funds', 'value' => $tx->tujuan_penggunaan_dana ?? null, 'wide' => true],
            ['label' => 'Relationship', 'value' => self::humanizeLabel($tx->hubungan_pengirim ?? null)],
        ];

        if ($branch !== null && $branch !== '') {
            $transactionFields[] = ['label' => 'Branch', 'value' => $branch];
        }
        if (! empty($extra['maker']) || ! empty($tx->maker)) {
            $transactionFields[] = ['label' => 'Maker', 'value' => $extra['maker'] ?? $tx->maker];
        }
        if (! empty($extra['approver']) || ! empty($tx->approver)) {
            $transactionFields[] = ['label' => 'Approver', 'value' => $extra['approver'] ?? $tx->approver];
        }

        return [
            [
                'title' => __('crm.tx_section_transaction'),
                'fields' => $transactionFields,
            ],
            [
                'title' => __('crm.tx_section_sender'),
                'fields' => [
                    ['label' => 'Name', 'value' => $tx->nama_pengirim ?? null],
                    ['label' => 'Phone', 'value' => $tx->telpon_pengirim ?? null],
                    ['label' => 'Identity', 'value' => self::humanizeLabel($tx->identitas ?? null)],
                    ['label' => 'ID Number', 'value' => $tx->nomor_identitas ?? null],
                    ['label' => 'Date of Birth', 'value' => self::formatDateOnly($tx->tanggal_lahir ?? null)],
                    ['label' => 'Place of Birth', 'value' => $tx->kota_lahir_pengirim ?? null],
                    ['label' => 'Gender', 'value' => self::formatGender($tx->jenis_kelamin ?? null)],
                    ['label' => 'Nationality', 'value' => $tx->negara_domisili_pengirim ?? null],
                    ['label' => 'Occupation', 'value' => self::humanizeLabel($tx->pekerjaan ?? null)],
                    ['label' => 'ID Address', 'value' => $tx->alamat_pengirim ?? null, 'wide' => true],
                    ['label' => 'Domicile Address', 'value' => $tx->alamat_domisili_pengirim ?? null, 'wide' => true],
                ],
            ],
            [
                'title' => __('crm.tx_section_recipient'),
                'fields' => [
                    ['label' => 'Name', 'value' => $tx->nama_penerima ?? null],
                    ['label' => 'Phone', 'value' => $tx->telepon_penerima ?? null],
                    ['label' => 'City', 'value' => $tx->kota_penerima ?? null],
                    ['label' => 'Address', 'value' => $tx->alamat_penerima ?? null, 'wide' => true],
                ],
            ],
        ];
    }

    public static function receiveHighlights($tx): array
    {
        $amount = $tx->bsubanyak ?? $tx->amount ?? null;

        return [
            ['label' => __('crm.tx_ref'), 'value' => $tx->no_referensi ?? null],
            ['label' => __('crm.tx_bsu'), 'value' => self::formatAmount($amount), 'emphasis' => true],
            ['label' => __('crm.tx_date'), 'value' => self::formatDateMinute($tx->tanggal ?? null)],
            ['label' => __('crm.tx_sender_country'), 'value' => $tx->negara_pengirim ?? null],
            ['label' => __('crm.tx_id_pos'), 'value' => $tx->id_pos ?? null],
        ];
    }

    public static function sendHighlights($tx): array
    {
        return [
            ['label' => __('crm.tx_ref'), 'value' => $tx->no_referensi ?? null],
            ['label' => __('crm.tx_bsu'), 'value' => self::formatAmount($tx->amount ?? null), 'emphasis' => true],
            ['label' => __('crm.tx_fee'), 'value' => self::formatAmount($tx->sending_fee ?? null)],
            ['label' => __('crm.tx_date'), 'value' => self::formatDateMinute($tx->tanggal ?? null)],
            ['label' => __('crm.tx_recipient_country'), 'value' => $tx->negara_penerima ?? null],
        ];
    }
}
