<?php

use App\Http\Controllers\Api\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CaseStudyController as AdminCaseStudyController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ResearchController as AdminResearchController;
use App\Http\Controllers\Api\Admin\ResearchTopicController as AdminResearchTopicController;
use App\Http\Controllers\Api\Admin\SolutionController as AdminSolutionController;
use App\Http\Controllers\Api\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Api\Admin\TagController as AdminTagController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LocaleController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ResearchController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SolutionController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:leads');
Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->middleware('throttle:newsletter');

Route::middleware('cache.public:300')->group(function () {
    Route::get('/locales', [LocaleController::class, 'index']);
    Route::get('/home', [PageController::class, 'home']);
    Route::get('/pages/{key}', [PageController::class, 'show']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/tags', [TagController::class, 'index']);

    Route::get('/solutions', [SolutionController::class, 'index']);
    Route::get('/solutions/{slug}', [SolutionController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/case-studies', [CaseStudyController::class, 'index']);
    Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

    Route::get('/research', [ResearchController::class, 'index']);
    Route::get('/research/{slug}', [ResearchController::class, 'show']);
});

Route::get('/search', [SearchController::class, 'index'])->middleware('throttle:search');

// Admin auth
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::apiResource('posts', AdminPostController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('categories', AdminCategoryController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('tags', AdminTagController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('solutions', AdminSolutionController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('products', AdminProductController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('case-studies', AdminCaseStudyController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('research', AdminResearchController::class)
            ->middlewareFor('destroy', 'permission:content.delete');
        Route::apiResource('research-topics', AdminResearchTopicController::class)
            ->only(['index', 'store', 'destroy'])
            ->middlewareFor('destroy', 'permission:content.delete');

        Route::get('/leads', [AdminLeadController::class, 'index']);
        Route::middleware('permission:leads.manage')->group(function () {
            Route::patch('/leads/{lead}', [AdminLeadController::class, 'update']);
            Route::post('/leads/{lead}/notes', [AdminLeadController::class, 'addNote']);
        });

        Route::get('/subscribers', [AdminSubscriberController::class, 'index']);

        Route::middleware('role:admin')->group(function () {
            Route::get('/audit-logs', [AdminAuditLogController::class, 'index']);
        });
    });
});
