<?php

namespace App\Livewire;

use App\Models\InfrastructureUnit;
use Livewire\Component;

class QrScanner extends Component
{
    public bool $isOpen    = false;
    public ?string $result = null;
    public ?array $unit    = null;
    public string $error   = '';

    public function openScanner(): void
    {
        $this->isOpen = true;
        $this->result = null;
        $this->unit   = null;
        $this->error  = '';
    }

    public function closeScanner(): void
    {
        $this->isOpen = false;
        $this->result = null;
        $this->unit   = null;
        $this->error  = '';
    }

    public function processQr(string $code): void
    {
        // ✅ Ekstrak kode dari URL kalau scan URL
        if (str_contains($code, '/unit/')) {
            $code = last(explode('/unit/', $code));
        }

        $unit = InfrastructureUnit::with([
            'infrastructure.location',
            'infrastructure.category',
            'logs' => fn($q) => $q->latest()->limit(5),
            'logs.request',
        ])->where('code', $code)->first();

        if (!$unit) {
            $this->error = 'Unit tidak ditemukan: ' . $code;
            $this->unit  = null;
            return;
        }

        $this->error  = '';
        $this->result = $code;
        $this->unit   = [
            'id'       => $unit->id,
            'code'     => $unit->code,
            'status'   => $unit->status,
            'note'     => $unit->note,
            'location' => $unit->infrastructure->location->name,
            'category' => $unit->infrastructure->category->name,
            'name'     => $unit->infrastructure->name,
            'logs'     => $unit->logs->map(fn($log) => [
                'type'           => $log->type,
                'note'           => $log->note,
                'requester_name' => $log->request?->requester_name ?? '-',
                'created_at'     => $log->created_at->translatedFormat('d M Y, H:i'),
            ])->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
