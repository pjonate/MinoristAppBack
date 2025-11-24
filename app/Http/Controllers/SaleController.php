<?php /*todo archvivo php debe comenzar con esta linea*/

namespace App\Http\Controllers;//se define la ruta para este archivo

use Illuminate\Http\Request;//importa el modelo de Request, util para realizar solicitudes HTTP en Laravel
use App\Models\Sale;
use App\Models\DetalleVenta;
use App\Models\Product; 

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales= Sale::all();
        return response()->json($sales);
    }

    /**
     * Show the form for creating a new resource
     * 
     */

    public function venta(Request $request)
    {
        $request->validate([//la sintaxis ->, hace referencia al acceso de un metodo de un objeto, en este caso
            'date' => 'required|date',           // obligatorio y debe ser fecha válida
            'total' => 'required|numeric', // obligatorio, numérico, no negativo
        ]);
        
        $venta = Sale::create([
            'date' => $request->date,
            'total' => $request->total,
        ]);

        return response()->json([
            'message' => 'Venta registrada correctamente',
            'venta' => $venta
        ], 201);
    } 


    public function create(Request $request)//la operacion Create, con una variable $request (asi se define en PHP), una 
    //instancia de la clase Request
    {
        $product = Product::where('codigo', $request->id_producto)->first();
        $productId = $product->id; // ✅ Aquí está el ID del producto
        
        //$product = Product::find($request->id_producto);
        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }



        // Validar stock antes de crear el detalle
        if ($product->stock < $request->cantidad) {
            return response()->json([
                'message' => 'Cantidad solicitada supera el stock disponible',
                'stock_disponible' => $product->stock
            ], 400);
        }

        $request->validate([//la sintaxis ->, hace referencia al acceso de un metodo de un objeto, en este caso
            'id_venta' => 'required|integer',
            //'id_producto' => 'required|string|max:255',//required: el campo es obligatorio
            'cantidad' => 'required|integer',//lo mismo de arriba
            'subtotal' => 'required|numeric',//obligaotrio, de tipo numerico
        ]);

        try
        {
            $venta = DetalleVenta::create([
                'id_venta' => $request->id_venta,
                'id_producto' => $productId,
                'cantidad' => $request->cantidad,
                'subtotal' => $request->subtotal,
            ]);

            // 2) Restar stock y guardar
            $product->stock -= $request->cantidad;
            $product->save();

            return response()->json([
                'message' => 'Venta registrada correctamente',
                'venta' => $venta
            ], 201);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Cantidad solicitada supera el stock disponible',
                'stock_disponible' => $product->stock
            ], 400);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}