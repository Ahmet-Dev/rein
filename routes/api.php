<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    RoleController,
    PermissionController,
    FacilityController,
    BuildingController,
    MaintenanceController,
    SecurityController,
    EmployeeController,
    AttendanceController,
    PayrollController,
    JobPositionController,
    ProductController,
    SupplierController,
    SalesOrderController,
    PurchaseOrderController,
    InvoiceController,
    TransactionController,
    PaymentController,
    AccountController,
    BudgetController,
    CustomerController,
    FeedbackController,
    MarketingCampaignController,
    SupportTicketController,
    RiskManagementController,
    QualityControlController,
    AIAnalysisController,
    AIController,
    TaskController,
    TaskLogController,
    TaxController,
    ProjectController,
    ProjectUserController,
    ProjectTaskController,
    ContractController,
    SurveyController,
    SurveyQuestionController,
    SurveyResponseController,
    PostController,
    PostLikeController,
    PostCommentController,
    InventoryController,
    InventoryTransactionController,
};
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

// Rate Limiting Tanımlama
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

// Ana giriş sayfası
Route::get('/', function () {
    return response()->json([
        'message' => 'Giriş yapın',
        'routes' => collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => $route->methods()[0] ?? '',
                'uri' => $route->uri(),
            ];
        })->values(),
    ]);
});

// Yetkilendirme Gerektirmeyen Endpointler
// Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// Admin Kullanıcısına Tam Yetki
Route::middleware(['auth:sanctum', 'admin', 'throttle:api'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('facilities', FacilityController::class);
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('maintenance', MaintenanceController::class);
    Route::apiResource('security', SecurityController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('attendance', AttendanceController::class);
    Route::apiResource('payrolls', PayrollController::class);
    Route::apiResource('job-positions', JobPositionController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('feedbacks', FeedbackController::class);
    Route::apiResource('marketing-campaigns', MarketingCampaignController::class);
    Route::apiResource('support-tickets', SupportTicketController::class);
    Route::apiResource('risk-management', RiskManagementController::class);
    Route::apiResource('quality-controls', QualityControlController::class);
    Route::apiResource('ai-analyses', AIAnalysisController::class);
    // AI Analiz Geçmişini Getirme
    Route::get('ai/analyses', [AIController::class, 'listAnalyses'])->middleware('auth:sanctum');
    Route::get('ai/analysis/{id}', [AIController::class, 'getAnalysis'])->middleware('auth:sanctum');
    // Görev Yönetimi (Tasks)
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('task-logs', TaskLogController::class);
    Route::apiResource('taxes', TaxController::class);

    Route::apiResource('projects', ProjectController::class);
    Route::get('/projects/{projectId}/users', [ProjectUserController::class, 'index']);
    Route::post('/projects/{projectId}/users', [ProjectUserController::class, 'store']);
    Route::delete('/projects/{projectId}/users/{userId}', [ProjectUserController::class, 'destroy']);
    Route::get('/projects/{projectId}/tasks', [ProjectTaskController::class, 'index']);
    Route::post('/projects/{projectId}/tasks', [ProjectTaskController::class, 'store']);
    Route::delete('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'destroy']);
    Route::apiResource('contracts', ContractController::class);

    Route::apiResource('surveys', SurveyController::class);
    Route::get('/surveys/{surveyId}/questions', [SurveyQuestionController::class, 'index']);
    Route::post('/surveys/{surveyId}/questions', [SurveyQuestionController::class, 'store']);
    Route::get('/questions/{id}', [SurveyQuestionController::class, 'show']);
    Route::put('/questions/{id}', [SurveyQuestionController::class, 'update']);
    Route::delete('/questions/{id}', [SurveyQuestionController::class, 'destroy']);
    Route::get('/surveys/{surveyId}/responses', [SurveyResponseController::class, 'index']);
    Route::post('/questions/{questionId}/responses', [SurveyResponseController::class, 'store']);
    Route::get('/responses/{id}', [SurveyResponseController::class, 'show']);
    Route::put('/responses/{id}', [SurveyResponseController::class, 'update']);
    Route::delete('/responses/{id}', [SurveyResponseController::class, 'destroy']);

    Route::apiResource('posts', PostController::class);
    Route::get('/posts/{postId}/comments', [PostCommentController::class, 'index']);
    Route::post('/posts/{postId}/comment', [PostCommentController::class, 'store']);
    Route::put('/comments/{id}', [PostCommentController::class, 'update']);
    Route::delete('/comments/{id}', [PostCommentController::class, 'destroy']);
    Route::post('/posts/{postId}/like', [PostLikeController::class, 'store']);
    Route::get('/posts/{postId}/likes', [PostLikeController::class, 'index']);

    Route::apiResource('inventories', InventoryController::class);
    Route::get('/inventories/{inventoryId}/transactions', [InventoryTransactionController::class, 'index']);
    Route::post('/inventories/{inventoryId}/transactions', [InventoryTransactionController::class, 'store']);
});

// 🚀 Yetkilendirilmiş Kullanıcılar İçin Genel API'ler
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [UserController::class, 'userInfo']);
    Route::post('logout', [UserController::class, 'logout']);
});

