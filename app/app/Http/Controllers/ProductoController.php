<?php

namespace App\Http\Controllers;

use App\Models\CatalogoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = CatalogoProducto::orderBy('id','desc')->paginate(15);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:255',
            'nombre_producto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_prov' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'imagen' => 'nullable|image|max:4096',
        ]);

        $data['activo'] = $request->boolean('activo');
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_path'] = 'storage/'.$path;
        }

        CatalogoProducto::create($data);
        return redirect()->route('productos.index')->with('ok','Producto creado');
    }

    public function show(CatalogoProducto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(CatalogoProducto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, CatalogoProducto $producto)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:255',
            'nombre_producto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_prov' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'imagen' => 'nullable|image|max:4096',
        ]);

        $data['activo'] = $request->boolean('activo');
        if ($request->hasFile('imagen')) {
            // borrar imagen anterior si estaba en storage
            if ($producto->imagen_path && str_starts_with($producto->imagen_path, 'storage/')) {
                $old = str_replace('storage/', '', $producto->imagen_path);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_path'] = 'storage/'.$path;
        }

        $producto->update($data);
        return redirect()->route('productos.index')->with('ok','Producto actualizado');
    }

    public function destroy(CatalogoProducto $producto)
    {
        if ($producto->imagen_path && str_starts_with($producto->imagen_path, 'storage/')) {
            $old = str_replace('storage/', '', $producto->imagen_path);
            Storage::disk('public')->delete($old);
        }
        $producto->delete();
        return redirect()->route('productos.index')->with('ok','Producto eliminado');
    }
}
