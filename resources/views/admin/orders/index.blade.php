@extends('admin.layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding: 24px 0;
    border-bottom: 3px solid #3b82f6;
}

.orders-title-section h1 {
    font-size: 32px;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.orders-title-section p {
    color: #6b7280;
    font-size: 16px;
    font-weight: 500;
}

.orders-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid #e5e7eb;
    overflow: hidden;
    padding: 32px;
}

.table {
    margin-bottom: 0;
    width: 100%;
}

.table thead th {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
    padding: 16px 24px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-right: 1px solid #e5e7eb;
}

.table tbody td {
    padding: 20px 24px;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
    border-right: 1px solid #f3f4f6;
}

.table tbody tr {
    transition: all 0.2s ease;
    background: white;
}

.table tbody tr:hover {
    background: #f8fafc;
    transform: none;
    box-shadow: none;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 1px solid #f59e0b;
}

.status-processing {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.status-shipped {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: #3730a3;
    border: 1px solid #6366f1;
}

.status-delivered {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border: 1px solid #10b981;
}

.status-cancelled {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border: 1px solid #ef4444;
}

.btn-view {
    padding: 8px 16px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    border: 2px solid #3b82f6;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
}

.btn-view i {
    font-size: 14px;
}

.dataTables_wrapper {
    padding: 0;
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-top: 24px;
}

/* Enhanced Filter Styling */
.dataTables_wrapper .dataTables_filter {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_filter label {
    font-weight: 700;
    color: #1f2937;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 12px 20px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    background: #f9fafb !important;
    width: 300px !important;
    margin-left: 0 !important;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
    background: white !important;
}

.dataTables_wrapper .dataTables_length {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_length label {
    font-weight: 700;
    color: #1f2937;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.dataTables_wrapper .dataTables_length select {
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 12px 20px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    background: #f9fafb !important;
    margin: 0 !important;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
    background: white !important;
}

/* Enhanced Pagination Styling */
.dataTables_wrapper .dataTables_paginate {
    margin-top: 32px;
    margin-bottom: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding: 16px 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 12px 18px !important;
    margin: 0 4px !important;
    border-radius: 12px !important;
    border: 2px solid #e5e7eb !important;
    background: white !important;
    color: #1f2937 !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 44px !important;
    height: 44px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f9fafb !important;
    border-color: #3b82f6 !important;
    color: #000 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    color: #fff !important;
    border-color: #3b82f6 !important;
    font-weight: 700 !important;
    transform: scale(1.05) !important;
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background: #f3f4f6 !important;
    color: #9ca3af !important;
    border-color: #e5e7eb !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    transform: none !important;
    box-shadow: none !important;
    background: #f3f4f6 !important;
    border-color: #e5e7eb !important;
    color: #9ca3af !important;
}

/* Info Styling */
.dataTables_wrapper .dataTables_info {
    margin-top: 16px;
    margin-bottom: 8px;
    text-align: center;
    color: #6b7280 !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    padding: 16px 24px !important;
    background: #f9fafb !important;
    border-radius: 12px !important;
    border: 1px solid #e5e7eb !important;
}

/* Full Height Layout */
body {
    min-height: 100vh;
}

.admin-content {
    min-height: calc(100vh - 80px);
    display: flex;
    flex-direction: column;
}

.orders-card {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.table-responsive {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
        max-width: 300px;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 8px 12px !important;
        font-size: 12px !important;
        min-width: 36px !important;
        height: 36px !important;
    }
}

div.dt-buttons {
    margin-bottom: 24px;
    margin-top: 16px;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    padding: 0 8px;
}

.dt-button {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 700 !important;
    margin-right: 8px !important;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important;
    transition: all 0.3s ease !important;
}

.dt-button:hover {
    background: linear-gradient(135deg, #1d4ed8, #3b82f6) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
}

/* DataTables custom styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin-top: 16px;
    color: #1f2937;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 6px 12px !important;
    margin: 0 2px !important;
    border-radius: 6px !important;
    border: 1px solid #e5e7eb !important;
    background: white !important;
    color: #1f2937 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f9fafb !important;
    border-color: #3b82f6 !important;
    color: #000 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    color: #fff !important;
    border-color: #3b82f6 !important;
    font-weight: 700 !important;
}

.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    margin-left: 8px !important;
    transition: all 0.3s ease !important;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

.dataTables_wrapper .dataTables_length select {
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    margin: 0 8px !important;
    transition: all 0.3s ease !important;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

.filter-section {
    background: linear-gradient(135deg, #f9fafb, #ffffff);
    padding: 24px;
    border-radius: 16px;
    margin-bottom: 32px;
    display: flex;
    gap: 20px;
    align-items: center;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.filter-section select {
    padding: 10px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-section select:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-section label {
    font-weight: 700;
    color: #1f2937;
    margin-right: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<div class="orders-header">
    <div class="orders-title-section">
        <h1>
            <i class="fas fa-shopping-cart"></i>
            Order Management
        </h1>
        <p>View and manage all customer orders</p>
    </div>
</div>

<div class="orders-card">
    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table id="ordersTable" class="table">
            <thead>
                <tr>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Order #</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Customer</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Phone</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Date</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Items</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Total</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Payment</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Shipping</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y-2 divide-gray-200">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <strong class="text-gray-900">{{ $order->order_number }}</strong>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                    </div>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $order->user->phone ?? '-' }}</div>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</div>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $order->orderItems->count() }} items</div>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount + ($order->shipping_cost ?? 0), 0, ',', '.') }}</div>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    @if($order->payment_proof)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Uploaded
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            No Proof
                        </span>
                    @endif
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap border-r border-gray-200">
                    @if($order->tracking_number)
                        <div class="text-sm text-gray-900">{{ $order->tracking_number }}</div>
                    @else
                        <span class="text-sm text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-view">
                        <i class="fas fa-eye"></i>
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#ordersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Export Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Export PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                },
                customize: function(doc) {
                    doc.content[1].table.widths = ['10%', '15%', '15%', '10%', '15%', '10%', '10%', '15%'];
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            infoEmpty: "No orders found",
            infoFiltered: "(filtered from _MAX_ total orders)"
        }
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        var status = $(this).val();
        if (status) {
            table.column(5).search(status).draw();
        } else {
            table.column(5).search('').draw();
        }
    });
});
</script>
@endsection