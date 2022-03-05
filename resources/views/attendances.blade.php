<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <th>الاسم</th>
        <th>التليفون</th>
        <th>اليوم</th>
        <th>الحضور</th>
        <th>الانصراف</th>

    </tr>
    @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->phone}}</td>
            <td>
                <table>
                    <tr>
                        <th>اليوم</th>
                        <th>الحضور</th>
                        <th>الانصراف</th>
                    </tr>
                    @foreach($user->attendances as $attendance)
                        <tr>
                            <td>{{$attendance->day}}</td>
                            <td>{{$attendance->checkin}}</td>
                            <td>{{$attendance->checkout}}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>

