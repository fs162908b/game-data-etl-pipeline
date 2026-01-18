<!DOCTYPE html>
<html>

<head>
    <title>遊戲數據監控中心</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📊 遊戲數據監控中心</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-4">日期</th>
                        <th class="p-4">登入次數</th>
                        <th class="p-4">DAU</th>
                        <th class="p-4">今日總營收</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summaries as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">{{ $row->log_date }}</td>
                            <td class="p-4">{{ number_format($row->login_count) }}</td>
                            <td class="p-4">{{ number_format($row->unique_players) }}</td>
                            <td class="p-4 text-green-600 font-bold">${{ number_format($row->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>