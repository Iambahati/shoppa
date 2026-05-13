<?php

namespace App\Enums;

enum PermissionName: string
{
    // User mgt
    case ViewUsers   = 'view_users';
    case CreateUsers = 'create_users';
    case EditUsers   = 'edit_users';
    case DeleteUsers = 'delete_users';

    // Role mgt
    case ViewRoles   = 'view_roles';
    case ManageRoles = 'manage_roles';

    // Vendor mgt
    case ViewVendors   = 'view_vendors';
    case CreateVendors = 'create_vendors';
    case EditVendors   = 'edit_vendors';
    case DeleteVendors = 'delete_vendors';
    case ApproveVendors = 'approve_vendors';

    // Product / catalog
    case ViewProducts    = 'view_products';
    case CreateProducts  = 'create_products';
    case EditProducts    = 'edit_products';
    case DeleteProducts  = 'delete_products';
    case ManageCategories = 'manage_categories';

    // Orders
    case ViewOrders   = 'view_orders';
    case ManageOrders = 'manage_orders';
    case CancelOrders = 'cancel_orders';

    // Payments
    case ViewPayments   = 'view_payments';
    case ManagePayments = 'manage_payments';
    case ManageRefunds  = 'manage_refunds';

    // Trust & verification
    case VerifyDevices    = 'verify_devices';
    case IssueCerts       = 'issue_certs';
    case RevokeCerts      = 'revoke_certs';
    case ViewInspections  = 'view_inspections';

    // Customer data & support
    case ViewCustomerData    = 'view_customer_data';
    case ManageSupportTickets = 'manage_support_tickets';
    case ManageDisputes      = 'manage_disputes';

    // Content
    case ContentManage = 'content_manage';

    // Theft / registry
    case ManageTheftReports = 'manage_theft_reports';

    // Logistics
    case ManageShipments = 'manage_shipments';
}