<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhoneModelController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TechnicianJobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('track/{uuid}', [InvoiceController::class, 'track'])->name('invoices.track');
Route::get('invoice/download/{invoice}', [InvoiceController::class, 'download'])->name('invoices.download');

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('print')->name('print.')->group(function (): void {
        Route::get('/', [PrintController::class, 'index'])->name('index');
        Route::get('reports/daily', [PrintController::class, 'dailyReport'])->name('reports.daily');
        Route::get('reports/monthly', [PrintController::class, 'monthlyReport'])->name('reports.monthly');
        Route::get('service-orders/{service_order}/receipt', [PrintController::class, 'receipt'])->name('service-orders.receipt');
        Route::get('service-orders/{service_order}/invoice', [PrintController::class, 'invoice'])->name('service-orders.invoice');
        Route::get('service-orders/{service_order}/warranty', [PrintController::class, 'warranty'])->name('service-orders.warranty');
        Route::get('service-orders/{service_order}/label', [PrintController::class, 'label'])->name('service-orders.label');
        Route::get('payments/{payment}/cashier-receipt', [PrintController::class, 'cashierReceipt'])->name('payments.cashier-receipt');
        Route::get('payments/cashier-receipt/form', [PrintController::class, 'cashierReceiptForm'])->name('payments.cashier-receipt.form');
        Route::post('payments/cashier-receipt/preview', [PrintController::class, 'cashierReceiptPreview'])->name('payments.cashier-receipt.preview');
    });

    Route::prefix('customers')->name('customers.')->group(function (): void {
        Route::get('export/excel', [CustomerController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [CustomerController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [CustomerController::class, 'print'])->name('print');
    });
    Route::resource('customers', CustomerController::class);

    Route::prefix('brands')->name('brands.')->group(function (): void {
        Route::get('export/excel', [BrandController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [BrandController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [BrandController::class, 'print'])->name('print');
    });
    Route::resource('brands', BrandController::class);

    Route::prefix('phone-models')->name('phone-models.')->group(function (): void {
        Route::get('export/excel', [PhoneModelController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [PhoneModelController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [PhoneModelController::class, 'print'])->name('print');
    });
    Route::resource('phone-models', PhoneModelController::class);

    Route::prefix('suppliers')->name('suppliers.')->group(function (): void {
        Route::get('export/excel', [SupplierController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [SupplierController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [SupplierController::class, 'print'])->name('print');
    });
    Route::resource('suppliers', SupplierController::class);

    Route::prefix('spare-parts')->name('spare-parts.')->group(function (): void {
        Route::get('export/excel', [SparePartController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [SparePartController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [SparePartController::class, 'print'])->name('print');
    });
    Route::resource('spare-parts', SparePartController::class);

    Route::prefix('service-orders')->name('service-orders.')->group(function (): void {
        Route::get('export/excel', [ServiceOrderController::class, 'exportExcel'])->name('export.excel');
        Route::get('print', [ServiceOrderController::class, 'print'])->name('print');
        Route::patch('{service_order}/status', [ServiceOrderController::class, 'updateStatus'])->name('update-status');
        Route::get('{service_order}/export/pdf', [ServiceOrderController::class, 'exportPdf'])->name('export.pdf');
        Route::get('{service_order}/print-label', [ServiceOrderController::class, 'printLabel'])->name('print-label');
    });
    Route::resource('service-orders', ServiceOrderController::class);

    Route::middleware('role:teknisi,owner,admin')->prefix('teknisi/pekerjaan')->name('technician.jobs.')->group(function (): void {
        Route::get('/', [TechnicianJobController::class, 'index'])->name('index');
        Route::get('{service_order}', [TechnicianJobController::class, 'show'])->name('show');
        Route::post('{service_order}/diagnosis', [TechnicianJobController::class, 'diagnosis'])->name('diagnosis');
        Route::patch('{service_order}/status', [TechnicianJobController::class, 'updateStatus'])->name('status');
        Route::post('{service_order}/spare-parts', [TechnicianJobController::class, 'spareParts'])->name('spare-parts');
        Route::post('{service_order}/service-fee', [TechnicianJobController::class, 'serviceFee'])->name('service-fee');
        Route::post('{service_order}/photo', [TechnicianJobController::class, 'photo'])->name('photo');
        Route::post('{service_order}/video', [TechnicianJobController::class, 'video'])->name('video');
        Route::post('{service_order}/note', [TechnicianJobController::class, 'note'])->name('note');
        Route::post('{service_order}/qc', [TechnicianJobController::class, 'qc'])->name('qc');
    });

    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::prefix('invoices')->name('invoices.')->group(function (): void {
        Route::get('{serviceInvoice}/pdf', [InvoiceController::class, 'downloadAuth'])->name('pdf');
        Route::get('{serviceInvoice}/whatsapp', [InvoiceController::class, 'whatsApp'])->name('whatsapp');
    });

    Route::prefix('purchases')->name('purchases.')->group(function (): void {
        Route::get('export/excel', [PurchaseController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [PurchaseController::class, 'exportPdf'])->name('export.pdf');
        Route::get('print', [PurchaseController::class, 'print'])->name('print');
    });
    Route::resource('purchases', PurchaseController::class);

    Route::prefix('reports')->name('reports.')->group(function (): void {
        Route::get('service', [ReportController::class, 'serviceReport'])->name('service');
        Route::get('service/export/excel', [ReportController::class, 'exportServiceExcel'])->name('service.export.excel');
        Route::get('service/export/pdf', [ReportController::class, 'exportServicePdf'])->name('service.export.pdf');
        Route::get('service/print', [ReportController::class, 'printService'])->name('service.print');

        Route::get('revenue', [ReportController::class, 'revenueReport'])->name('revenue');
        Route::get('revenue/export/excel', [ReportController::class, 'exportRevenueExcel'])->name('revenue.export.excel');
        Route::get('revenue/export/pdf', [ReportController::class, 'exportRevenuePdf'])->name('revenue.export.pdf');
        Route::get('revenue/print', [ReportController::class, 'printRevenue'])->name('revenue.print');

        Route::get('stock', [ReportController::class, 'stockReport'])->name('stock');
        Route::get('stock/export/excel', [ReportController::class, 'exportStockExcel'])->name('stock.export.excel');
        Route::get('stock/export/pdf', [ReportController::class, 'exportStockPdf'])->name('stock.export.pdf');
        Route::get('stock/print', [ReportController::class, 'printStock'])->name('stock.print');
    });

    Route::middleware('role:owner,admin')->group(function (): void {
        Route::resource('users', UserController::class);

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/logo', [SettingController::class, 'uploadLogo'])->name('settings.upload-logo');
    });
});
