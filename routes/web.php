<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AreaPageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sewa-perlengkapan-event-{slug}', [AreaPageController::class, 'show'])
    ->where('slug', 'jakarta|bekasi|depok|bogor|tangerang|indonesia')
    ->name('areas.show');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/jual', [ProductController::class, 'sales'])->name('products.sales');
Route::get('/sewa-{slug}-jabodetabek', [ProductController::class, 'categoryBySeoSlug'])
    ->where('slug', '[a-z0-9-]+')
    ->name('products.category');
Route::get('/produk/kategori/{legacySlug}', [ProductController::class, 'legacyCategory'])->name('products.category.legacy');
Route::redirect('/produk/genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt', '/produk/genset-berbagai-kapasitas', 301);
Route::redirect('/produk/genset-5000-watt-sampai-150-kva', '/produk/genset-berbagai-kapasitas', 301);
Route::redirect('/produk/smart-tv-75-inch-65-inch-dan-55-inch', '/produk/smart-tv-berbagai-ukuran', 301);
Route::redirect('/produk/processor-novastar-vx16s-vx600-vx400', '/produk/processor-novastar', 301);
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/portofolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portofolio/{portfolio}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::redirect('/blog/10-tips-memilih-dekorasi-pernikahan-yang-berkesan', '/blog/perlengkapan-event-pernikahan-checklist-tenda-peralatan', 301);
Route::redirect('/blog/berapa-biaya-sewa-tenda-pernikahan-ini-panduannya', '/blog/faktor-biaya-sewa-tenda-pernikahan', 301);
Route::redirect('/blog/ide-dekorasi-ulang-tahun-anak-yang-kreatif-dan-murah', '/blog/perlengkapan-event-ulang-tahun', 301);
Route::get('/blog/{blogPost:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::get('/kebijakan-privasi', [StaticPageController::class, 'privacy'])->name('privacy');
Route::get('/syarat-layanan', [StaticPageController::class, 'terms'])->name('terms');
Route::get('/robots.txt', [StaticPageController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [StaticPageController::class, 'sitemap'])->name('sitemap');
