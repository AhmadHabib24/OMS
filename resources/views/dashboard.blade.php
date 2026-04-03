<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-[28px] border border-[#E5ECFF] bg-white/80 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6 lg:p-8">
                <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-[#EEF3FF] text-[#2E3F7A] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                            Premium Overview
                        </div>
                        <h2 class="text-3xl font-extrabold text-[#0B1533] mt-4">Admin Dashboard</h2>
                        <p class="text-sm text-slate-500 mt-2">
                            KPI overview from {{ $from->format('Y-m-d') }} to {{ $to->format('Y-m-d') }}
                        </p>
                    </div>

                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full xl:w-auto">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-2">From</label>
                            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                                   class="w-full rounded-2xl border border-[#DCE4F5] bg-white px-4 py-3 text-sm focus:border-[#2E3F7A] focus:ring-[#2E3F7A]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-2">To</label>
                            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                                   class="w-full rounded-2xl border border-[#DCE4F5] bg-white px-4 py-3 text-sm focus:border-[#2E3F7A] focus:ring-[#2E3F7A]">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="w-full md:w-auto px-5 py-3 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#0B1533] to-[#2E3F7A] shadow-lg shadow-blue-900/20 hover:opacity-95 transition">
                                Apply
                            </button>

                            <a href="{{ route('dashboard') }}"
                               class="w-full md:w-auto px-5 py-3 rounded-2xl bg-[#EEF3FF] text-[#2E3F7A] font-semibold hover:bg-[#E1E9FF] transition text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-[#0B1533]">Employees</h3>
                    <span class="text-sm text-slate-500">Team overview</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_35px_rgba(11,21,51,.05)] p-5">
                        <p class="text-sm text-slate-500">Total Employees</p>
                        <h4 class="text-3xl font-extrabold text-[#0B1533] mt-3">{{ $employeeStats['total'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Active</p>
                        <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $employeeStats['active'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Inactive</p>
                        <h4 class="text-3xl font-extrabold text-rose-600 mt-3">{{ $employeeStats['inactive'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#E5ECFF] bg-gradient-to-br from-[#0B1533] to-[#2E3F7A] text-white p-5 shadow-[0_18px_40px_rgba(11,21,51,.18)]">
                        <p class="text-sm text-blue-100">Added In Range</p>
                        <h4 class="text-3xl font-extrabold mt-3">{{ $employeeStats['new_this_month'] }}</h4>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-[#0B1533]">Attendance</h3>
                    <span class="text-sm text-slate-500">Daily activity & alerts</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Total Records</p>
                        <h4 class="text-3xl font-extrabold text-[#0B1533] mt-3">{{ $attendanceStats['total_records'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Present</p>
                        <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $attendanceStats['present'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#FBE8B8] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Late</p>
                        <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $attendanceStats['late'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Suspicious</p>
                        <h4 class="text-3xl font-extrabold text-rose-600 mt-3">{{ $attendanceStats['suspicious'] }}</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-5">
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Absent</p>
                        <h4 class="text-2xl font-extrabold text-rose-500 mt-3">{{ $attendanceStats['absent'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCE4F5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Half Day</p>
                        <h4 class="text-2xl font-extrabold text-sky-600 mt-3">{{ $attendanceStats['half_day'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#E5ECFF] bg-gradient-to-r from-[#F8FAFF] to-[#EEF3FF] p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Today / Today Late</p>
                        <h4 class="text-2xl font-extrabold text-[#0B1533] mt-3">
                            {{ $attendanceStats['today'] }} / <span class="text-amber-600">{{ $attendanceStats['today_late'] }}</span>
                        </h4>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-[#0B1533]">Leads</h3>
                    <span class="text-sm text-slate-500">Pipeline status</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Total Leads</p>
                        <h4 class="text-3xl font-extrabold text-[#0B1533] mt-3">{{ $leadStats['total'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">New</p>
                        <h4 class="text-3xl font-extrabold text-[#2E3F7A] mt-3">{{ $leadStats['new'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Qualified</p>
                        <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $leadStats['qualified'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Won</p>
                        <h4 class="text-3xl font-extrabold text-emerald-700 mt-3">{{ $leadStats['won'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Lost</p>
                        <h4 class="text-3xl font-extrabold text-rose-600 mt-3">{{ $leadStats['lost'] }}</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-5">
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">High Priority</p>
                        <h4 class="text-2xl font-extrabold text-rose-600 mt-3">{{ $leadStats['high_priority'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#FBE8B8] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Follow-up Due</p>
                        <h4 class="text-2xl font-extrabold text-amber-600 mt-3">{{ $leadStats['follow_up_due'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#E5ECFF] bg-gradient-to-r from-[#F8FAFF] to-[#EEF3FF] p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Created In Range</p>
                        <h4 class="text-2xl font-extrabold text-[#2E3F7A] mt-3">{{ $leadStats['created_in_range'] }}</h4>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-[#0B1533]">Email Campaigns</h3>
                    <span class="text-sm text-slate-500">Campaign performance</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="rounded-3xl border border-[#E5ECFF] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Total Campaigns</p>
                        <h4 class="text-3xl font-extrabold text-[#0B1533] mt-3">{{ $campaignStats['total'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#FBE8B8] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Draft</p>
                        <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $campaignStats['draft'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Sent</p>
                        <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $campaignStats['sent'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#F7D9D9] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Failed</p>
                        <h4 class="text-3xl font-extrabold text-rose-600 mt-3">{{ $campaignStats['failed'] }}</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                    <div class="rounded-3xl border border-[#E5ECFF] bg-gradient-to-r from-[#F8FAFF] to-[#EEF3FF] p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Created In Range</p>
                        <h4 class="text-2xl font-extrabold text-[#2E3F7A] mt-3">{{ $campaignStats['created_in_range'] }}</h4>
                    </div>
                    <div class="rounded-3xl border border-[#DCF3E5] bg-white/85 p-5 shadow-[0_16px_35px_rgba(11,21,51,.05)]">
                        <p class="text-sm text-slate-500">Sent In Range</p>
                        <h4 class="text-2xl font-extrabold text-emerald-600 mt-3">{{ $campaignStats['sent_in_range'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">Recent Leads</h3>
                    <div class="space-y-4">
                        @forelse($recentLeads as $lead)
                            <div class="rounded-2xl border border-[#EEF3FF] bg-[#FBFCFF] p-4">
                                <p class="font-semibold text-[#0B1533]">{{ $lead->name }}</p>
                                <p class="text-sm text-slate-500">{{ $lead->company ?? '-' }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ ucwords(str_replace('_', ' ', $lead->status)) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No recent leads found.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[28px] border border-[#F7D9D9] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">Recent Suspicious Attendance</h3>
                    <div class="space-y-4">
                        @forelse($recentSuspiciousAttendance as $item)
                            <div class="rounded-2xl border border-red-100 bg-red-50 p-4">
                                <p class="font-semibold text-[#0B1533]">{{ $item->employee->full_name ?? 'Unknown Employee' }}</p>
                                <p class="text-sm text-slate-500">{{ $item->attendance_date->format('Y-m-d') }}</p>
                                <p class="text-xs text-rose-600 mt-2">{{ $item->suspicious_reason ?? 'No reason available' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No suspicious attendance found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">Recent Employees</h3>
                    <div class="space-y-4">
                        @forelse($recentEmployees as $employee)
                            <div class="rounded-2xl border border-[#EEF3FF] bg-[#FBFCFF] p-4">
                                <p class="font-semibold text-[#0B1533]">{{ $employee->full_name }}</p>
                                <p class="text-sm text-slate-500">{{ $employee->department ?? '-' }} / {{ $employee->designation ?? '-' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No recent employees found.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
                    <h3 class="text-lg font-bold text-[#0B1533] mb-4">Recent Campaigns</h3>
                    <div class="space-y-4">
                        @forelse($recentCampaigns as $campaign)
                            <div class="rounded-2xl border border-[#EEF3FF] bg-[#FBFCFF] p-4">
                                <p class="font-semibold text-[#0B1533]">{{ $campaign->name }}</p>
                                <p class="text-sm text-slate-500">{{ ucfirst($campaign->status) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No recent campaigns found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-[#E5ECFF] bg-white/85 backdrop-blur-xl shadow-[0_16px_40px_rgba(11,21,51,.06)] p-6">
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
                                    <p class="text-xs text-slate-500 mt-2">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
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
</x-app-layout>