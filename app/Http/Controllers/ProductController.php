<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getProducts()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                $products = $user->products()->get();
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(['data' => $products], Response::HTTP_OK);
    }

    public function newProducts(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'precio' => 'required|min:1|numeric',
                    'cantidad' => 'numeric|integer',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors()->toJson(), 400);
                }
                $id = Str::orderedUuid();
                Product::create(['id' => $id, 'name' => $request->get('name'), 'precio' => $request->get('precio'), 'cantidad' => $request->get('cantidad'), "user_id" => $user->id]);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(['message' => "Producto Creado"], Response::HTTP_OK);
    }

    public function editProduct(Request $request, $id)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'precio' => 'required|min:1|numeric',
                    'cantidad' => 'numeric|integer',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors()->toJson(), 400);
                }
                $product = Product::findOrFail($request->id);

                $product->name = $request->name;
                $product->precio = $request->precio;
                $product->cantidad = $request->cantidad;
                $product->save();
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(['message' => "Producto Actualizado"], Response::HTTP_OK);
    }

    public function deleteProduct(Request $request, $id)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                Product::destroy($request->id);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(['message' => "Producto Borrado"], Response::HTTP_OK);
    }
}