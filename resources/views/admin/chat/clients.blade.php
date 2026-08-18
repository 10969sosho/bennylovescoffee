@extends('admin.layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
.clients-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding: 24px 0;
    border-bottom: 3px solid #10b981;
}

.clients-title-section h1 {
    font-size: 32px;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.clients-title-section p {
    color: #6b7280;
    font-size: 16px;
    font-weight: 500;
}

.clients-actions {
    display: flex;
    gap: 12px;
}

.btn-export {
    padding: 10px 20px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.clients-card {
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

.badge-role {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-client {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.btn-chat-sm {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
}

.btn-chat-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    background: linear-gradient(135deg, #059669, #10b981);
}

.badge-admin {
    background: linear-gradient(135deg, #fce7f3, #fbcfe8);
    color: #be185d;
    border: 1px solid #ec4899;
}

.btn-chat-sm {
    padding: 8px 16px;
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    border: 2px solid #d4af37;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(212, 175, 55, 0.2);
}

.btn-chat-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
    background: linear-gradient(135deg, #f0d872, #d4af37);
}

.btn-chat-sm i {
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
    border-color: #d4af37 !important;
    outline: none !important;
    box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15) !important;
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
    border-color: #d4af37 !important;
    outline: none !important;
    box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15) !important;
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
    border-color: #d4af37 !important;
    color: #000 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #d4af37, #f0d872) !important;
    color: #000 !important;
    border-color: #d4af37 !important;
    font-weight: 700 !important;
    transform: scale(1.05) !important;
    box-shadow: 0 4px 16px rgba(212, 175, 55, 0.3) !important;
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
    text-align: center;
    color: #6b7280 !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    padding: 12px 20px !important;
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

.clients-card {
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
    .clients-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .clients-actions {
        width: 100%;
        justify-content: center;
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
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.dt-button {
    background: linear-gradient(135deg, #d4af37, #f0d872) !important;
    color: #000 !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 700 !important;
    margin-right: 8px !important;
    box-shadow: 0 2px 4px rgba(212, 175, 55, 0.3) !important;
    transition: all 0.3s ease !important;
}

.dt-button:hover {
    background: linear-gradient(135deg, #f0d872, #d4af37) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4) !important;
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
    border-color: #10b981 !important;
    color: #000 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: #fff !important;
    border-color: #10b981 !important;
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
    border-color: #10b981 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}

.dataTables_wrapper .dataTables_length select {
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    margin: 0 8px !important;
    transition: all 0.3s ease !important;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: #10b981 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}
</style>

<div class="clients-header">
    <div class="clients-title-section">
        <h1>
            <i class="fas fa-users"></i>
            Registered Clients
        </h1>
        <p>Manage and view all registered users</p>
    </div>
</div>

<div class="clients-card">
    <div class="overflow-x-auto">
        <table id="clientsTable" class="table">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Phone</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Registered Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Total Orders</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Unread Messages</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y-2 divide-gray-200">
            @foreach($clients as $client)
            <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm font-medium text-gray-900">{{ $client->id }}</div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr($client->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $client->email }}</div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $client->phone ?? '-' }}</div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <span class="badge-role {{ $client->role === 'admin' || $client->role === 'superadmin' ? 'badge-admin' : 'badge-client' }}">
                        {{ ucfirst($client->role) }}
                    </span>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $client->created_at->format('M d, Y') }}</div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    <div class="text-sm text-gray-900">{{ $client->orders->count() }}</div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                    @if($client->unread_count > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $client->unread_count }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-6 py-6 whitespace-nowrap">
                    <a href="{{ route('admin.chat.show', $client) }}" class="btn-chat-sm">
                        <i class="fas fa-comments"></i>
                        Chat
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
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
    $('#clientsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Export Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Export PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ clients",
            infoEmpty: "No clients found",
            infoFiltered: "(filtered from _MAX_ total clients)"
        }
    });
});
</script>
@endsection