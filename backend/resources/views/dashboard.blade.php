<!DOCTYPE html>
<html>
<head>
    <title>遊戲數據監控中心</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📊 遊戲數據監控中心</h1>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">系統運行中</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h2 class="font-bold text-gray-700">📅 每日營收報表</h2>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-gray-800 text-white text-sm">
                            <tr>
                                <th class="p-4">日期</th>
                                <th class="p-4 text-right">登入次數</th>
                                <th class="p-4 text-right">DAU</th>
                                <th class="p-4 text-right">今日總營收</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summaries as $row)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4 font-medium">{{ $row->log_date }}</td>
                                <td class="p-4 text-right">{{ number_format($row->login_count) }}</td>
                                <td class="p-4 text-right">{{ number_format($row->unique_players) }}</td>
                                <td class="p-4 text-right text-blue-600 font-bold">${{ number_format($row->total_revenue, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
                        <h2 class="font-bold text-red-700">🚨 即時異常告警</h2>
                        <span class="animate-ping h-2 w-2 rounded-full bg-red-400"></span>
                    </div>
                    <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto">
                        @forelse($alerts as $alert)
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded">{{ $alert->type }}</span>
                                <span class="text-[10px] text-gray-400">{{ $alert->created_at }}</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-700 leading-relaxed">{{ $alert->message }}</p>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-400">
                            <p>目前暫無異常數據</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
