<?php

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Models\AnnualTrash\AnnualTrash;
use App\Models\AnnualTrash\AnnualTrashCollectionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnualTrashFieldController extends Controller
{
    public function qr(AnnualTrash $bin)
    {
        $this->authorizeBin($bin);
        $scanUrl = route('keptkayas.annual_trash.collection.show', $bin);

        return view('annual_trash.bins.qr', compact('bin', 'scanUrl'));
    }

    public function show(AnnualTrash $bin)
    {
        $this->authorizeBin($bin);
        $bin->load('user');
        $logs = AnnualTrashCollectionLog::with('staff')
            ->where('org_id_fk', getAuthOrgId())
            ->where('annual_trash_id', $bin->id)
            ->latest('visited_at')
            ->limit(20)
            ->get();

        return view('annual_trash.field.show', compact('bin', 'logs'));
    }

    public function store(Request $request, AnnualTrash $bin)
    {
        $this->authorizeBin($bin);
        $validated = $request->validate([
            'outcome' => 'required|in:collected,missed,issue',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:10240',
        ]);

        $photoPath = $request->file('photo')?->store('annual_trash/issues', 'public');

        AnnualTrashCollectionLog::create([
            'org_id_fk' => getAuthOrgId(),
            'annual_trash_id' => $bin->id,
            'staff_id' => Auth::id(),
            'outcome' => $validated['outcome'],
            'notes' => $validated['notes'] ?? null,
            'photo_path' => $photoPath,
            'visited_at' => now(),
        ]);

        return redirect()->route('keptkayas.annual_trash.collection.show', $bin)
            ->with('success', 'บันทึกผลการปฏิบัติงานแล้ว');
    }

    private function authorizeBin(AnnualTrash $bin): void
    {
        abort_unless((int) $bin->org_id_fk === (int) getAuthOrgId(), 404);
    }
}