<?php

namespace Modules\CentralApplication\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Modules\CentralApplication\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CentralApplication\Transformers\TenantResource;

class TenantController extends Controller
{
    use ApiResponse;


    /**
     * @OA\Get(
     *     path="/api/tenants",
     *     tags={"Main Application"},
     *     summary="Get a list of tenants",
     *     description="Retrieves a list of all tenants from the database",
     *     @OA\Response(
     *         response=200,
     *         description="A list of tenants",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tenants retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/tenantResourceSchema")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->get();
        if ($tenants->isEmpty()) {
            return $this->errorResponse('No tenants found', 404);
        }
        return $this->successResponse(TenantResource::collection($tenants), 'Tenants retrieved successfully');
    }

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
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="tenant created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/tenantResourceSchema")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $tenant =  Tenant::create([
                "tenant_name" => $request->tenant_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            $tenant->domains()->create([
                'domain' => $request->domain,
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
        return $this->successResponse(new TenantResource($tenant), 'Tenant created successfully');
    }

    /**
     *  @OA\Get(
     *     path="/api/tenants/{id}",
     *     tags={"Main Application"},
     *     summary="Get a single tenant",
     *     description="Retrieves a single tenant by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tenant retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/tenantResourceSchema")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tenant not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $tenant = Tenant::with('domains')->find($id);
        if (!$tenant) {
            return $this->errorResponse('Tenant not found', 404);
        }
        return $this->successResponse(new TenantResource($tenant), 'Tenant retrieved successfully');
    }


    /**
     * @OA\Patch(
     *     path="/api/tenants/{id}",
     *     tags={"Main Application"},
     *     summary="Update an existing tenant",
     *     description="Updates an existing tenant in the database",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tenant_name","email","domain"},
     *             @OA\Property(property="tenant_name", type="string", example="updated_tenant"),
     *             @OA\Property(property="email", type="string", format="email", example="updated_tenant@gmail.com"),
     *             @OA\Property(property="domain", type="string", example="updated_tenant.localhost"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant updated successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="string", example="success"),
     *            @OA\Property(property="message", type="string", example="tenant updated successfully")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        try {
            $tenant->update([
                "tenant_name" => $request->tenant_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            $tenant->domains()->update([
                'domain' => $request->domain,
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
        return $this->successResponse(null, 'Tenant updated successfully');
    }

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
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
        return $this->successResponse('Tenant successfully deleted');
    }
}
