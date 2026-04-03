<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-[28px] border border-[#E5ECFF] bg-white/80 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-[#EEF3FF] text-[#2E3F7A] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                            Employee Workspace
                        </div>
                        <h2 class="text-3xl font-extrabold text-[#0B1533] mt-4">Employee Dashboard</h2>
                        <p class="text-sm text-slate-500 mt-2">
                            Welcome back, {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-gradient-to-r from-[#0B1533] to-[#2E3F7A] text-white px-6 py-5 shadow-[0_18px_40px_rgba(11,21,51,.18)]">
                        <p class="text-sm text-blue-100">Quick Summary</p>
                        <p class="text-xl font-bold mt-1">Your workday in one view</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">Monthly Attendance</p>
                    <h4 class="text-3xl font-extrabold text-[#2E3F7A] mt-3">{{ $monthlyAttendanceCount ?? 0 }}</h4>
                </div>

                <div class="rounded-3xl border border-[#FBE8B8] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">Monthly Late</p>
                    <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $monthlyLateCount ?? 0 }}</h4>
                </div>

                <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">My Leads</p>
                    <h4 class="text-3xl font-extrabold text-[#0B1533] mt-3">{{ $myLeadsCount ?? 0 }}</h4>
                </div>

                <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">Open Leads</p>
                    <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $myOpenLeadsCount ?? 0 }}</h4>
                </div>

                <div class="rounded-3xl border border-[#E7DDF9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">My Leave Requests</p>
                    <h4 class="text-3xl font-extrabold text-violet-600 mt-3">
                        {{ auth()->user()->employee ? auth()->user()->employee->leaveRequests()->count() : 0 }}
                    </h4>
                </div>

                <div class="rounded-3xl border border-[#E7DDF9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                    <p class="text-sm text-slate-500">Approved Leaves This Year</p>
                    <h4 class="text-3xl font-extrabold text-violet-600 mt-3">
                        {{ auth()->user()->employee ? auth()->user()->employee->leaveRequests()->where('status', 'approved')->whereYear('start_date', now()->year)->sum('total_days') : 0 }}
                    </h4>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">My Profile</h3>
                    @if($employee)
                        <div class="space-y-3 text-sm text-slate-700">
                            <p><span class="font-semibold text-[#0B1533]">Code:</span> {{ $employee->employee_code }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Department:</span> {{ $employee->department ?? '-' }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Designation:</span> {{ $employee->designation ?? '-' }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Status:</span> {{ ucfirst($employee->status) }}</p>
                        </div>

                        <a href="{{ route('profile.employee') }}"
                           class="inline-flex mt-5 px-5 py-3 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#0B1533] to-[#2E3F7A] shadow-lg shadow-blue-900/20 hover:opacity-95 transition">
                            View Profile
                        </a>
                    @else
                        <p class="text-sm text-rose-600">Employee profile not linked yet.</p>
                    @endif
                </div>

                <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">Today's Attendance</h3>

                    @if($todayAttendance)
                        <div class="space-y-3 text-sm text-slate-700">
                            <p><span class="font-semibold text-[#0B1533]">Date:</span>
                                {{ $todayAttendance->attendance_date->format('Y-m-d') }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Check In:</span> {{ $todayAttendance->check_in ?? '-' }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Check Out:</span> {{ $todayAttendance->check_out ?? '-' }}</p>
                            <p><span class="font-semibold text-[#0B1533]">Status:</span> {{ ucfirst($todayAttendance->status) }}</p>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3">
                            @if(!$todayAttendance->check_out)
                                <a href="{{ route('profile.checkout.form') }}"
                                   class="inline-flex px-5 py-3 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#0B1533] to-[#2E3F7A] shadow-lg shadow-blue-900/20 hover:opacity-95 transition">
                                    Submit Check-Out
                                </a>
                            @endif

                            <a href="{{ route('profile.attendance') }}"
                               class="inline-flex px-5 py-3 rounded-2xl bg-[#EEF3FF] text-[#2E3F7A] font-semibold hover:bg-[#E1E9FF] transition">
                                View My Attendance
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-amber-600">No attendance marked for today.</p>

                        <a href="{{ route('profile.checkin.form') }}"
                           class="inline-flex mt-5 px-5 py-3 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#0B1533] to-[#2E3F7A] shadow-lg shadow-blue-900/20 hover:opacity-95 transition">
                            Mark My Attendance
                        </a>
                    @endif
                </div>

                <div class="md:col-span-2 rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-[#0B1533]">Recent Notifications</h3>

                        <a href="{{ route('notifications.index') }}"
                           class="text-sm text-[#2E3F7A] hover:text-[#1B2A57] font-semibold">
                            View All
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($globalRecentNotifications as $notification)
                            <a href="{{ route('notifications.show', $notification) }}"
                               class="block rounded-2xl border {{ $notification->read_at ? 'border-[#E5ECFF] bg-white' : 'border-[#D7E3FF] bg-[#F6F9FF]' }} p-4 hover:shadow-sm transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-[#0B1533]">{{ $notification->title }}</p>
                                        <p class="text-sm text-slate-600 mt-1">{{ $notification->message ?? '-' }}</p>
                                        <p class="text-xs text-slate-500 mt-2">
                                            {{ $notification->created_at->format('Y-m-d H:i') }}
                                        </p>
                                    </div>

                                    @if(!$notification->read_at)
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-[#EAF0FF] text-[#2E3F7A]">
                                            New
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No recent notifications.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>