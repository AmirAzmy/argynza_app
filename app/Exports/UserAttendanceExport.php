<?php

namespace App\Exports;

use App\Models\User\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserAttendanceExport implements FromCollection
{
    private $userId;
    private $date;

    public function __construct($userId, $date)
    {
        $this->userId = $userId;
        $this->date = $date;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = User::find($this->userId);
        return $user->attendances()
            ->whereMonth('day', Carbon::parse($this->date)->format('m'))
            ->get();
    }
}
