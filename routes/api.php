<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthenticationController::class, "register"]);
Route::post("/login", [AuthenticationController::class, "login"]);
Route::post("/refresh/token",[AuthenticationController::class,"refreshToken"]);
Route::post("/logout",[AuthenticationController::class,"logout"]);
Route::post("/forget/password",[AuthenticationController::class,"forgetPassword"]);
Route::post("/reset/password",[AuthenticationController::class,"resetPassword"]);

Route::middleware("JWTAuthenticationMiddleware")->group(function(){
    Route::post("/create/location", [LocationController::class, "createLocation"]);
    Route::post("/update/location", [LocationController::class, "updateLocation"]);
    Route::post("/delete/location", [LocationController::class, "deleteLocation"]);
    Route::get("/get/locations", [LocationController::class, "getLocations"]);

    Route::post("/create/category", [CategoryController::class, "createCategory"]);
    Route::post("/delete/category", [CategoryController::class, "deleteCategory"]);
    Route::get("/get/categories", [CategoryController::class, "getCategories"]);
    Route::post("/show/category", [CategoryController::class, "showCategory"]);


    Route::post("/create/size", [SizeController::class, "createSize"]);
    Route::post("/delete/size", [SizeController::class, "deleteSize"]);
    Route::get("/get/sizes", [SizeController::class, "getSizes"]);

    Route::post("/create/color", [ColorController::class, "createColor"]);
    Route::post("/delete/color", [ColorController::class, "deleteColor"]);
    Route::get("/get/colors", [ColorController::class, "getColors"]);

    Route::post("/create/product", [ProductController::class, "createProduct"]);
    Route::post("/delete/product", [ProductController::class, "deleteProduct"]);
    Route::post("/update/product", [ProductController::class, "updateProduct"]);

    Route::post("/create/product/variant", [ProductVariantController::class, "createProductVariant"]);
    Route::post("/get/product/variants", [ProductVariantController::class, "getProudctVariants"]);
    Route::post("/update/product/variants", [ProductVariantController::class, "updateProductVariant"]);
    Route::post("/delete/product/variant", [ProductVariantController::class, "deleteProductVariant"]);

    Route::post("/add/product/to/cart", [CartController::class, "addProductToCart"]);
    Route::post("/update/product/from/cart", [CartController::class, "updateProductFromCart"]);
    Route::post("/delete/product/from/cart", [CartController::class, "deleteProductFromCart"]);

    Route::get("/get/my/cart/items", [CartController::class, "getMyCartItems"]);

    Route::post("/check/out", [OrderController::class, "checkOut"]);
    Route::get("/get/my/orders", [OrderController::class, "getMyOrders"]);
    Route::post("/cancel/order", [OrderController::class, "cancelOrder"]);
    Route::get("/get/orders",[OrderController::class,"getOrders"]);
    Route::post("/update/order",[OrderController::class,"updateOrder"]);

    Route::prefix("/get")->group(function () {
        Route::get("/users/purchase/statistics", [DashboardController::class, "getUsersPurchaseStatistics"]);
        Route::post("/category/product/statistics", [DashboardController::class, "getCategoryProductStatistics"]);
        Route::get("/orders/statistics", [DashboardController::class, "getOrdersStatistics"]);
        Route::get("/total/users", [DashboardController::class, "getTotalUsers"]);
        Route::get("/one/time/customers", [DashboardController::class, "getOneTimeCustomers"]);
        Route::get("/repeat/customers", [DashboardController::class, "getRepeatCustomers"]);
        Route::get("/size/sales/overview", [DashboardController::class, "getSizeSalesOverview"]);
        Route::post("/average/rate/between/orders", [DashboardController::class, "getAverageRateBetweenOrders"]);
        Route::get("/inactive/users", [DashboardController::class, "getInactiveUsers"]);
        Route::post("/top/purchased/products", [DashboardController::class, "getTopPurchasedProducts"]);
        Route::get("/most/added/but/least/ordered", [DashboardController::class, "getMostAddedButLeastOrdered"]);
    });
});