<?php /*todo archvivo php debe comenzar con esta linea*/

namespace App\Http\Controllers;//se define la ruta para este archivo

use Illuminate\Http\Request;//importa el modelo de Request, util para realizar solicitudes HTTP en Laravel
use App\Models\Product;//importa el modelo producto, para modificarlo a traves de las operaciones

class ProductController extends Controller//se creae la clase ProductController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos= Product::all();
        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)//la operacion Create, con una variable $request (asi se define en PHP), una 
    //instancia de la clase Request
    {
        $request->validate([//la sintaxis ->, hace referencia al acceso de un metodo de un objeto, en este caso
            //al metodo "validate", del objeto $request, que es de tipo Request
            //"validate" se usa para validar los datos dentro de un request. Si no se cumplen las reglas,
            //no se continua con la operacion
            'codigo' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',//required: el campo es obligatorio
            //string: debe ser texto, max: debe tener un maximo de 255 caracteres
            'descripcion' => 'required|string|max:255',//lo mismo de arriba
            'proveedor' => 'nullable|string|max:255',//nullable: puede ser vacio
            'precio' => 'required|numeric',//obligaotrio, de tipo numerico
            'stock' => 'required|integer',//obligaotrio, de tipo entero
        ]);

        $product = Product::create([
            'codigo' => $request->codigo,
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'proveedor' => $request->proveedor,
            'precio' => $request->precio,
            'stock' => $request->stock
        ]);

        return response()->json([
            'message' => 'Producto registrado correctamente',
            'product' => $product
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*return response()->json([
            'message' => 'Producto creado exitosamente',
            'data' => $validated
        ], 201);*/
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Validación
        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
            'categoria'   => 'nullable|string|max:255',
            'proveedor'   => 'nullable|string|max:255',
            'precio'      => 'required|numeric',
            'stock'       => 'required|integer',
        ]);

        // Actualizar
        $product->update($validated);

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $producto = Product::where('id', $id)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }

    public function buscarProducto(Request $request)
    {
        $q = $request->query('q', '');

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $productos = Product::where('descripcion', 'LIKE', "%$q%")
            ->orderBy('descripcion')
            ->take(5) // máximo 5 sugerencias
            ->get(['id', 'descripcion', 'precio', 'stock']);

        return response()->json($productos);
    }
}
