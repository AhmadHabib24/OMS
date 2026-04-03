<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\AppNotificationService;
use Illuminate\Console\Command;

class SendDailySystemNotifications extends Command
{
    protected $signature = 'notifications:daily';
    protected $description = 'Send daily system notifications for follow-ups and leave reminders';

    public function handle(AppNotificationService $notificationService): int
    {
        $this->sendFollowUpDueNotifications($notificationService);
        $this->sendLeaveStartingTomorrowNotifications($notificationService);
        $this->sendLeaveEndingTodayNotifications($notificationService);
        $this->sendAdminDailySummaryNotifications($notificationService);

        $this->info('Daily notifications processed successfully.');

        return self::SUCCESS;
    }

    protected function sendFollowUpDueNotifications(AppNotificationService $notificationService): void
    {
        $today = today();

        $leads = Lead::with('assignedEmployee.user')
            ->whereDate('next_follow_up_date', '<=', $today)
            ->whereNotIn('status', ['won', 'lost'])
            ->whereNotNull('assigned_to')
            ->get();

        foreach ($leads as $lead) {
            $employeeUserId = optional(optional($lead->assignedEmployee)->user)->id;

            if (!$employeeUserId) {
                continue;
            }

            $alreadySent = \App\Models\AppNotification::where('user_id', $employeeUserId)
                ->where('type', 'follow_up_due')
                ->whereDate('created_at', $today)
                ->where('meta->lead_id', $lead->id)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $notificationService->create(
                $employeeUserId,
                'follow_up_due',
                'Lead Follow-up Due',
                "Follow-up is due for lead {$lead->name}" . ($lead->company ? " ({$lead->company})" : '') . ".",
                route('leads.my'),
                [
                    'lead_id' => $lead->id,
                    'assigned_to' => $lead->assigned_to,
                    'next_follow_up_date' => optional($lead->next_follow_up_date)->format('Y-m-d'),
                ]
            );
        }
    }
    protected function sendAdminDailySummaryNotifications(AppNotificationService $notificationService): void
    {
        $today = today();

        $adminIds = User::role('admin')->pluck('id')->toArray();

        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();
        $dueLeads = Lead::whereDate('next_follow_up_date', '<=', $today)
            ->whereNotIn('status', ['won', 'lost'])
            ->count();
        $suspiciousAttendance = \App\Models\Attendance::whereDate('attendance_date', $today)
            ->where('is_suspicious', true)
            ->count();

        foreach ($adminIds as $adminId) {
            $alreadySent = \App\Models\AppNotification::where('user_id', $adminId)
                ->where('type', 'admin_daily_summary')
                ->whereDate('created_at', $today)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $notificationService->create(
                $adminId,
                'admin_daily_summary',
                'Daily Admin Summary',
                "Pending leaves: {$pendingLeaves}, Due lead follow-ups: {$dueLeads}, Suspicious attendance today: {$suspiciousAttendance}.",
                route('dashboard'),
                [
                    'pending_leaves' => $pendingLeaves,
                    'due_leads' => $dueLeads,
                    'suspicious_attendance' => $suspiciousAttendance,
                ]
            );
        }
    }

    protected function sendLeaveStartingTomorrowNotifications(AppNotificationService $notificationService): void
    {
        $tomorrow = today()->addDay();

        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->where('status', 'approved')
            ->whereDate('start_date', $tomorrow)
            ->get();

        foreach ($leaveRequests as $leaveRequest) {
            $userId = optional($leaveRequest->employee)->user_id;

            if (!$userId) {
                continue;
            }

            $alreadySent = \App\Models\AppNotification::where('user_id', $userId)
                ->where('type', 'leave_starting_tomorrow')
                ->whereDate('created_at', today())
                ->where('meta->leave_request_id', $leaveRequest->id)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $notificationService->create(
                $userId,
                'leave_starting_tomorrow',
                'Approved Leave Starts Tomorrow',
                "Your approved {$leaveRequest->leaveType->name} starts tomorrow ({$leaveRequest->start_date->format('Y-m-d')}).",
                route('leave-requests.my'),
                [
                    'leave_request_id' => $leaveRequest->id,
                    'start_date' => $leaveRequest->start_date->format('Y-m-d'),
                    'end_date' => $leaveRequest->end_date->format('Y-m-d'),
                ]
            );
        }
    }

    protected function sendLeaveEndingTodayNotifications(AppNotificationService $notificationService): void
    {
        $today = today();

        $leaveRequests = LeaveRequest::with(['employee.user', 'leaveType'])
            ->where('status', 'approved')
            ->whereDate('end_date', $today)
            ->get();

        foreach ($leaveRequests as $leaveRequest) {
            $userId = optional($leaveRequest->employee)->user_id;

            if (!$userId) {
                continue;
            }

            $alreadySent = \App\Models\AppNotification::where('user_id', $userId)
                ->where('type', 'leave_ending_today')
                ->whereDate('created_at', $today)
                ->where('meta->leave_request_id', $leaveRequest->id)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $notificationService->create(
                $userId,
                'leave_ending_today',
                'Leave Ends Today',
                "Your approved {$leaveRequest->leaveType->name} ends today ({$leaveRequest->end_date->format('Y-m-d')}).",
                route('leave-requests.my'),
                [
                    'leave_request_id' => $leaveRequest->id,
                    'start_date' => $leaveRequest->start_date->format('Y-m-d'),
                    'end_date' => $leaveRequest->end_date->format('Y-m-d'),
                ]
            );
        }
    }
}