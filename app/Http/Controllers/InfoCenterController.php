<?php

namespace App\Http\Controllers;

use App\Models\EskulProfile;
use App\Models\Winner;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoCenterController extends Controller
{
    // ─── ESKUL ───────────────────────────────────────────────────────────────────

    public function eskulIndex()
    {
        $eskuls = cache()->remember('admin_eskul_profiles', 86400, function () {
            return EskulProfile::select(['id', 'name', 'icon', 'description', 'detail', 'schedule', 'contact', 'activities', 'achievements', 'is_active', 'sort_order', 'image_path'])
                ->orderBy('sort_order')
                ->get();
        });
        return view('admin.infocenter.eskul', compact('eskuls'));
    }

    public function eskulStore(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:120',
            'icon'         => 'nullable|string|max:10',
            'description'  => 'required|string',
            'detail'       => 'required|string',
            'schedule'     => 'required|string|max:200',
            'contact'      => 'nullable|string|max:100',
            'activities'   => 'nullable|string',
            'achievements' => 'nullable|string|max:255',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('infocenter/eskul', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);
        unset($data['image']);

        EskulProfile::create($data);
        cache()->forget('admin_eskul_profiles');
        return back()->with('success', 'Eskul berhasil ditambahkan.');
    }

    public function eskulUpdate(Request $request, EskulProfile $eskul)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:120',
            'icon'         => 'nullable|string|max:10',
            'description'  => 'required|string',
            'detail'       => 'required|string',
            'schedule'     => 'required|string|max:200',
            'contact'      => 'nullable|string|max:100',
            'activities'   => 'nullable|string',
            'achievements' => 'nullable|string|max:255',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($eskul->image_path) Storage::disk('public')->delete($eskul->image_path);
            $data['image_path'] = $request->file('image')->store('infocenter/eskul', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);
        unset($data['image']);

        $eskul->update($data);
        cache()->forget('admin_eskul_profiles');
        return back()->with('success', 'Eskul berhasil diperbarui.');
    }

    public function eskulDestroy(EskulProfile $eskul)
    {
        if ($eskul->image_path) Storage::disk('public')->delete($eskul->image_path);
        $eskul->delete();
        cache()->forget('admin_eskul_profiles');
        return back()->with('success', 'Eskul berhasil dihapus.');
    }

    // ─── WINNERS ─────────────────────────────────────────────────────────────────

    public function winnersIndex()
    {
        $winners = cache()->remember('admin_winners_list', 86400, function () {
            return Winner::select(['id', 'rank', 'name', 'school', 'category', 'score', 'is_active', 'sort_order', 'image_path'])
                ->orderBy('sort_order')
                ->get();
        });
        return view('admin.infocenter.winners', compact('winners'));
    }

    public function winnersStore(Request $request)
    {
        $data = $request->validate([
            'rank'       => 'required|string|max:50',
            'name'       => 'required|string|max:150',
            'school'     => 'required|string|max:150',
            'category'   => 'required|string|max:200',
            'score'      => 'nullable|string|max:50',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('infocenter/winners', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);
        unset($data['image']);

        Winner::create($data);
        cache()->forget('admin_winners_list');
        return back()->with('success', 'Pemenang berhasil ditambahkan.');
    }

    public function winnersUpdate(Request $request, Winner $winner)
    {
        $data = $request->validate([
            'rank'       => 'required|string|max:50',
            'name'       => 'required|string|max:150',
            'school'     => 'required|string|max:150',
            'category'   => 'required|string|max:200',
            'score'      => 'nullable|string|max:50',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($winner->image_path) Storage::disk('public')->delete($winner->image_path);
            $data['image_path'] = $request->file('image')->store('infocenter/winners', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);
        unset($data['image']);

        $winner->update($data);
        cache()->forget('admin_winners_list');
        return back()->with('success', 'Pemenang berhasil diperbarui.');
    }

    public function winnersDestroy(Winner $winner)
    {
        if ($winner->image_path) Storage::disk('public')->delete($winner->image_path);
        $winner->delete();
        cache()->forget('admin_winners_list');
        return back()->with('success', 'Pemenang berhasil dihapus.');
    }

    // ─── DOCUMENTATION ──────────────────────────────────────────────────────────

    public function docsIndex()
    {
        $docs = cache()->remember('admin_docs_list', 3600, function () {
            return \App\Models\Documentation::select(['id', 'title', 'description', 'event_date', 'file_path', 'file_type', 'is_active', 'created_at'])
                ->latest()
                ->get();
        });
        return view('admin.infocenter.docs', compact('docs'));
    }

    public function docsStore(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'event_date'  => 'nullable|date|after:1900-01-01|before:2100-01-01',
            'file'        => 'required|file|max:51200', // 50MB max, check MIME manually
        ]);

        $file = $request->file('file');
        $mime = $file->getMimeType();
        $isVideo = str_starts_with($mime, 'video/');
        $isImage = str_starts_with($mime, 'image/');

        if (!$isVideo && !$isImage) {
            return back()->withErrors(['file' => 'File harus berupa gambar atau video.'])->withInput();
        }

        $type     = $isVideo ? 'video' : 'image';
        $fileName = time() . '_' . uniqid();
        $path     = '';

        if ($type === 'image') {
            try {
                $webpData = $this->convertToWebP($file);
                $path = 'infocenter/docs/' . $fileName . '.webp';
                Storage::disk('public')->put($path, $webpData);
            } catch (\Throwable $e) {
                // Fallback: store original file
                $ext  = $file->getClientOriginalExtension() ?: 'jpg';
                $path = $file->storeAs('infocenter/docs', $fileName . '.' . $ext, 'public');
            }
        } else {
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('infocenter/docs', $fileName . '.' . $extension, 'public');
        }

        $eventDate = null;
        if ($request->event_date) {
            try {
                $parsed = \Carbon\Carbon::parse($request->event_date);
                if ($parsed->year > 1900 && $parsed->year <= 9999) {
                    $eventDate = $parsed->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $eventDate = null;
            }
        }

        \App\Models\Documentation::create([
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $eventDate,
            'file_path'   => $path,
            'file_type'   => $type,
            'is_active'   => true,
        ]);
        cache()->forget('admin_docs_list');

        return back()->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function docsDestroy(\App\Models\Documentation $documentation)
    {
        if ($documentation->file_path) {
            Storage::disk('public')->delete($documentation->file_path);
        }
        $documentation->delete();
        cache()->forget('admin_docs_list');
        return back()->with('success', 'Dokumentasi berhasil dihapus.');
    }

    private function convertToWebP($file)
    {
        $image = null;
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($file->getRealPath());
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($file->getRealPath());
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        } elseif ($extension === 'webp') {
            return file_get_contents($file->getRealPath());
        }

        if (!$image) return file_get_contents($file->getRealPath());

        ob_start();
        imagewebp($image, null, 80);
        $data = ob_get_clean();
        imagedestroy($image);

        return $data;
    }
}
