<?php

namespace App\Http\Services\User;

use App\Exports\SiteAttendanceExport;
use App\Exports\UserAttendanceExport;
use App\Models\Attendance;
use App\Models\Project\Site;
use App\Models\User\PushToken;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceService
{
    public function userAttendances(Request $request)
    {
        $attendances = Attendance::where('user_id', $request->user_id)
            ->orderBy('id', 'desc');

        $attendances->whereMonth('day', Carbon::parse($request->date)
            ->format('m'));
        return $attendances->paginate(30);
    }

    public function siteAttendances(Request $request)
    {
        $users = User::whereHas('attendances', function ($attendance) use ($request) {
            $attendance->where('site_id', $request->site_id);
        })
            ->with([
                'attendances' => function ($attendance) use ($request) {
                    $attendance->whereMonth('day', Carbon::parse($request->date)->format('m'));
                }
            ]);
        return $users->paginate(30);
    }

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
        $this->savePushToken($request);
        $site = Site::findOrFail($request->site_id);
        $distance = $this->calculateDistance((double) $site->lat, (double) $site->lng,
            (double) $request->lat, (double) $request->lng);
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
        Excel::store(new UserAttendanceExport($request->user_id, $request->date),
            'excel/user_attendance.xlsx', 'uploads');
        return [
            'file' => url('uploads/excel/user_attendance.xlsx')
        ];
    }

    public function exportSiteAttendance(Request $request)
    {
        Excel::store(new SiteAttendanceExport($request->site_id, $request->date),
            'excel/site_attendance.xlsx', 'uploads');
        return [
            'file' => url('uploads/excel/site_attendance.xlsx')
        ];
    }

    public function savePushToken($request)
    {
        $headers = $request->header();
        $data = [
            'user_id'     => Auth::id() ?? 0,
            'udid'        => $headers['device-udid'][0] ?? '',
            'device_type' => $headers['device-type'][0] ?? '',
            'device_name' => $headers['device-name'][0] ?? '',
            'os_version'  => $headers['device-os-version'][0] ?? '',
            'app_version' => $headers['app-version'][0] ?? '',
            'send_push'   => 0
        ];
        $pushToken = PushToken::where('push_token', $headers['device-push-token'][0])->first();
        if ($pushToken) {
            return $pushToken->update($data);
        }
        $data['push_token'] = $headers['device-push-token'][0];
        return PushToken::create($data);
    }

}
