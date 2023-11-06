<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LinkExternalController;
use App\Http\Controllers\AccountInvoiceController;
use App\Http\Controllers\Member\PublicController as MemberPublic;
use App\Http\Controllers\Member\AccountController as MemberAccount;
use App\Http\Controllers\Member\ProfileController as MemberProfile;
use App\Http\Controllers\Member\InvitationController as MemberInvite;
use App\Http\Controllers\Guest\GuestController as Guestbook;
use App\Http\Controllers\Guest\GuestSouvenirController as GuestbookSouvenir;
use App\Http\Controllers\HomeController as HomePublic;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//** Landing */

Route::get('/', [HomePublic::class, 'index'])->name('landing.home');
Route::get('info/{slug}', [HomePublic::class, 'info'])->name('info');

//** End of Landing */
// ===============================================================================================
//** Dashboard */

Route::get('signin', [MemberAccount::class, 'signin'])->name('signin');
Route::post('signingin', [MemberAccount::class, 'signin_store'])->name('signin-store');
Route::get('signup', [MemberAccount::class, 'signup'])->name('signup');
Route::post('signingup/{step}', [MemberAccount::class, 'signup_store'])->name('signup-store');
// socialite
Route::get('/auth/redirect', [MemberAccount::class, 'redirectToProvider']);
Route::get('/auth/callback', [MemberAccount::class, 'handleProviderCallback']);

Route::prefix('template')->group(function () {
	Route::get('preview/{slug}', [MemberPublic::class, 'template'])->name('preview-template.index');
});

Auth::routes();
Route::prefix('dashboard')->middleware('is_member')->group(function () {
	Route::get('/', [MemberInvite::class, 'main'])->name('member.main');
	Route::get('design', [MemberInvite::class, 'm_design'])->name('menu.design');
	Route::get('cover', [MemberInvite::class, 'm_cover'])->name('menu.cover');
	Route::get('profile', [MemberInvite::class, 'm_profile'])->name('menu.profile');
	Route::get('detail', [MemberInvite::class, 'm_detail'])->name('menu.detail');
	Route::get('quote', [MemberInvite::class, 'm_quote'])->name('menu.quote');
	Route::get('event', [MemberInvite::class, 'm_event'])->name('menu.event');
	Route::get('story', [MemberInvite::class, 'm_story'])->name('menu.story');
	Route::get('gallery', [MemberInvite::class, 'm_gallery'])->name('menu.gallery');
	Route::get('music', [MemberInvite::class, 'm_music'])->name('menu.music');
	Route::get('rsvp', [MemberInvite::class, 'm_rsvp'])->name('menu.rsvp');
	Route::get('additional-info', [MemberInvite::class, 'm_additional'])->name('menu.additional');
	Route::get('e-invitation', [MemberInvite::class, 'm_einvitation'])->name('menu.einvitation');
	Route::get('guest/gift', [MemberInvite::class, 'm_gift'])->name('menu.gift');
	Route::get('guest/wishes', [MemberInvite::class, 'm_wishes'])->name('menu.wishes');
	Route::get('guest/present', [MemberInvite::class, 'm_presenting'])->name('menu.presenting');
	Route::get('share', [MemberInvite::class, 'm_share'])->name('menu.share');
	// execute
	Route::put('saving/{menu}', [MemberInvite::class, 'save_setting'])->name('save.setting');
	Route::get('show/event/{id}', [MemberInvite::class, 'm_event_show'])->name('menu.event-show');
	Route::post('create/event', [MemberInvite::class, 'm_event_add'])->name('menu.event-add');
	Route::put('edit/event/{id}', [MemberInvite::class, 'm_event_edit'])->name('menu.event-edit');
	Route::delete('delete/event/{id}', [MemberInvite::class, 'm_event_delete'])->name('menu.event-delete');
	Route::get('show/story/{id}', [MemberInvite::class, 'm_story_show'])->name('menu.story-show');
	Route::post('create/story', [MemberInvite::class, 'm_story_add'])->name('menu.story-add');
	Route::put('edit/story/{id}', [MemberInvite::class, 'm_story_edit'])->name('menu.story-edit');
	Route::delete('delete/story/{id}', [MemberInvite::class, 'm_story_delete'])->name('menu.story-delete');
	Route::put('edit/e-invitation', [MemberInvite::class, 'm_einvitation_edit'])->name('menu.einvitation-edit');
	Route::post('create/guest', [MemberInvite::class, 'm_share_add'])->name('menu.share-add');
	Route::put('create/gallery', [MemberInvite::class, 'm_gallery_add'])->name('menu.gallery-add');
	Route::post('create/music', [MemberInvite::class, 'm_music_add'])->name('menu.music-add');
	// assets
	Route::post('storage/list/{mode}', [MemberProfile::class, 'strbox_list'])->name('strbox.list');
    Route::post('storage/upload', [MemberProfile::class, 'strbox_store'])->name('strbox.store');
	// account
	Route::get('account-profile', [MemberProfile::class, 'profile'])->name('profile');
	Route::put('account-profile/save', [MemberProfile::class, 'profile_update'])->name('profile.update');
	Route::get('account-storage', [MemberProfile::class, 'account_storage'])->name('storage');
	Route::get('account-transaction', [MemberProfile::class, 'account_transaction'])->name('transaction');
	Route::post('account-transaction/list', [MemberProfile::class, 'account_transaction_list'])->name('transaction.list');
	// accounting
	Route::get('upgrade', [MemberProfile::class, 'package_up'])->name('packages');
	Route::get('upgrade/{id}/payment', [MemberProfile::class, 'package_payment'])->name('packages.payment');
	Route::get('invoice/{id}', [MemberProfile::class, 'invoice'])->name('invoice');
	Route::post('create-invoice/{id}', [MemberProfile::class, 'invoice_add'])->name('invoice-add');
	Route::put('provement-invoice/{id}', [MemberProfile::class, 'invoice_provement'])->name('invoice-prove');
	Route::put('payment-invoice/{id}', [MemberProfile::class, 'invoice_pay'])->name('invoice-pay');
});

//** End of Dashboard */
// ===============================================================================================
//** Guestbook */

Route::prefix('guestbook')->middleware('is_member')->group(function () {
	Route::get('/', [Guestbook::class, 'guestbook'])->name('guestbook');
	Route::get('souvenir', [Guestbook::class, 'm_souvenir'])->name('menu.souvenir');
	Route::post('souvenir/list', [GuestbookSouvenir::class, 'list'])->name('menu.souvenir.list');
	Route::post('create/souvenir', [Guestbook::class, 'm_souvenir_add'])->name('menu.souvenir-add');
	Route::get('show/souvenir/{id}', [Guestbook::class, 'm_souvenir_show'])->name('menu.souvenir-show');
	Route::put('edit/souvenir/{id}', [Guestbook::class, 'm_souvenir_edit'])->name('menu.souvenir-edit');
	Route::delete('delete/souvenir/{id}', [Guestbook::class, 'm_souvenir_delete'])->name('menu.souvenir-delete');
	Route::get('reservation', [Guestbook::class, 'm_reservation'])->name('menu.reservation');
	Route::get('table-management', [Guestbook::class, 'm_management'])->name('menu.management');
});

//** End of Guestbook */
// ===============================================================================================
//** Panel */

Route::prefix('control-panel')->middleware('is_owner')->group(function () {
	Route::get('/', [SettingController::class, 'dashboard'])->name('home.dashboard');
	Route::get('storage/{type?}', [SettingController::class, 'storage'])->name('home.storage');
	Route::get('storage/{id}/edit', [SettingController::class, 'storage_edit'])->name('home.storage-edit');
	Route::patch('storage/{id}/update', [SettingController::class, 'storage_update'])->name('home.storage-update');
	Route::delete('storage/{id}/delete/', [SettingController::class, 'storage_delete'])->name('home.storage-delete');
	Route::post('storage/list/{type}', [SettingController::class, 'storage_list'])->name('home.storage-list');
	Route::post('storage/upload/{type}', [SettingController::class, 'storage_store'])->name('home.storage-store');
	Route::post('storage/modal/{mode}', [SettingController::class, 'storage_modal'])->name('home.storage-modal');
	Route::post('storage/modal/select/{mode}', [SettingController::class, 'put_storage_modal'])->name('home.put-storage-modal');
	Route::post('storage/youtube/select', [SettingController::class, 'from_youtube'])->name('home.from-youtube');
	// Setting
	Route::get('account/{tab}', [SettingController::class, 'account'])->name('setting.account');
	Route::patch('account/profile/update', [SettingController::class, 'profile_update'])->name('setting.profile.update');
	Route::patch('account/password/update', [SettingController::class, 'password_update'])->name('setting.password.update');
	Route::get('setting/{tab}', [SettingController::class, 'site'])->name('setting.site');
	Route::patch('setting/site/update', [SettingController::class, 'site_update'])->name('setting.site.update');
	Route::patch('setting/meta/update', [SettingController::class, 'meta_update'])->name('setting.meta.update');
	Route::patch('setting/maintenance/update', [SettingController::class, 'maintenance_update'])->name('setting.maintenance.update');
	Route::patch('setting/{id}/change', [SettingController::class, 'icolo_update'])->name('setting.icolo.update');
	Route::get('activity-log', [SettingController::class, 'log_activity'])->name('setting.log_activity');
	Route::post('activity-log/list', [SettingController::class, 'log_list'])->name('setting.log_activity.list');
	Route::get('activity-log/{id}/show', [SettingController::class, 'log_detail'])->name('setting.log_activity.show');
	Route::delete('activity-log/clear', [SettingController::class, 'log_clear'])->name('setting.log_activity.clear');
	// AccountInvoice
	Route::resource('invoice-transaction', AccountInvoiceController::class);
	Route::post('invoice-transaction/list', [AccountInvoiceController::class, 'list'])->name('invoice-transaction.list');
	Route::get('invoice-transaction/{id}/{status}', [AccountInvoiceController::class, 'confirm'])->name('invoice-transaction.confirm');
	// Bank
	Route::resource('bank', BankController::class);
	Route::post('bank/list', [BankController::class, 'list'])->name('bank.list');
	// Contact
	Route::get('contact/{tab}', [ContactController::class, 'index'])->name('contact.index');
	Route::patch('contact/update', [ContactController::class, 'update'])->name('contact.update');
	// Invitation
	Route::resource('member', InvitationController::class);
	Route::post('member/list', [InvitationController::class, 'list'])->name('member.list');
	Route::get('eksekusi/{id}/{type}', [InvitationController::class, 'user_purger'])->name('member.executor');
	// Links
	Route::get('social-media', [LinkExternalController::class, 'social'])->name('social.index');
	Route::patch('social-media/update', [LinkExternalController::class, 'update_social'])->name('social.update');
	Route::get('ecommerce', [LinkExternalController::class, 'ecommerce'])->name('ecommerce.index');
	Route::patch('ecommerce/update', [LinkExternalController::class, 'update_ecommerce'])->name('ecommerce.update');
	// Package
	Route::resource('package', PackageController::class);
	Route::post('package/list', [PackageController::class, 'list'])->name('package.list');
	// Template
	Route::resource('template', TemplateController::class);
	Route::post('template/list', [TemplateController::class, 'list'])->name('template.list');
	Route::get('component/{slug}/collection', [TemplateController::class, 'component'])->name('template.component');
	Route::post('component/{slug}/store', [TemplateController::class, 'component_store'])->name('template.component.store');
	Route::delete('component/{slug}/destroy', [TemplateController::class, 'component_destroy'])->name('template.component.destroy');
	// Artisan
	Route::get('public-link', function (){
		\Illuminate\Support\Facades\Artisan::call('storage:link');
		echo 'linked';
	});
});

//** End of Panel */
// ===============================================================================================
//** Invitaion */

Route::get('{slug?}', [MemberPublic::class, 'invitation'])->name('invitation.index');
Route::post('send-confirmation/{slug}', [MemberPublic::class, 'invitation_present'])->name('invitation.present');
Route::post('send-wishes/{slug}', [MemberPublic::class, 'invitation_wish'])->name('invitation.wish');

//** End of Invitaion */
