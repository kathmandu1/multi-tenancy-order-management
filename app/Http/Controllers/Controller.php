<?php

namespace App\Http\Controllers;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Integration Swagger in Laravel with Tenant Api Documentation For Multitenancy Laravel app",
 *      description="Implementation of Swagger with in Multi tenancy App",
 *      @OA\Contact(
 *          email="santoshghimire1122233@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Tenancy 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description=" API Server"
 * )
 *   @OAS\SecurityScheme(
 *      securityScheme="bearer",
 *      type="http",
 *      scheme="bearer"
 * )
 * @OA\Tag(
 * name="Multitenancy  Api End Points"
 * )
 * * @OA\Tag(
 *     name="Main Application",
 *     description="Endpoints for the main application for tenant creatation and domain"
 * )
 *
 * @OA\Tag(
 *     name="Tenants",
 *     description="Endpoints related to tenants inside the main application regarding Order,Product and shipping"
 * )
 */
abstract class Controller
{
    //
}
