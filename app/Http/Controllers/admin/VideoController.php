<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $plan = $request->get('plan');
        
        $videos = Video::when($search, function($query, $search) {
                return $query->where('titulo', 'like', "%{$search}%")
                            ->orWhere('materia', 'like', "%{$search}%")
                            ->orWhere('tema', 'like', "%{$search}%");
            })
            ->when($plan !== null, function($query) use ($plan) {
                return $query->where('plan', $plan);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        // Estadísticas
        $totalVideos = Video::count();
        $conProgresos = Video::has('progresos')->count();
        $planGratuito = Video::where('plan', true)->count();
        
        return view('administrador.videos.index', compact('videos', 'totalVideos', 'conProgresos', 'planGratuito'));
    }
    
    public function create()
    {
        $asignaturas = Asignatura::orderBy('nombre')->get();
        return view('administrador.videos.create', compact('asignaturas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'materia' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'link' => 'required|url|max:500',
            'duracion' => 'nullable|string|max:50',
            'plan' => 'boolean'
        ]);
        
        try {
            $video = Video::create([
                'materia' => $request->materia,
                'tema' => $request->tema,
                'titulo' => $request->titulo,
                'link' => $request->link,
                'duracion' => $request->duracion,
                'plan' => $request->has('plan') ? 1 : 0
            ]);
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el video: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show($id)
    {
        $video = Video::with('progresos')->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'materia' => $video->materia,
                    'tema' => $video->tema,
                    'titulo' => $video->titulo,
                    'link' => $video->link,
                    'duracion' => $video->duracion,
                    'plan' => $video->plan,
                    'progresos_count' => $video->progresos->count()
                ]
            ]);
        }
        
        return view('administrador.videos.show', compact('video'));
    }
    
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $asignaturas = Asignatura::orderBy('nombre')->get();
        return view('administrador.videos.edit', compact('video', 'asignaturas'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'materia' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'link' => 'required|url|max:500',
            'duracion' => 'nullable|string|max:50',
            'plan' => 'boolean'
        ]);
        
        try {
            $video = Video::findOrFail($id);
            $video->update([
                'materia' => $request->materia,
                'tema' => $request->tema,
                'titulo' => $request->titulo,
                'link' => $request->link,
                'duracion' => $request->duracion,
                'plan' => $request->has('plan') ? 1 : 0
            ]);
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el video: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            
            if ($video->progresos()->count() > 0) {
                return redirect()->route('admin.videos.index')
                    ->with('error', 'No se puede eliminar el video porque tiene progresos asociados.');
            }
            
            $video->delete();
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.videos.index')
                ->with('error', 'Error al eliminar el video: ' . $e->getMessage());
        }
    }
}