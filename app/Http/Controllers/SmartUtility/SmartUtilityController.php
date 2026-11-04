<?php

namespace App\Http\Controllers\SmartUtility;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use App\Models\SmartUtility\SmartUtilityConfig;
use App\Models\SmartUtility\SmartUtilityItem;
use App\Models\SmartUtility\SmartUtilityShape;
use App\Models\SmartUtility\SmartUtilityTicket;
use App\Models\Tabwater\TabwaterPipeline;
use App\Models\Tabwater\TabwaterPipeNode;
use App\Models\Tabwater\TabwaterStation;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwMeterInfos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SmartUtilityController extends Controller
{
    /**
     * Get the active organization ID
     */
    protected function getOrgId()
    {
        return session('active_org_id') ?? (Auth::check() ? Auth::user()->org_id_fk : null) ?? 1;
    }

    /**
     * Show the main Smart Utility view - default redirect to elec
     */
    public function index(Request $request)
    {
        if ($request->query('mode') === 'citizen') {
            return $this->citizen($request);
        }
        return redirect()->route('smart_utility.elec');
    }

    /**
     * Electricity module view
     */
    public function elecView(Request $request)
    {
        return $this->renderModuleMap('elec', $request);
    }

    /**
     * Water module view
     */
    public function waterView(Request $request)
    {
        return $this->renderModuleMap('water', $request);
    }

    /**
     * Roads & Drainage module view
     */
    public function roadView(Request $request)
    {
        return $this->renderModuleMap('road', $request);
    }

    /**
     * Buildings & Facilities module view
     */
    public function bldgView(Request $request)
    {
        return $this->renderModuleMap('bldg', $request);
    }

    /**
     * Render the modern map view for a specific module
     */
    protected function renderModuleMap($module, Request $request)
    {
        $orgId = $this->getOrgId();
        $orgInfos = null;
        try {
            $orgInfos = Organization::getOrgInfos($orgId);
        } catch (\Throwable $e) {}

        $orgName = $orgInfos['org_name'] ?? 'อบต.เสม็ดใต้';
        $areaName = $orgInfos['org_address'] ?? 'ต.เสม็ดใต้ อ.บางคล้า จ.ฉะเชิงเทรา';
        $centerLat = !empty($orgInfos['lat']) ? (float)$orgInfos['lat'] : 13.690000;
        $centerLng = !empty($orgInfos['lng'] ?? $orgInfos['long']) ? (float)($orgInfos['lng'] ?? $orgInfos['long']) : 101.200000;

        $config = SmartUtilityConfig::firstOrCreate(
            ['org_id_fk' => $orgId],
            [
                'org_name' => $orgName,
                'area_name' => $areaName,
                'elec_prefix' => 'SMT',
                'water_prefix' => 'WTR',
                'road_prefix' => 'RD',
                'bldg_prefix' => 'BD',
                'center_lat' => $centerLat,
                'center_lng' => $centerLng,
                'staff_pass' => '3350553',
            ]
        );

        // Stats for quick metrics cards
        $stats = [
            'total_items' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', $module)->count(),
            'good_items' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', $module)->where('status', 'good')->count(),
            'bad_items' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', $module)->where('status', 'bad')->count(),
            'pending_tickets' => SmartUtilityTicket::where('org_id_fk', $orgId)->where('module', $module)->whereIn('status', ['open', 'in_progress'])->count(),
            'total_lines' => SmartUtilityShape::where('org_id_fk', $orgId)->where('module', $module)->where('shape_type', 'line')->count(),
        ];

        $stations = [];
        if ($module === 'water') {
            try {
                $stats['system_meters'] = TwMeterInfos::count();
                $stats['system_stations'] = TabwaterStation::where('deleted', false)->count();
                $stations = TabwaterStation::where('deleted', false)->get(['id', 'code', 'name', 'lat', 'lng']);
            } catch (\Throwable $e) {
                $stats['system_meters'] = 0;
                $stats['system_stations'] = 0;
            }
        }

        $moduleTitles = [
            'elec' => [
                'title' => 'ระบบไฟฟ้าสาธารณะอัจฉริยะ',
                'subtitle' => 'ผังตำแหน่งเสาไฟฟ้า โคมส่องสว่าง หม้อแปลงไฟฟ้า และแนวสายส่ง',
                'icon' => '💡',
                'badge' => 'ระบบไฟฟ้า',
                'color' => '#f59e0b',
                'theme' => 'warning',
            ],
            'water' => [
                'title' => 'ระบบประปาและแหล่งน้ำอัจฉริยะ',
                'subtitle' => 'ผังมิเตอร์ผู้ใช้น้ำ แนวท่อเมน ประตูน้ำ และสถานีสูบผลิตน้ำประปา',
                'icon' => '💧',
                'badge' => 'ระบบประปา',
                'color' => '#0284c7',
                'theme' => 'info',
            ],
            'road' => [
                'title' => 'ถนน / ทางเท้า / ท่อระบายน้ำ',
                'subtitle' => 'ผังเส้นทางคมนาคม ผิวจราจร จุดกลับรถ ท่อระบายน้ำ และสะพาน',
                'icon' => '🛣️',
                'badge' => 'ระบบคมนาคม & ระบายน้ำ',
                'color' => '#64748b',
                'theme' => 'secondary',
            ],
            'bldg' => [
                'title' => 'อาคารและสิ่งปลูกสร้างสาธารณะ',
                'subtitle' => 'ผังที่ตั้งอาคารราชการ ศูนย์เด็กเล็ก รพ.สต. และสวนสาธารณะ',
                'icon' => '🏢',
                'badge' => 'สิ่งปลูกสร้าง',
                'color' => '#8b5cf6',
                'theme' => 'primary',
            ],
        ];

        $moduleInfo = $moduleTitles[$module] ?? $moduleTitles['elec'];

        return view('smart_utility.map', compact('config', 'orgInfos', 'module', 'moduleInfo', 'stats', 'stations'));
    }

    /**
     * GIS Analytics & Reports View
     */
    public function reportsView(Request $request)
    {
        $orgId = $this->getOrgId();
        $orgInfos = null;
        try {
            $orgInfos = Organization::getOrgInfos($orgId);
        } catch (\Throwable $e) {}

        $config = SmartUtilityConfig::where('org_id_fk', $orgId)->first();

        // Aggregation by module
        $countsByModule = [
            'elec' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', 'elec')->count(),
            'water' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', 'water')->count(),
            'road' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', 'road')->count(),
            'bldg' => SmartUtilityItem::where('org_id_fk', $orgId)->where('module', 'bldg')->count(),
        ];

        // Status breakdown
        $countsByStatus = [
            'good' => SmartUtilityItem::where('org_id_fk', $orgId)->where('status', 'good')->count(),
            'bad' => SmartUtilityItem::where('org_id_fk', $orgId)->where('status', 'bad')->count(),
            'wait' => SmartUtilityItem::where('org_id_fk', $orgId)->where('status', 'wait')->count(),
        ];

        // Tickets summary
        $ticketsSummary = [
            'total' => SmartUtilityTicket::where('org_id_fk', $orgId)->count(),
            'open' => SmartUtilityTicket::where('org_id_fk', $orgId)->where('status', 'open')->count(),
            'in_progress' => SmartUtilityTicket::where('org_id_fk', $orgId)->where('status', 'in_progress')->count(),
            'resolved' => SmartUtilityTicket::where('org_id_fk', $orgId)->where('status', 'resolved')->count(),
        ];

        $items = SmartUtilityItem::where('org_id_fk', $orgId)->orderBy('id', 'desc')->paginate(30);
        $recentTickets = SmartUtilityTicket::where('org_id_fk', $orgId)->orderBy('id', 'desc')->limit(15)->get();

        return view('smart_utility.reports', compact('config', 'orgInfos', 'countsByModule', 'countsByStatus', 'ticketsSummary', 'items', 'recentTickets'));
    }

    /**
     * Public citizen entrypoint (Mobile-first standalone portal)
     */
    public function citizen(Request $request)
    {
        $orgId = $this->getOrgId();
        $orgInfos = null;
        try {
            $orgInfos = Organization::getOrgInfos($orgId);
        } catch (\Throwable $e) {}

        $config = SmartUtilityConfig::firstOrCreate(
            ['org_id_fk' => $orgId],
            [
                'org_name' => $orgInfos['org_name'] ?? 'อบต.เสม็ดใต้',
                'area_name' => $orgInfos['org_address'] ?? 'ต.เสม็ดใต้ อ.บางคล้า จ.ฉะเชิงเทรา',
                'elec_prefix' => 'SMT',
                'water_prefix' => 'WTR',
                'road_prefix' => 'RD',
                'bldg_prefix' => 'BD',
                'center_lat' => !empty($orgInfos['lat']) ? (float)$orgInfos['lat'] : 13.690000,
                'center_lng' => !empty($orgInfos['lng'] ?? $orgInfos['long']) ? (float)($orgInfos['lng'] ?? $orgInfos['long']) : 101.200000,
                'staff_pass' => '3350553',
            ]
        );

        return view('smart_utility.citizen', compact('config', 'orgInfos'));
    }

    /**
     * Initial data bundle for the app (replaces localStorage on load)
     */
    public function initData(Request $request)
    {
        $orgId = $this->getOrgId();
        $config = SmartUtilityConfig::where('org_id_fk', $orgId)->first();
        if (!$config) {
            $org = null;
            try { $org = Organization::find($orgId); } catch (\Throwable $e) {}
            $orgName = $org ? ($org->org_name ?? $org->name ?? 'อบต.เสม็ดใต้') : 'อบต.เสม็ดใต้';
            $config = SmartUtilityConfig::create([
                'org_id_fk' => $orgId,
                'org_name' => $orgName,
                'area_name' => $org ? ($org->org_address ?? '') : '',
                'elec_prefix' => 'SMT',
                'water_prefix' => 'WTR',
                'road_prefix' => 'RD',
                'bldg_prefix' => 'BD',
                'center_lat' => 13.690000,
                'center_lng' => 101.200000,
                'staff_pass' => '3350553',
            ]);
        }

        // Fetch Items
        $items = SmartUtilityItem::where('org_id_fk', $orgId)->get();

        // Fetch Shapes
        $shapes = SmartUtilityShape::where('org_id_fk', $orgId)->get();

        // Fetch Tickets
        $tickets = SmartUtilityTicket::where('org_id_fk', $orgId)->orderBy('id', 'desc')->get();

        // Tabwater live integration
        $systemWaterMeters = [];
        try {
            $dbMeters = TwMeterInfos::with('user')->get();

            // Check unpaid invoices
            $meterIds = $dbMeters->pluck('meter_id')->toArray();
            $unpaidMap = TwInvoice::whereIn('meter_id_fk', $meterIds)
                ->where('status', '!=', 'paid')
                ->select('meter_id_fk', DB::raw('count(*) as unpaid_count'), DB::raw('sum(total_amount) as total_debt'))
                ->groupBy('meter_id_fk')
                ->get()
                ->keyBy('meter_id_fk');

            foreach ($dbMeters as $m) {
                $unpaid = $unpaidMap[$m->meter_id] ?? null;
                $systemWaterMeters[] = [
                    'meter_id' => $m->meter_id,
                    'meternumber' => $m->meternumber,
                    'factory_no' => $m->factory_no,
                    'submeter_name' => $m->submeter_name,
                    'meter_address' => $m->meter_address,
                    'user_name' => $m->user ? $m->user->name : 'ไม่ระบุชื่อ',
                    'telephone' => $m->user ? ($m->user->telephone ?? '') : '',
                    'status' => $m->status,
                    'cutmeter' => (int)$m->cutmeter === 1,
                    'unpaid_count' => $unpaid ? (int)$unpaid->unpaid_count : 0,
                    'total_debt' => $unpaid ? (float)$unpaid->total_debt : 0,
                ];
            }
        } catch (\Throwable $e) {}

        // Tabwater Stations & Devices
        $systemStations = [];
        try {
            $stations = TabwaterStation::where('deleted', false)
                ->withCount('devices')
                ->get(['id', 'code', 'name', 'lat', 'lng']);
            foreach ($stations as $st) {
                if ($st->lat && $st->lng) {
                    $systemStations[] = [
                        'id' => $st->id,
                        'code' => $st->code,
                        'name' => $st->name,
                        'lat' => (float)$st->lat,
                        'lng' => (float)$st->lng,
                        'devices_count' => $st->devices_count,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // Tabwater Pipe Nodes
        $systemPipeNodes = [];
        try {
            $nodes = TabwaterPipeNode::where('deleted', false)->get(['id', 'station_id', 'node_code', 'name', 'type', 'lat', 'lng', 'remark']);
            foreach ($nodes as $nd) {
                if ($nd->lat && $nd->lng) {
                    $systemPipeNodes[] = [
                        'id' => $nd->id,
                        'station_id' => $nd->station_id,
                        'node_code' => $nd->node_code,
                        'name' => $nd->name,
                        'type' => $nd->type,
                        'lat' => (float)$nd->lat,
                        'lng' => (float)$nd->lng,
                        'remark' => $nd->remark,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // Tabwater Pipelines
        $systemPipelines = [];
        try {
            $pipes = TabwaterPipeline::where('deleted', false)->get(['id', 'station_id', 'pipe_code', 'name', 'level', 'size_mm', 'material', 'path_coordinates']);
            foreach ($pipes as $p) {
                $coords = is_string($p->path_coordinates) ? json_decode($p->path_coordinates, true) : $p->path_coordinates;
                if (!empty($coords) && is_array($coords)) {
                    $systemPipelines[] = [
                        'id' => $p->id,
                        'station_id' => $p->station_id,
                        'pipe_code' => $p->pipe_code,
                        'name' => $p->name,
                        'level' => $p->level ?? 'main',
                        'size_mm' => $p->size_mm ?? 100,
                        'material' => $p->material ?? 'HDPE',
                        'coordinates' => $coords,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        return response()->json([
            'ok' => true,
            'config' => $config,
            'items' => [
                'elec' => $items->where('module', 'elec')->values(),
                'water' => $items->where('module', 'water')->values(),
                'road' => $items->where('module', 'road')->values(),
                'bldg' => $items->where('module', 'bldg')->values(),
            ],
            'shapes' => [
                'elec' => [
                    'lines' => $shapes->where('module', 'elec')->where('shape_type', 'line')->values(),
                    'zones' => $shapes->where('module', 'elec')->where('shape_type', 'zone')->values(),
                    'ann' => $shapes->where('module', 'elec')->whereNotIn('shape_type', ['line', 'zone'])->values(),
                ],
                'water' => [
                    'lines' => $shapes->where('module', 'water')->where('shape_type', 'line')->values(),
                    'zones' => $shapes->where('module', 'water')->where('shape_type', 'zone')->values(),
                    'ann' => $shapes->where('module', 'water')->whereNotIn('shape_type', ['line', 'zone'])->values(),
                ],
                'road' => [
                    'lines' => $shapes->where('module', 'road')->where('shape_type', 'line')->values(),
                    'zones' => $shapes->where('module', 'road')->where('shape_type', 'zone')->values(),
                    'ann' => $shapes->where('module', 'road')->whereNotIn('shape_type', ['line', 'zone'])->values(),
                ],
                'bldg' => [
                    'lines' => $shapes->where('module', 'bldg')->where('shape_type', 'line')->values(),
                    'zones' => $shapes->where('module', 'bldg')->where('shape_type', 'zone')->values(),
                    'ann' => $shapes->where('module', 'bldg')->whereNotIn('shape_type', ['line', 'zone'])->values(),
                ],
            ],
            'tickets' => $tickets,
            'system_water' => [
                'meters' => $systemWaterMeters,
                'stations' => $systemStations,
                'pipe_nodes' => $systemPipeNodes,
                'pipelines' => $systemPipelines,
            ],
        ]);
    }

    /**
     * Save configuration
     */
    public function saveConfig(Request $request)
    {
        $orgId = $this->getOrgId();
        $d = $request->validate([
            'org_name' => 'required|string|max:100',
            'area_name' => 'nullable|string|max:100',
            'elec_prefix' => 'nullable|string|max:10',
            'water_prefix' => 'nullable|string|max:10',
            'road_prefix' => 'nullable|string|max:10',
            'bldg_prefix' => 'nullable|string|max:10',
            'center_lat' => 'nullable|numeric|between:-90,90',
            'center_lng' => 'nullable|numeric|between:-180,180',
            'staff_pass' => 'nullable|string|max:50',
        ]);

        $config = SmartUtilityConfig::updateOrCreate(
            ['org_id_fk' => $orgId],
            $d
        );

        return response()->json(['ok' => true, 'config' => $config]);
    }

    /**
     * Save or update an item (pole, meter, valve, etc.)
     */
    public function saveItem(Request $request)
    {
        $orgId = $this->getOrgId();
        $d = $request->validate([
            'id' => 'nullable|integer',
            'module' => 'required|string|in:elec,water,road,bldg',
            'item_code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'item_type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:50',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'status' => 'nullable|string|max:30',
            'condition' => 'nullable|string|max:30',
            'year_be' => 'nullable|string|max:10',
            'area_sqm' => 'nullable|numeric|min:0',
            'value_baht' => 'nullable|numeric|min:0',
            'owner' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $d['org_id_fk'] = $orgId;

        if (!empty($d['id'])) {
            $item = SmartUtilityItem::findOrFail($d['id']);
            $item->update($d);
        } else {
            $item = SmartUtilityItem::where('org_id_fk', $orgId)
                ->where('module', $d['module'])
                ->where('item_code', $d['item_code'])
                ->first();

            if ($item) {
                $item->update($d);
            } else {
                $item = SmartUtilityItem::create($d);
            }
        }

        return response()->json(['ok' => true, 'item' => $item]);
    }

    /**
     * Delete an item
     */
    public function deleteItem($id)
    {
        $item = SmartUtilityItem::findOrFail($id);
        $item->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Save shape (polyline or annotation)
     */
    public function saveShape(Request $request)
    {
        $orgId = $this->getOrgId();
        $d = $request->validate([
            'id' => 'nullable|integer',
            'module' => 'required|string|in:elec,water,road,bldg',
            'shape_type' => 'required|string|in:line,ann,zone',
            'shape_code' => 'nullable|string|max:50',
            'label' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:30',
            'weight' => 'nullable|integer|min:1|max:20',
            'coordinates' => 'required|array',
            'meta' => 'nullable|array',
        ]);

        $d['org_id_fk'] = $orgId;

        if (!empty($d['id'])) {
            $shape = SmartUtilityShape::findOrFail($d['id']);
            $shape->update($d);
        } else {
            $shape = SmartUtilityShape::create($d);
        }

        return response()->json(['ok' => true, 'shape' => $shape]);
    }

    /**
     * Delete shape
     */
    public function deleteShape($id)
    {
        $shape = SmartUtilityShape::findOrFail($id);
        $shape->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Clear all shapes of a module
     */
    public function clearShapes(Request $request)
    {
        $orgId = $this->getOrgId();
        $module = $request->input('module');
        $type = $request->input('shape_type');

        $q = SmartUtilityShape::where('org_id_fk', $orgId);
        if ($module) {
            $q->where('module', $module);
        }
        if ($type === 'line') {
            $q->where('shape_type', 'line');
        } elseif ($type === 'ann') {
            $q->where('shape_type', '!=', 'line');
        }

        $q->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Create a new fault / repair ticket
     */
    public function createTicket(Request $request)
    {
        $orgId = $this->getOrgId();

        $d = $request->validate([
            'module' => 'required|string|in:elec,water,road,bldg',
            'ref_code' => 'nullable|string|max:50',
            'village' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'detail' => 'required|string',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'cost' => 'nullable|numeric|min:0',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|string',
        ]);

        $module = $d['module'];
        $prefix = match ($module) {
            'elec' => 'E-',
            'water' => 'W-',
            'road' => 'R-',
            'bldg' => 'B-',
            default => 'X-',
        };

        $maxTicket = SmartUtilityTicket::where('module', $module)->orderBy('id', 'desc')->first();
        $nextNum = 1;
        if ($maxTicket && preg_match('/\d+$/', $maxTicket->ticket_code, $matches)) {
            $nextNum = (int)$matches[0] + 1;
        }
        $ticketCode = $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $photoList = [];
        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $file) {
                $path = $file->store('smart_utility_photos', 'public');
                $photoList[] = [
                    'url' => asset('storage/' . $path),
                    'name' => $file->getClientOriginalName(),
                ];
            }
        } elseif (!empty($d['photos'])) {
            $photoList = $d['photos'];
        }

        $history = [
            [
                'time' => date('d/m/Y H:i'),
                'by' => 'citizen',
                'action' => 'แจ้งเรื่องเข้าระบบ',
                'text' => $d['detail'],
            ]
        ];

        $ticket = SmartUtilityTicket::create([
            'org_id_fk' => $orgId,
            'ticket_code' => $ticketCode,
            'module' => $module,
            'ref_code' => $d['ref_code'] ?? null,
            'village' => $d['village'] ?? null,
            'location' => $d['location'] ?? null,
            'detail' => $d['detail'],
            'lat' => $d['lat'] ?? null,
            'lng' => $d['lng'] ?? null,
            'status' => 'open',
            'cost' => $d['cost'] ?? 0,
            'photos' => $photoList,
            'history' => $history,
        ]);

        return response()->json(['ok' => true, 'ticket' => $ticket]);
    }

    /**
     * Staff update ticket status, note, cost
     */
    public function staffUpdateTicket($code, Request $request)
    {
        $orgId = $this->getOrgId();
        $ticket = SmartUtilityTicket::where('org_id_fk', $orgId)->where('ticket_code', $code)->firstOrFail();

        $d = $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,cancelled',
            'staff_note' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $history = $ticket->history ?? [];
        $statusLabels = [
            'open' => 'รอรับเรื่อง',
            'in_progress' => 'กำลังดำเนินการ',
            'resolved' => 'แก้ไขเรียบร้อยแล้ว',
            'cancelled' => 'ยกเลิก',
        ];

        $history[] = [
            'time' => date('d/m/Y H:i'),
            'by' => 'staff',
            'action' => 'เปลี่ยนสถานะเป็น ' . ($statusLabels[$d['status']] ?? $d['status']),
            'text' => $d['staff_note'] ?? '',
        ];

        $ticket->status = $d['status'];
        if (isset($d['staff_note'])) {
            $ticket->staff_note = $d['staff_note'];
        }
        if (isset($d['cost'])) {
            $ticket->cost = $d['cost'];
        }
        if ($d['status'] === 'resolved') {
            $ticket->resolved_at = now();
        }
        $ticket->history = $history;
        $ticket->save();

        return response()->json(['ok' => true, 'ticket' => $ticket]);
    }

    /**
     * Citizen reply on ticket
     */
    public function citizenReplyTicket($code, Request $request)
    {
        $orgId = $this->getOrgId();
        $ticket = SmartUtilityTicket::where('org_id_fk', $orgId)->where('ticket_code', $code)->firstOrFail();

        $d = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $history = $ticket->history ?? [];
        $history[] = [
            'time' => date('d/m/Y H:i'),
            'by' => 'citizen',
            'action' => 'ประชาชนส่งข้อความสอบถาม',
            'text' => $d['message'],
        ];

        $ticket->history = $history;
        $ticket->save();

        return response()->json(['ok' => true, 'ticket' => $ticket]);
    }

    /**
     * Bulk sync all datasets
     */
    public function syncAll(Request $request)
    {
        $orgId = $this->getOrgId();
        $payload = $request->input('bundle');
        if (!is_array($payload)) {
            return response()->json(['ok' => false, 'error' => 'Invalid bundle'], 422);
        }

        DB::beginTransaction();
        try {
            if (!empty($payload['config'])) {
                SmartUtilityConfig::updateOrCreate(['org_id_fk' => $orgId], $payload['config']);
            }

            foreach (['elec', 'water', 'road', 'bldg'] as $mod) {
                if (!empty($payload['items'][$mod]) && is_array($payload['items'][$mod])) {
                    foreach ($payload['items'][$mod] as $it) {
                        if (!empty($it['item_code']) && !empty($it['lat']) && !empty($it['lng'])) {
                            SmartUtilityItem::updateOrCreate(
                                ['org_id_fk' => $orgId, 'module' => $mod, 'item_code' => $it['item_code']],
                                [
                                    'name' => $it['name'] ?? null,
                                    'item_type' => $it['item_type'] ?? null,
                                    'category' => $it['category'] ?? null,
                                    'village' => $it['village'] ?? null,
                                    'lat' => $it['lat'],
                                    'lng' => $it['lng'],
                                    'status' => $it['status'] ?? 'good',
                                    'condition' => $it['condition'] ?? 'good',
                                    'year_be' => $it['year_be'] ?? null,
                                    'area_sqm' => $it['area_sqm'] ?? null,
                                    'value_baht' => $it['value_baht'] ?? null,
                                    'owner' => $it['owner'] ?? null,
                                    'note' => $it['note'] ?? null,
                                    'meta' => $it['meta'] ?? null,
                                ]
                            );
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Import JSON full backup
     */
    public function importFullJSON(Request $request)
    {
        $orgId = $this->getOrgId();
        $data = $request->input('json_data');
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
            return response()->json(['ok' => false, 'error' => 'Invalid JSON structure'], 422);
        }

        DB::beginTransaction();
        try {
            SmartUtilityItem::where('org_id_fk', $orgId)->delete();
            SmartUtilityShape::where('org_id_fk', $orgId)->delete();
            SmartUtilityTicket::where('org_id_fk', $orgId)->delete();

            if (!empty($data['config'])) {
                $c = $data['config'];
                SmartUtilityConfig::updateOrCreate(['org_id_fk' => $orgId], [
                    'org_name' => $c['org_name'] ?? 'อบต.เสม็ดใต้',
                    'area_name' => $c['area_name'] ?? '',
                    'elec_prefix' => $c['elec_prefix'] ?? 'SMT',
                    'water_prefix' => $c['water_prefix'] ?? 'WTR',
                    'road_prefix' => $c['road_prefix'] ?? 'RD',
                    'bldg_prefix' => $c['bldg_prefix'] ?? 'BD',
                    'center_lat' => $c['center_lat'] ?? 13.690000,
                    'center_lng' => $c['center_lng'] ?? 101.200000,
                    'staff_pass' => $c['staff_pass'] ?? '3350553',
                ]);
            }

            foreach (['elec', 'water', 'road', 'bldg'] as $mod) {
                if (!empty($data['items'][$mod]) && is_array($data['items'][$mod])) {
                    foreach ($data['items'][$mod] as $it) {
                        SmartUtilityItem::create([
                            'org_id_fk' => $orgId,
                            'module' => $mod,
                            'item_code' => $it['item_code'] ?? ($mod . '-' . uniqid()),
                            'name' => $it['name'] ?? null,
                            'item_type' => $it['item_type'] ?? null,
                            'category' => $it['category'] ?? null,
                            'village' => $it['village'] ?? null,
                            'lat' => $it['lat'],
                            'lng' => $it['lng'],
                            'status' => $it['status'] ?? 'good',
                            'condition' => $it['condition'] ?? 'good',
                            'year_be' => $it['year_be'] ?? null,
                            'area_sqm' => $it['area_sqm'] ?? null,
                            'value_baht' => $it['value_baht'] ?? null,
                            'owner' => $it['owner'] ?? null,
                            'note' => $it['note'] ?? null,
                            'meta' => $it['meta'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Clear all records
     */
    public function clearAll()
    {
        $orgId = $this->getOrgId();
        SmartUtilityItem::where('org_id_fk', $orgId)->delete();
        SmartUtilityShape::where('org_id_fk', $orgId)->delete();
        SmartUtilityTicket::where('org_id_fk', $orgId)->delete();
        return response()->json(['ok' => true]);
    }
}
