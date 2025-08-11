<?php

namespace Modules\CentralApplication\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Modules\CentralApplication\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index() {}


    /**
     * @OA\Post(
     *     path="/api/tenants",
     *     tags={"Main Application"},
     *     summary="Create a new tenant",
     *     description="Stores a new user in the database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tenant_name","email","domain"},
     *             @OA\Property(property="tenant_name", type="string", example="alibaba"),
     *             @OA\Property(property="email", type="string", format="email", example="alibaba@gmail.com"),
     *             @OA\Property(property="domain", type="string", example="alibaba.localhost"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tenant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="tenant created successfully")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $tenant =  Tenant::create([
            "tenant_name" => $request->tenant_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        $tenant->domains()->create([
            'domain' => $request->domain,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id) {}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * @OA\Delete(
     *     path="/api/tenants/{id}",
     *     tags={"Main Application"},
     *     summary="Delete an tenant",
     *     description="Deletes an item by ID",
     *     operationId="deleteItem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tenant deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Item not found")
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tenant = Tenant::findOrFail($id);

            if ($tenant->domains()->get()) {
                $tenant->domains()->each(function ($domain) {
                    $domain->delete();
                });
            }
            $tenant->delete();
            DB::commit();

            return $this->successResponse('Tenant successfully deleted');
        } catch (Exception $exception) {
            dd($exception);
            DB::rollBack();

            return $this->errorResponse($exception);
        }
    }
}
