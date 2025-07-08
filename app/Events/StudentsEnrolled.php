<?php

namespace App\Events;

use App\Models\ClassSection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentsEnrolled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ClassSection $classSection,
        public array $changes // ['attached' => [], 'detached' => []]
    ) {}
}