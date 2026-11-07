<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เกิดข้อผิดพลาดในระบบ (Error 500)</title>
    <!-- Fonts (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            font-family: 'Kanit', sans-serif !important;
            background: #f8fafc;
            color: #1e293b;
        }
        .err-container {
            max-width: 900px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #fee2e2;
            overflow: hidden;
        }
        .err-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: #ffffff;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .err-header h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
        }
        .err-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .err-body {
            padding: 24px 28px;
        }
        .err-message-box {
            background: #fef2f2;
            border-left: 5px solid #dc2626;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .err-message-title {
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 6px;
            font-size: 1rem;
        }
        .err-message-text {
            font-family: Consolas, Monaco, monospace;
            font-size: 0.95rem;
            color: #7f1d1d;
            word-break: break-word;
            line-height: 1.5;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        .info-card {
            background: #f1f5f9;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        .info-label {
            color: #64748b;
            font-size: 0.8rem;
            margin-bottom: 4px;
        }
        .info-value {
            font-weight: 600;
            color: #0f172a;
            word-break: break-all;
            font-family: Consolas, Monaco, monospace;
        }
        details {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 16px;
        }
        summary {
            cursor: pointer;
            font-weight: 600;
            color: #38bdf8;
            outline: none;
        }
        pre {
            margin: 12px 0 0;
            font-size: 0.8rem;
            line-height: 1.45;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
        }
        .btn-back {
            background: #e2e8f0;
            color: #1e293b;
        }
        .btn-home {
            background: #2563eb;
            color: #ffffff;
        }
    </style>
</head>
<body>
@php
    $realException = (isset($exception) && $exception instanceof \Throwable)
        ? ($exception->getPrevious() ?: $exception)
        : null;
    $defaultConn = config('database.default');
    $currentDbName = config("database.connections.{$defaultConn}.database");
    $activeOrgId = session('active_org_id') ?? (auth()->check() ? auth()->user()->org_id_fk : 'N/A');
    $activeOrgDb = session('active_org_database') ?? 'NULL (ใช้ DB กลาง)';
@endphp
<div class="err-container">
    <div class="err-header">
        <h1>⚠️ ระบบพบข้อผิดพลาด (500 Internal Server Error)</h1>
        <span class="err-badge">{{ $realException ? class_basename($realException) : 'ServerError' }}</span>
    </div>
    <div class="err-body">
        <div class="err-message-box">
            <div class="err-message-title">สาเหตุของปัญหา (Error Message):</div>
            <div class="err-message-text">
                {{ $realException && $realException->getMessage() ? $realException->getMessage() : 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ กรุณาตรวจสอบไฟล์ storage/logs/laravel.log' }}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">ไฟล์ที่เกิดปัญหา (File & Line)</div>
                <div class="info-value">
                    @if($realException)
                        {{ str_replace(base_path() . DIRECTORY_SEPARATOR, '', $realException->getFile()) }} : บรรทัดที่ {{ $realException->getLine() }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">URL / Method ที่เรียกใช้งาน</div>
                <div class="info-value">{{ request()->method() }} {{ request()->fullUrl() }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Database Connection ปัจจุบัน</div>
                <div class="info-value">Connection: {{ $defaultConn }} | DB: {{ $currentDbName }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">ข้อมูลองค์กร (Tenant Context)</div>
                <div class="info-value">org_id: {{ $activeOrgId }} | org_database: {{ $activeOrgDb ?: 'NULL' }}</div>
            </div>
        </div>

        @if($realException)
            <details>
                <summary>🔍 คลิกเพื่อดูรายละเอียด Stack Trace ทั้งหมด</summary>
                <pre>{{ $realException->getTraceAsString() }}</pre>
            </details>
        @endif

        <div class="btn-row">
            <a href="javascript:history.back()" class="btn btn-back">⬅️ ย้อนกลับหน้าเดิม</a>
            <a href="{{ url('/accessmenu') }}" class="btn btn-home">🏠 กลับหน้าเมนูหลัก (/accessmenu)</a>
        </div>
    </div>
</div>
</body>
</html>
