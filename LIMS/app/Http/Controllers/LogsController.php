<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Carbon\Carbon;

class LogsController extends Controller
{
    public function index()
    {
        $logs = [];
        $error = null;

        try {
            $response = Http::timeout(15)->get('http://localhost:5198/api/SystemAuditLogs');

            if ($response->successful()) {
                $logs = collect($response->json())
                    ->map(fn ($log) => $this->formatLog($log))
                    ->values()
                    ->all();
            } else {
                $error = 'Failed to retrieve system audit logs.';
            }
        } catch (ConnectionException $e) {
            Log::error('Audit logs API unreachable', ['error' => $e->getMessage()]);
            $error = 'The logs service is not available right now.';
        }

        return view('systempage.Logs.index', compact('logs', 'error'));
    }

    private function formatLog(array $log): array
    {
        $createdAt = Carbon::parse($log['createdAt']);

        $request = !empty($log['requestId'])
            ? 'Ref. #' . str_pad($log['requestId'], 4, '0', STR_PAD_LEFT)
            : null;

        $record = $log['labNumber'] ?? null;

        return [
            'id'     => $log['logId'],
            'type'   => $this->actionType($log['action'] ?? ''),
            'title'  => trim(($log['action'] ?? '') . ' ' . ($record ?? $request ?? '')),
            'actor'  => $log['userName'] ?? 'Unknown user',
            'module' => $log['module'] ?? '',
            'time'   => $createdAt->format('g:i A'),
            'date' => $createdAt->format('M j, Y'),
            'detail' => [
                'user'      => $log['userName'] ?? null,
                'module'    => $log['module'] ?? null,
                'record'    => $record,
                'request'   => $request,
                'timestamp' => $createdAt->format('M j, Y · g:i A'),
                'changes'   => collect($log['changes'] ?? [])->map(fn ($c) => [
                    'field'     => $c['fieldName'] ?? '',
                    'before'    => $c['oldValue'] ?? '—',
                    'after'     => $c['newValue'] ?? '—',
                    'highlight' => true,
                ])->all(),
            ],
        ];
    }

    private function actionType(string $action): string
    {
        return match ($action) {
            'Encoded result', 'Approved request'                   => 'success',
            'Edited result', 'Returned for revision'               => 'edit',
            'Submitted request', 'Received request',
            'Routed sample', 'Started analysis'                    => 'info',
            'Failed sign-in', 'Rejected request'                   => 'warning',
            default                                                => 'neutral',
        };
    }
}