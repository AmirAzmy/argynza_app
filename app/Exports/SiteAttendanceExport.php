<?php

namespace App\Exports;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SiteAttendanceExport implements FromView
{
    private $siteId;
    private $date;

    public function __construct($siteId, $date)
    {
        $this->siteId = $siteId;
        $this->date = $date;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function view(): View
    {
        $users = User::whereHas('attendances', function ($attendance) {
            $attendance->where('site_id', $this->siteId);
        })
            ->with([
                'attendances' => function ($attendance) {
                    $attendance->whereMonth('day', Carbon::parse($this->date)->format('m'));
                }
            ])
            ->get();
        return view('attendances', [
            'users' => $users
        ]);
    }
}
