<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\AuditLogRepository;

class NotificationController
{
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->auditRepo = new AuditLogRepository();
    }

    public function getNotifications(?Request $request = null): Response
    {
        $tenant = current_tenant();
        if (!$tenant) {
            return Response::json(['success' => false, 'data' => []]);
        }

        $logs = $this->auditRepo->listRecent($tenant['id'], 8);

        $formatted = array_map(function ($log) {
            $timeDiff = time() - strtotime($log['created_at']);
            if ($timeDiff < 60) {
                $timeAgo = 'Just now';
            } elseif ($timeDiff < 3600) {
                $timeAgo = floor($timeDiff / 60) . 'm ago';
            } elseif ($timeDiff < 86400) {
                $timeAgo = floor($timeDiff / 3600) . 'h ago';
            } else {
                $timeAgo = date('M j', strtotime($log['created_at']));
            }

            return [
                'id' => $log['id'],
                'action' => $log['action'],
                'user' => $log['user_name'] ?? 'System',
                'description' => $log['description'],
                'time_ago' => $timeAgo,
                'created_at' => $log['created_at'],
            ];
        }, $logs);

        return Response::json([
            'success' => true,
            'count' => count($formatted),
            'data' => $formatted,
        ]);
    }
}
