<?php

use App\Http\Controllers\DesignerConsignmentAgreement;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear', function() {
    $run = Artisan::call('config:clear');
    $run = Artisan::call('cache:clear');
    $run = Artisan::call('config:cache');
    return 'FINISHED';
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::get('/runArtisan', function () {
//    return \Artisan::call('doctrine:schema:update');
//});
//Route::get('/runConfigArtisan', function () {
//    return \Artisan::call('config:cache');
//});
//Route::get('/test-category', 'SubCategoryController@test_data');

Route::post('/product-review-report', 'ProductReportExportController@productReviewExport');

// agreements
Route::post('/agreements/filled-agreements', 'AgreementsController@getAgreements');
Route::post('/agreement/save_external_agreement', 'AgreementsController@saveExternalAgreement');

// dashboard
Route::post('/dashboard/count', 'DashboardController@dashboardCount');


// assign agent to seller
Route::post('/seller/assign_agent', 'SellerController@assignAgent');

//search sellers
Route::post('/search-sellers', 'SellerController@searchSellers');


// seller product export for stage
Route::post('/export-sller-products-stage', 'ProductReportExportController@exportSellerStageProducts');

// For Production Product Labels Report

Route::post('/export-seller-products-labels', 'ProductReportExportController@exportsellerproductslabels');

// consignment agreement with storage
Route::post('consignment_agreement_with_storage/save_send', 'ConsignmentAgreementWithStorageController@createAndSendConsignmentAgreementWithStorage');
Route::post('consignment_agreement_with_storage/check_agreement', 'ConsignmentAgreementWithStorageController@checkAgreement');
Route::post('consignment_agreement_with_storage/save_agreement', 'ConsignmentAgreementWithStorageController@saveAgreement');

Route::post('consignment_agreement_with_storage/send_consignment_agreement_with_storage_pro_review', 'ConsignmentAgreementWithStorageController@SendConsignmentAgreementWithStorageProReview');
// Designer Consignment agreement
Route::post('designer_consignment_agreement/send_designer_consignment_agreement', [DesignerConsignmentAgreement::class,'sendDesignerConsignmentAgreement']);
Route::post('designer_consignment_agreement/check_designer_consignment_agreement', [DesignerConsignmentAgreement::class,'checkDesignerConsignmentAgreement']);
Route::post('designer_consignment_agreement/save_designer_consignment_agreement', [DesignerConsignmentAgreement::class,'saveDesignerConsignmentAgreement']);



Route::post('consignment_agreement_with_storage/send_storage_amendment_to_consignment_agreement_mail', 'ConsignmentAgreementWithStorageController@sendStorageAmendmentToConsignmentAgreementMail');
Route::post('consignment_agreement_with_storage/check_agreement_storage_amendment', 'ConsignmentAgreementWithStorageController@checkAgreementStorageAmendment');
Route::post('consignment_agreement_with_storage/save_agreement_storage_amendment', 'ConsignmentAgreementWithStorageController@saveAgreementStorageAmendment');

Route::post('consignment_agreement_with_storage/resend_acknowledgement_email_consignment_agreement_with_storage', 'ConsignmentAgreementWithStorageController@ResendAcknowledgementEmailConsignmentAgreementWithStorage');


// auction agreement
Route::post('auction_agreement/check_auction_agreement', 'AuctionAgreementController@checkAuctionAgreement');
Route::post('auction_agreement/reject_to_auction', 'AuctionAgreementController@sendMailReject');
Route::post('auction_agreement/save_auction_agreement', 'AuctionAgreementController@saveAuctionAgreement');
Route::post('seller/get_reject_to_acution_sellers', 'AuctionAgreementController@getRejectToAuctionSellers');
Route::post('reject_to_auction/get_reject_to_auction_products', 'AuctionAgreementController@getRejectProducts');


//storage agreement
Route::post('storage_agreement/save_storage_agreement', 'SellerController@saveSellerStorageAgreement');
Route::post('storage_agreement/check_storage_agreement', 'SellerController@checkSellerStorageAgreement');
Route::post('storage_agreement/get_storage_agreement_report', 'ProductReportController@getStorageAgreementReport');
Route::post('storage_report/get_storage_products', 'ProductReportController@getStorageProductsReports');

Route::post('storage_agreement/export_storage_report', 'ProductReportController@exportStorageProducts');
// Route::get('storage_agreement/export_storage_report', 'ProductReportController@exportStorageProducts');

Route::post('seller_agreement/save_seller_agreement', 'SellerController@saveSellerAgreement');
//Route::post('seller_agreement/save_new_seller_agreement', 'SellerController@saveNewSellerAgreement');
Route::get('seller_agreement/get_id_seller_agreement', 'SellerController@saveuserSellerAgreement');
Route::post('seller_agreement/check_seller_agreement', 'SellerController@checkSellerAgreement');
Route::post('seller_agreement/getAllMyProductQuoteAgreements', 'SellerController@getAllMyProductQuoteAgreements');

Route::post('seller_agreement/getAllMyProductQuoteRenews', 'SellerController@getAllMyProductQuoteRenews');

Route::get('seller/agree_terms/{seller_id}', 'SellerController@agreeTermsAcknowledgement');

Route::post('setEmails', 'OptionController@setEmails');
Route::post('email_send_record/get_email_send_records', 'EmailSendRecordController@getEmailSendRecords');
Route::post('email_send_record/get_email_send_record', 'EmailSendRecordController@getEmailSendRecordOfId');

Route::get('updateAllSellerRoles', 'SellerController@updateAllSellerRoles');
Route::get('getAllArchivedProductsCount', 'ProductController@getAllArchivedProductsCount');
Route::get('getProductsArchivedCount', 'ProductController@getProductsArchivedCount');
Route::get('getProductsRejectedCount', 'ProductController@getProductsRejectedCount');
Route::get('getProductsForReviewCount', 'ProductController@getProductsForReviewCount');
Route::get('getPickUpLocationsBySelectIdSellerId/{select_id}/{seller_id}', 'OptionController@getPickUpLocationsBySelectIdSellerId');
Route::get('getOptionsBySelectId/{select_id}', 'OptionController@getOptionsBySelectId');
Route::post('option/saveOption', 'OptionController@saveOption');
Route::get('select/get_status', 'UsersController@getAllStatus');
Route::get('get_all_product_status', 'ProductController@get_all_product_status');
Route::get('get_all_product_approved_status', 'ProductApprovedController@get_all_product_status');

Route::post('product/change_product_status', 'ProductController@changeProductStatus');

Route::post('get_email_template', 'ProductController@getEmailTemplate');
Route::post('save/email_template', 'ProductController@saveEmailTemplate');

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('//signup', 'UsersController@registerUser');
Route::post('/editAuthUser', 'UsersController@editAuthUser');
Route::post('forgot_password', 'AuthenticateController@forgot_password');
Route::get('password_admin/reset/{token}', 'AuthenticateController@get_forgotten_user');
Route::get('/get_roles', 'RolesController@getAllRoles');
Route::post('/password_admin/reset', 'AuthenticateController@reset');

Route::post('roles/get_all_roles', 'RolesController@get_all_roles');
Route::post('roles/get_role', 'RolesController@get_role');
Route::post('roles/delete_role', 'RolesController@delete_role');
Route::post('roles/get_roles', 'RolesController@get_roles');
Route::post('roles/save_role', 'RolesController@save_role');
Route::post('permission/get_permissions', 'PermissionController@get_permissions');
Route::post('permission/get_permission_categories', 'PermissionController@get_permission_categories');
Route::post('permission/save_permission', 'PermissionController@save_permission');
Route::post('permission/get_all_permissions', 'PermissionController@get_all_permissions');
Route::post('permission/get_permissions_by_role', 'PermissionController@get_permissions_by_role');
Route::post('permission/set_permissions', 'PermissionController@set_permissions');
Route::post('permission/get_permission', 'PermissionController@get_permission');

Route::get('getAllUsers', 'UsersController@getAllUsers');

Route::get('getAllApprovalProducts', 'ProductsQuotationController@getAllApprovalProducts');
Route::get('getProductProposalsInProgress', 'ProductsQuotationController@getProductProposalsInProgress');
Route::post('product_quotation/get_product_quotations', 'ProductsQuotationController@getProductQuotations');
Route::post('product_quotation/get_product_quotation', 'ProductsQuotationController@getProductQuotation');
Route::post('product_quotation/save_product_quotation', 'ProductsQuotationController@saveProductQuotation');
Route::post('product_quotation/send_mail', 'ProductsQuotationController@sendMail');
Route::post('product_quotation/send_mail_archive', 'ProductsQuotationController@sendMailArchive');
Route::post('product_quotation/delete_product_quotation', 'ProductsQuotationController@DeleteAllProductQuotation');
Route::post('product_quotation/send_mail_reject', 'ProductsQuotationController@sendMailReject');
Route::post('product_quotation/send_mail_approve', 'ProductsQuotationController@sendMailApprove');
Route::post('product_quotation/send_mail_approve_status_change', 'ProductsQuotationController@sendMailApproveStatusChange');
Route::post('product_final/save_product_quotation_final', 'ProductsQuotationController@saveProductQuotationFinal');
Route::post('product_for_production/save_product_for_production', 'ProductsQuotationController@saveProductForProduction');
Route::get('get_all_pending_product_quatation', 'ProductsQuotationController@getAllPendingProductQuotation');
Route::post('get_all_sync_product', 'ProductsQuotationController@getAllSyncProduct');
Route::post('get_sync_product_order', 'ProductsQuotationController@getSyncProductOrder');
Route::post('get_sync_product_order_report', 'ProductsQuotationController@getSyncProductOrderReport');

Route::post('get_all_sync_order', 'ProductsQuotationController@getAllSyncOrder');
Route::post('get_sync_order_detail', 'ProductsQuotationController@getSyncOrderDetail');
Route::post('get_sync_order_report', 'ProductsQuotationController@getSyncOrderReport');

Route::post('import_product', 'ProductsImportController@importProduct');

Route::get('getProposalInProposalProduction', 'ProductsQuotationController@getProposalInProposalProduction');
Route::get('getProductsInProduction', 'ProductsQuotationController@getProductsInProduction');
Route::post('product_final/get_product_finals', 'ProductsQuotationController@getProductFinals');
Route::post('product_final/change_product_final_status', 'ProductsQuotationController@changeProductFinalStatus');
Route::post('product_final/send_mail_product_final_status', 'ProductsQuotationController@sendMailProductFinalStatus');
Route::get('product_final/change_all_product_sync', 'ProductsQuotationController@AllSyncProduct');


Route::post('webhooks_products', 'WebhooksProductsController@WebhooksProducts');
Route::post('webhooks_orders', 'WebhooksOrdersController@WebhooksOrders');
Route::post('webhooks_products_old', 'WebhooksProductsOldController@webhooks_products_old');
Route::get('webhooks_workflow_wpproductid', 'WebhooksProductsOldController@webhooks_workflow_wpproductid');
Route::post('webhooks_products_stock_update', 'WebhookProductStockUpdateController@WebhooksProductStockUpdate');

Route::get('product_stuck_remove/{sku}', 'ProductStuckRemoveController@product_stuck_remove');





Route::get('get_details', 'ProductsQuotationController@getDetails');
Route::get('getProductsInCopyright', 'ProductsQuotationController@getProductsInCopyright');
Route::post('copyright/change_copyright_status', 'ProductsQuotationController@changeCopyrightStatus');
Route::post('copyright/get_copyrights', 'ProductsQuotationController@getCopyrights');


Route::post('product_for_production/change_product_for_production_status', 'ProductsQuotationController@changeProductForProductionStatus');
Route::post('product_for_production/get_product_for_productions', 'ProductsQuotationController@getProductForProductions');


Route::post('product_for_production/get_proposal_for_productions', 'ProductsQuotationController@getProposalForProductions');

//awaiting_contract
Route::post('awaiting_contract/change_product_for_awaiting_contract', 'ProductsQuotationController@changeProductForAwaitingcontractStatus');
Route::post('awaiting_contract/change_acknowledgement_awaiting_contract', 'ProductsQuotationController@saveAcknowledgementAwaitingcontract');
Route::post('awaiting_contract/send_pricing_proposal', 'ProductsQuotationController@savePricingProposalAwaitingcontract');
Route::post('awaiting_contract/send_preliminary_pricing_proposal', 'ProductsQuotationController@savePreliminaryPricingProposalAwaitingcontract');
Route::post('awaiting_contract/get_product_for_awaiting_contract', 'ProductsQuotationController@getProductForAwaiting_contract');
Route::post('awaiting_contract/change_product_for_awaiting_contract_status', 'ProductsQuotationController@savePropasalAcceptAwaitingcontract');
Route::post('awaiting_contract/save_awaiting_contract', 'ProductsQuotationController@saveProductAwaitingContract');


//product_for_pricing
Route::post('product_for_pricing/get_product_for_pricings', 'ProductsQuotationController@getProductForPricings');
Route::post('product_for_pricing/change_product_for_pricing_status', 'ProductsQuotationController@changeProductForPricingStatus');
Route::post('product_for_pricing/send_mail_approve', 'ProductsQuotationController@sendMailApprove');
Route::post('product_for_pricing/save_product_pricing_final', 'ProductsQuotationController@saveProductPricingFinal');
Route::post('product_for_pricing/save_product_storage_pricing', 'ProductsQuotationController@saveProductStoragePricing');
//Route::group(['middleware' => 'auth'], function ()
//{
//category storage price
Route::post('category/get_product_subcategory', 'SubCategoryController@get_subcategorys_id');
Route::post('subcategory/save_storage_price_subcategory', 'SubCategoryController@save_storage_price_subcategorys');


// agent assigned product
Route::post('products/assigned_agents', 'ProductController@getProductsWithAssignedAgents');
Route::post('agents_logs/create', 'AgentLogController@createAgentLog');
Route::post('agents_logs/get', 'AgentLogController@getAgentsLogs');
Route::post('agents_logs/archive/get', 'AgentLogController@getAgentsArchiveLogs');
Route::get('agents_logs/get/{id}', 'AgentLogController@getAgentLogById');
Route::post('agents_logs/update/{id}', 'AgentLogController@updateAgentLog');
Route::delete('agents_logs/delete/{id}', 'AgentLogController@deleteAgentLog');
Route::post('agents_logs/submit_for_approval/{id}', 'AgentLogController@submitForApproval');
Route::post('agents_logs/archive', 'AgentLogController@archiveLog');
Route::post('agents_logs/archive/restore', 'AgentLogController@archiveRestoreLog');
Route::post('agents_logs/invoice/upload', 'AgentLogController@uploadInvoiceImages');
Route::post('agents_logs/invoice/delete', 'AgentLogController@deleteInvoiceImage');

// WP Seller Product of perticular stage
Route::post('seller/products-for-wp', 'WordpressAPIController@getSellerProductOfStage');
Route::post('seller/products-count-for-wp', 'WordpressAPIController@getSellerProductCount');
Route::post('seller/products-storage-agreement-for-wp', 'WordpressAPIController@getSellerAllStorageAgreement');
Route::post('product/products-info-from-wp-id', 'WordpressAPIController@getProductInforFromWpProductIds');
Route::post('product/send-storage-proposal-from-wp', 'WordpressAPIController@sendStorageProposalFromWP');
Route::post('product/product-info-for-wp', 'WordpressAPIController@getProductInforFromWpProductId');
Route::post('product/product-info-form-wp-product-ids', 'WordpressAPIController@getProductInfoFromMultipleWpProductIds');
Route::post('product/set-same-storage-price-to-products', 'WordpressAPIController@setSameStoragePriceToProducts');
Route::post('product/set-storage-price-to-products', 'WordpressAPIController@setStoragePriceToProduct');

Route::post('seller/products-for-wp', 'WordpressAPIController@getSellerProductOfStage');


// WebHooks //







Route::post('users/get_users', [
    'uses' => 'UsersController@getUsers',
    'as' => 'get_users'
]);

Route::get('users/get_all_users', [
    'uses' => 'UsersController@getAllUsers',
    'as' => 'get_all_users'
]);

Route::post('users/get_user', [
    'uses' => 'UsersController@getUser',
    'as' => 'get_user'
]);
Route::post('users/get_all_copywriters', [
    'uses' => 'UsersController@getAllCopywriters',
    'as' => 'get_all_copywriters'
]);
Route::post('users/get_all_copywriters_and_admins', [
    'uses' => 'UsersController@getAllCopywritersAndAdmins',
    'as' => 'get_all_copywriters_and_admins'
]);

Route::post('users/get_all_agents', 'UsersController@getAllAgents');

Route::post('users/delete_user', [
    'uses' => 'UsersController@deleteUser',
    'as' => 'delete_user'
]);

Route::post('user/change_user_status', [
    'uses' => 'UsersController@changeUserStatus',
    'as' => 'change_user_status'
]);

// ###################################

Route::post('seller/get_seller_city_state', 'SellerController@getSellerCityState');
Route::post('seller/get_seller', [
    'uses' => 'SellerController@getSeller',
    'as' => 'get_seller'
]);

Route::get('seller/get_all_seller', [
    'uses' => 'SellerController@getAllSellers',
    'as' => 'get_all_seller'
]);

Route::post('seller/get_user', [
    'uses' => 'SellerController@getUser',
    'as' => 'get_user'
]);

Route::post('seller/delete_user', [
    'uses' => 'SellerController@deleteUser',
    'as' => 'delete_user'
]);

Route::get('getAllSyncProductsOfSellerHome', [
    'uses' => 'ApiController@getAllSyncProductsOfSellerHome',
    'as' => 'getAllSyncProductsOfSellerHome'
]);
Route::get('brand/saveBrands', [
    'uses' => 'ApiController@saveBrands',
    'as' => 'insert_seller'
]);


Route::get('getAllApprovedProductsQuotationWithStatusNull', [
    'uses' => 'ApiController@getAllApprovedProductsQuotationWithStatusNull',
    'as' => 'getAllApprovedProductsQuotationWithStatusNull'
]);


Route::get('seller/insert_seller', [
    'uses' => 'SellerController@insertSeller',
    'as' => 'insert_seller'
]);

Route::get('seller/update_seller', [
    'uses' => 'SellerController@updateSeller',
    'as' => 'update_seller'
]);

//seller
Route::post('seller/get_sellers', [
    'uses' => 'SellerController@getSellers',
    'as' => 'get_sellers'
]);

Route::post('seller/get_archived_sellers', [
    'uses' => 'SellerController@getArchivedSellers',
    'as' => 'get_archived_sellers'
]);
Route::post('seller/get_product_in_state_sellers', [
    'uses' => 'SellerController@getProductsInStateSellers',
    'as' => 'get_product_in_state_sellers'
]);

Route::post('seller/delete_seller', [
    'uses' => 'SellerController@deleteSeller',
    'as' => 'delete_seller'
]);

Route::post('seller/add_seller', [
    'uses' => 'SellerController@saveSeller',
    'as' => 'add_seller'
]);

Route::post('seller/get_edit_seller', [
    'uses' => 'SellerController@getSeller',
    'as' => 'edit_seller'
]);

Route::post('seller/get_seller_product', [
    'uses' => 'SellerController@getSellerProduct',
    'as' => 'get_seller_product'
]);

Route::post('wp/seller/update', [
    'uses' => 'SellerController@updateWPSeller',
    'as' => 'seller_update'
]);

Route::post('wp/seller/create', [
    'uses' => 'SellerController@saveWPSeller',
    'as' => 'seller_create'
]);

Route::post('wp/seller/save_seller_agreement_renew', [
    'uses' => 'SellerController@saveWPProductRenewsByWpProductId',
    'as' => 'save_seller_agreement_renew'
]);

Route::post('wp/seller/delete', [
    'uses' => 'SellerController@deleteWPSeller',
    'as' => 'seller_delete_wp'
]);


Route::get('wp/seller/getAllAgreements/{wp_seller_id}', [
    'uses' => 'SellerController@getAllSellerAgreementsOfWpSellerId',
    'as' => 'get_all_agreements_wp'
]);


Route::post('wp/taxonomy/update', [
    'uses' => 'SubCategoryController@updateWPCatDetails',
    'as' => 'product_cat_update_wp'
]);
Route::post('wp/taxonomy/create', [
    'uses' => 'SubCategoryController@createWPCatDetails',
    'as' => 'product_cat_create_wp'
]);

Route::post('wp/taxonomy/delete', [
    'uses' => 'SubCategoryController@deleteWPCatDetails',
    'as' => 'product_cat_delete_wp'
]);



// ###################################


Route::post('product/send_products_pricing_proposal', [
    'uses' => 'ProductsQuotationController@savePricingProposalProductForReview',
    'as' => 'send_products_pricing_proposal'
]);

Route::post('product/get_products', [
    'uses' => 'ProductController@getProducts',
    'as' => 'get_products'
]);

Route::post('product/get_product', [
    'uses' => 'ProductController@getProduct',
    'as' => 'get_product'
]);

Route::post('product/delete_product', [
    'uses' => 'ProductController@deleteProduct',
    'as' => 'delete_product'
]);

Route::post('product/save_product', [
    'uses' => 'ProductController@saveProduct',
    'as' => 'save_product'
]);

Route::post('product/reopen_product', [
    'uses' => 'ProductController@reopenProduct',
    'as' => 'reopen_product'
]);

Route::post('wp/product/save_product', [
    'uses' => 'ProductController@saveWpProduct',
    'as' => 'save_wp_product'
]);

Route::post('product/edit_product', [
    'uses' => 'ProductController@editProduct',
    'as' => 'edit_product'
]);

Route::post('product/change_product_status_to_archive', [
    'uses' => 'ProductController@changeProductStatusToArchive',
    'as' => 'change_product_status_to_archive'
]);
Route::post('product/change_product_status', [
    'uses' => 'ProductController@changeProductStatus',
    'as' => 'change_product_status'
]);
Route::post('product/change_product_status_to_reject', [
    'uses' => 'ProductController@changeProductStatusToReject',
    'as' => 'change_product_status_to_reject'
]);

Route::post('product/change_product_delete', [
    'uses' => 'ProductController@changeProductStatusToDelete',
    'as' => 'change_product_delete'
]);

Route::post('product/change_product_status_to_approve', [
    'uses' => 'ProductController@changeProductStatusToApprove',
    'as' => 'change_product_status_to_approve'
]);

Route::post('product/get_archived_products', [
    'uses' => 'ProductController@getArchivedProducts',
    'as' => 'get_archived_products'
]);
Route::post('product/get_all_products_with_status', [
    'uses' => 'ProductController@getAllProductsWithStatus',
    'as' => 'get_all_products_with_status'
]);
Route::post('product/send_proposal', [
    'uses' => 'ProductController@sendProposalMail',
    'as' => 'send_proposal_product'
]);
Route::post('product_quote/send_proposal', [
    'uses' => 'ProductsQuotationController@sendProposalMail',
    'as' => 'send_proposal_step2'
]);

Route::post('export_products', [
    'uses' => 'ExportController@exportProducts',
    'as' => 'export_products'
]);

Route::post('export_today_products', [
    'uses' => 'ExportController@exportProducts',
    'as' => 'export_today_products'
]);

Route::post('export_products_document', [
    'uses' => 'ExportController@downloadProductPdf',
    'as' => 'export_products_document'
]);

Route::post('export_products_document_in_word', [
    'uses' => 'ExportController@downloadProductWord',
    'as' => 'export_products_document_in_word'
]);
Route::post('export_products_document_in_word_proposal', [
    'uses' => 'ExportController@downloadProductWordProposal',
    'as' => 'export_products_document_in_word_proposal'
]);



// ###################################

Route::post('product_approved/get_products_approved', [
    'uses' => 'ProductApprovedController@getProducts',
    'as' => 'get_products_approved'
]);

Route::post('product_approved/get_product_approved', [
    'uses' => 'ProductApprovedController@getProduct',
    'as' => 'get_product_approved'
]);

Route::post('product_approved/delete_product_approved', [
    'uses' => 'ProductApprovedController@deleteProduct',
    'as' => 'delete_product_approved'
]);

Route::post('product_approved/save_product_approved', [
    'uses' => 'ProductApprovedController@saveProduct',
    'as' => 'save_product_approved'
]);

Route::post('product_approved/edit_product_approved', [
    'uses' => 'ProductApprovedController@editProduct',
    'as' => 'edit_product_approved'
]);

Route::post('product_approved/change_product_approved_status', [
    'uses' => 'ProductApprovedController@changeProductStatus',
    'as' => 'change_product_approved_status'
]);



// ###################################

Route::post('schedule/get_schedules', [
    'uses' => 'ScheduleController@getSchedules',
    'as' => 'get_schedules'
]);

Route::post('schedule/get_schedule', [
    'uses' => 'ScheduleController@getSchedule',
    'as' => 'get_schedule'
]);

Route::post('schedule/get_schedule_by_product', [
    'uses' => 'ScheduleController@getScheduleByProductId',
    'as' => 'schedule/get_schedule_by_product'
]);
Route::post('schedule/get_schedule_by_product_quotation_id', [
    'uses' => 'ScheduleController@getScheduleByProductQuotationId',
    'as' => 'schedule/get_schedule_by_product_quotation_id'
]);

Route::post('schedule/delete_schedule', [
    'uses' => 'ScheduleController@deleteSchedule',
    'as' => 'delete_schedule'
]);

Route::post('schedule/save_schedule', [
    'uses' => 'ScheduleController@saveSchedule',
    'as' => 'save_schedule'
]);
Route::post('schedule/save_all_schedule_of_seller', [
    'uses' => 'ScheduleController@saveAllScheduleOfSeller',
    'as' => 'save_schedule'
]);
Route::post('schedule/get_seller_of_prduct_quot', [
    'uses' => 'ScheduleController@getSellerIdOfProductQuot',
    'as' => 'get_seller_of_prduct_quot'
]);

Route::post('schedule/edit_schedule', [
    'uses' => 'ScheduleController@editSchedule',
    'as' => 'edit_schedule'
]);

Route::post('schedule/change_schedule_status', [
    'uses' => 'ScheduleController@changeScheduleStatus',
    'as' => 'change_schedule_status'
]);




// ###################################

Route::post('sell/get_sells', [
    'uses' => 'SellController@getSells',
    'as' => 'get_sells'
]);

Route::post('sell/get_sell', [
    'uses' => 'SellController@getSell',
    'as' => 'get_sell'
]);

Route::post('product/delete_sell', [
    'uses' => 'SellController@deleteSell',
    'as' => 'delete_sell'
]);

// ###################################

Route::post('category/get_categorys', [
    'uses' => 'CategoryController@getCategorys',
    'as' => 'get_categorys'
]);

Route::post('category/get_category', [
    'uses' => 'CategoryController@getCategory',
    'as' => 'get_category'
]);

Route::post('category/delete_category', [
    'uses' => 'CategoryController@deleteCategory',
    'as' => 'delete_category'
]);

Route::post('category/save_category', [
    'uses' => 'CategoryController@saveCategory',
    'as' => 'save_category'
]);


// ###################################


Route::post('subcategory/get_subcategorys', [
    'uses' => 'SubCategoryController@getSubCategorys',
    'as' => 'get_subcategorys'
]);

Route::post('subcategory/get_subcategory', [
    'uses' => 'SubCategoryController@getSubCategory',
    'as' => 'get_subcategory'
]);

Route::post('subcategory/delete_subcategory', [
    'uses' => 'SubCategoryController@deleteSubCategory',
    'as' => 'delete_subcategory'
]);

Route::post('subcategory/save_subcategory', [
    'uses' => 'SubCategoryController@saveSubCategory',
    'as' => 'save_subcategory'
]);
Route::post('subcategory/getAllSubCategorysOfAgeCategory', [
    'uses' => 'SubCategoryController@getAllSubCategorysOfAgeCategory',
    'as' => 'getAllSubCategorysOfAgeCategory'
]);
// ###################################

Route::post('/uploadImages', 'UploadController@uploadImages');
Route::post('/upload_Images', 'UploadController@uploadImages');
Route::post('menu_item/deleteImage/{folder}/{image}/{id}', 'UploadController@deleteUpload');
Route::post('/deleteImage/{folder}/{image}/{id}', 'UploadController@deleteUpload');
Route::post('/deleteImage/{folder}/{image}', 'UploadController@deleteUpload');

Route::post('/product/uploadImages', 'UploadController@uploadProductImages');
Route::post('/product/upload_Images', 'UploadController@uploadProductImages');
Route::post('/product/deleteImage', 'UploadController@deleteProductUpload');
Route::post('/product/deleteImagePending', 'UploadController@deleteProductUploadPending');
Route::post('/product/deleteImageForFirstAdd', 'UploadController@deleteProductUploadForFirstAdd');

Route::get('get_all_categorys', 'CategoryController@getAllCategorys');
Route::get('get_all_subcategorys', 'SubCategoryController@getAllSubCategorys');
Route::get('get_all_subcategorys_of_category_id/{category_id}', 'SubCategoryController@getAllSubCategorysOfCategoryId');

Route::get('get_all_subcategorys_of_product_materials/{category_id}', 'SubCategoryController@getAllSubCategorusOfProductMaterials');

Route::post('save_seller_WP', 'UsersController@saveSeller');
Route::post('is_shop_url_available_WP', 'UsersController@IsShopUrlAvailable');
Route::post('is_seller_email_available_WP', 'UsersController@IsSellerEmailAvailable');


Route::post('category/change_category_status', [
    'uses' => 'CategoryController@changeCategoryStatus',
    'as' => 'change_category_status'
]);

Route::post('subcategory/change_subcategory_status', [
    'uses' => 'SubCategoryController@changeSubCategoryStatus',
    'as' => 'change_subcategory_status'
]);


Route::post('product_report/get_product_report', 'ProductReportController@getProductReport');


//stripe payment
Route::get('payment/{product_id}', 'ProductStoragePriceController@checkProductStoragePrice');
Route::post('payment/produt_payment', 'ProductStoragePriceController@checkProductPayment');


//});
//############################# API route ##########################

Route::get('saveProductLookToMultiple', 'ApiController@saveProductLookToMultiple');
Route::get('saveProductCollectionToMultiple', 'ApiController@saveProductCollectionToMultiple');
Route::post('tlv/apicall', 'ApiController@apiCall');
Route::post('save/rooms', 'ApiController@saveRooms');

Route::get('syncCityAndStateOfProducts', 'ProductController@syncCityAndStateOfProducts');
Route::get('testroute', function()
{
//    if( $_SERVER['REMOTE_ADDR'] == '103.215.158.66' ){
    // $url = 'https://tlv-workflowapp.com/api/wp/taxonomy/update';
//    $url = 'http://tlv.local/';
//    $ch = curl_init();
//    $term_id = 5482;
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, ['test' => 'in']);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//    $response = curl_exec($ch);
//    echo '<pre>';
//    print_r($url);
//    echo '</pre>';
//    echo '<pre>';
//    print_r($response);
//    echo '</pre>';
//    }

    // $email = 'webdeveloper1011@gmail.com';
    $mail = null;

    $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
    try {

        $subject = 'test subject111';
        $message = 'test message222';
        $email = 'jaykishan@esparkinfo.com';

        $mail->isSMTP();
        //    $mail->SMTPDebug = true;
//        $mail->SMTPAuth = true;  // use smpt auth
//        $mail->Host = 'smtp.mandrillapp.com';
//        $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
//        $mail->SMTPSecure = 'tls';
//        $mail->Username = 'The Local Vault';
//        $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';
//
//        $mail->setFrom("sell@thelocalvault.com", "The Local Vault");


        $mail->SMTPAuth = true;  // use smpt auth
        $mail->Host = 'smtp.mandrillapp.com';
        $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
        $mail->Username = 'The Local Vault';
        $mail->Password = 'qvDgdRzAVBHadrQX28K8zw';
        $mail->setFrom("sell@thelocalvault.com", "The Local Vault");

        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->addAddress($email);
        $mail->addBCC('webdeveloper1011@gmail.com');


        if ($mail->send()) {

            try {
                $data = [];

                Log::info('success');

            } catch (\RuntimeException $e) {
                Log::info($e);
                // Content is not encrypted.
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                Log::info($e);
                // Content is not encrypted.
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                Log::info($e);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                Log::info($e);
            }
        }else{
            Log::info('not send');
        }
    } catch (phpmailerException $e) {
        Log::info($e);
        dd($e);
        return 0;
    } catch (Exception $e) {
        Log::info($e);
        dd($e);
        return 0;
    }

    return 1;
});

// ############################ API route end ######################


Route::get('/{basename}', 'ApiController@redirectToLatestGoogleDoc');
Route::post('/updateImagePriority', 'UploadController@updateImagePriority');


Route::group(['prefix' => 'mobile'], function ()
{
    Route::post('login', 'MobileApiController@login');
    Route::get('getOptionsBySelectId/{select_id}', 'OptionController@getOptionsBySelectId');

    Route::group(['middleware' => 'api.auth'], function ()
    {
//Route::group(['prefix'=>'mobile'], function () {

        Route::post('upload_image', 'MobileApiController@uploadImagesMobileApi');
        Route::post('delete_product_image', 'MobileApiController@deleteProductUploadForFirstAddMobileApi');
        Route::post('upload_product_image', 'MobileApiController@uploadProductImagesMobileApi');
        Route::post('user/edit_profile', 'MobileApiController@editProfile');


        Route::post('get_sellers_for_production', 'MobileApiController@getSellersForProduction');
        Route::post('get_sellers_produts_for_production_stage', 'MobileApiController@getProductForProductionsUsingSellerIDMobileApi');
        Route::post('add_seller_product_for_production_stage', 'MobileApiController@AddSellerProductForProductionsMobileApi');
        Route::post('get_pickup_location', 'MobileApiController@getPickupLocations');
        Route::post('add_new_seller', 'MobileApiController@addNewSeller');
        Route::post('check_seller_email_exists', 'MobileApiController@checkEmailExits');
        Route::post('save_new_seller', 'MobileApiController@saveNewSeller');
        Route::post('get_all_sellers', 'MobileApiController@getAllSellers');
        Route::post('save_new_pickup_location', 'MobileApiController@saveNewPickupLocation');
        Route::post('state/get_pickup_state', 'MobileApiController@getPickupLocationState');
        Route::post('edit_seller_product_for_production_stage', 'MobileApiController@EditSellerProductForProductionsMobileApi');
        Route::post('save_product_for_productions', 'MobileApiController@saveProductForProductionsMobileApi');

        Route::post('product_quotation/archive_product_quotation', 'MobileApiController@ArchiveAllProductQuotationApi');
        Route::post('product_quotation/delete_product_quotation', 'MobileApiController@DeleteAllProductQuotationApi');

        Route::post('product_quotation/submit_to_pricing_stage', 'MobileApiController@submitToPricingStage');
        Route::post('product_quotation/submit_multiple_products_to_pricing_stage', 'MobileApiController@submitMultipleProductsToPricingStage');

        //return seller
        Route::post('seller/get_sellerById', 'MobileApiController@getSellerWpIdProductionsMobileApi');
    });
});
