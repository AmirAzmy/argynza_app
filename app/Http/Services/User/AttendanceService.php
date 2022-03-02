<?php

namespace App\Http\Services\User;

use App\Exports\UsersExport;
use App\Models\Attendance;
use App\Models\Project\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceService
{
    /**
     * checkin and checkout
     * @param  Request  $request
     * @return mixed
     */
    public function checkInAndOut(Request $request)
    {
        $this->checkLocation($request);
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('day', now()->format('Y-m-d'))
            ->first();
        if ($attendance && $attendance->checkout != null) {
            throw new BadRequestHttpException(Lang::get('messages.attendance_error_msg'));
        }
        if ($attendance) {
            $attendance->update(['checkout' => now()->format('h:i:s')]);
            return $attendance;
        }
        return Attendance::create([
            'site_id' => $request->site_id,
            'user_id' => Auth::id(), 'day' => now()->format('Y-m-d'),
            'checkin' => now()->format('h:i:s'), 'checkout' => null
        ]);
    }

    /**
     * Check Location
     * @param  Request  $request
     * @return int[]
     */
    public function checkLocation(Request $request)
    {
        $site = Site::findOrFail($request->site_id);
        $distance = $this->calculateDistance((double) $site->lat, (double) $site->lng,
            (double) $request->lat, (double) $request->long);
        if ($distance > ($site->redius * 1000)) {
            throw new BadRequestHttpException(Lang::get('messages.location_error_msg'));
        }
        return ["on_site" => 1];
    }

    /**
     * Get Distance Between Two Locations
     * @param $latitudeFrom
     * @param $longitudeFrom
     * @param $latitudeTo
     * @param $longitudeTo
     * @return float
     */
    function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo): float
    {
        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad)
            * sin($latitudeTo * $rad) + cos($latitudeFrom * $rad)
            * cos($latitudeTo * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 * 1.853;
    }

    public function exportUserAttendance(Request $request)
    {
        return Excel::download(new UsersExport($request->user_id), 'user_attendance.xlsx');
    }
}
