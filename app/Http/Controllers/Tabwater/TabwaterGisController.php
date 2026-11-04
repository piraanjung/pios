<?php

namespace App\Http\Controllers\Tabwater;

use App\Http\Controllers\Controller;
use App\Models\Tabwater\TabwaterPipeline;
use App\Models\Tabwater\TabwaterPipeNode;
use App\Models\Tabwater\TabwaterStation;
use Illuminate\Http\Request;

class TabwaterGisController extends Controller
{
    private const NODE_TYPES = 'station,main_junction,sub_junction,valve_point,tank,end_point';

    public function index()
    {
        return view('tabwater.settings.gis.index');
    }

    public function data()
    {
        $stations = TabwaterStation::where('deleted', false)
            ->with('devices:id,station_id,device_code,name,type,status')
            ->get(['id', 'code', 'name', 'lat', 'lng']);
        $ids = $stations->pluck('id');

        $nodes = TabwaterPipeNode::where('deleted', false)->whereIn('station_id', $ids)
            ->get(['id', 'station_id', 'node_code', 'name', 'type', 'lat', 'lng', 'remark']);
        $pipes = TabwaterPipeline::where('deleted', false)->whereIn('station_id', $ids)
            ->get(['id', 'station_id', 'pipe_code', 'name', 'from_node_id', 'to_node_id', 'level', 'size_mm', 'material', 'length_m', 'path_coordinates']);

        return response()->json(compact('stations', 'nodes', 'pipes'));
    }

    // ---------- Station ----------
    public function storeStation(Request $request)
    {
        $d = $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $station = TabwaterStation::create($d + [
            'max_turbidity_ntu' => 5, 'min_ph' => 6.5, 'max_ph' => 8.5, 'max_tds_ppm' => 500,
            'min_water_level_percent' => 20, 'max_water_level_percent' => 90, 'secchi_k_factor' => 1,
        ]);

        return response()->json(['ok' => true, 'id' => $station->id]);
    }

    public function updateStation(Request $request, $id)
    {
        $station = TabwaterStation::findOrFail($id);
        $station->update($request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]));

        return response()->json(['ok' => true]);
    }

    public function destroyStation($id)
    {
        TabwaterStation::findOrFail($id)->update(['deleted' => true]);

        return response()->json(['ok' => true]);
    }

    // ---------- Node ----------
    public function storeNode(Request $request)
    {
        $d = $request->validate([
            'station_id' => 'required|integer',
            'node_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . self::NODE_TYPES,
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'remark' => 'nullable|string|max:255',
        ]);
        TabwaterStation::findOrFail($d['station_id']);

        $node = TabwaterPipeNode::create($d);

        return response()->json(['ok' => true, 'id' => $node->id]);
    }

    public function updateNode(Request $request, $id)
    {
        $node = TabwaterPipeNode::findOrFail($id);
        TabwaterStation::findOrFail($node->station_id);
        $node->update($request->validate([
            'node_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . self::NODE_TYPES,
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'remark' => 'nullable|string|max:255',
        ]));

        return response()->json(['ok' => true]);
    }

    public function destroyNode($id)
    {
        $node = TabwaterPipeNode::findOrFail($id);
        TabwaterStation::findOrFail($node->station_id);

        $used = TabwaterPipeline::where('deleted', false)
            ->where(fn ($q) => $q->where('from_node_id', $id)->orWhere('to_node_id', $id))->exists();
        if ($used) {
            return response()->json(['ok' => false, 'message' => 'จุดนี้ถูกใช้งานโดยแนวท่อ กรุณาลบแนวท่อก่อน'], 422);
        }

        $node->update(['deleted' => true]);

        return response()->json(['ok' => true]);
    }

    // ---------- Pipeline ----------
    private function pipeRules(): array
    {
        return [
            'pipe_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'from_node_id' => 'required|exists:tabwater_pipe_nodes,id',
            'to_node_id' => 'required|exists:tabwater_pipe_nodes,id|different:from_node_id',
            'level' => 'required|in:main,sub_main,distribution',
            'size_mm' => 'required|integer|min:1',
            'material' => 'required|string|max:50',
            'length_m' => 'nullable|numeric|min:0',
            'path_coordinates' => 'required|array|min:2',
            'path_coordinates.*' => 'array|size:2',
        ];
    }

    public function storePipe(Request $request)
    {
        $d = $request->validate($this->pipeRules() + ['station_id' => 'required|integer']);
        TabwaterStation::findOrFail($d['station_id']);

        $pipe = TabwaterPipeline::create($d);

        return response()->json(['ok' => true, 'id' => $pipe->id]);
    }

    public function updatePipe(Request $request, $id)
    {
        $pipe = TabwaterPipeline::findOrFail($id);
        TabwaterStation::findOrFail($pipe->station_id);
        $pipe->update($request->validate($this->pipeRules()));

        return response()->json(['ok' => true]);
    }

    public function destroyPipe($id)
    {
        $pipe = TabwaterPipeline::findOrFail($id);
        TabwaterStation::findOrFail($pipe->station_id);
        $pipe->update(['deleted' => true]);

        return response()->json(['ok' => true]);
    }
}
