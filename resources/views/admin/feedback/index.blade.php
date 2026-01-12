@extends('layouts.admin')

@section('title', 'Manage Feedback | Admin Panel')

@section('content')
<h1 class="page-title">Customer Feedback</h1>

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="feedbackSearch" placeholder="🔍 Search feedback..." class="admin-input search-input">
</div>

<div class="table-container">
    <div class="pagination-header">
        <form method="GET" action="{{ url()->current() }}" class="per-page-form">
            <label>Rows:</label>
            <select name="per_page" onchange="this.form.submit()" class="admin-input">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                <option value="All" {{ $perPage == 'All' ? 'selected' : '' }}>All</option>
            </select>
            @foreach(request()->except('per_page', 'page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Message</th>
                    <th>Reply Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $f)
                <tr>
                    <td>{{ $f->feedback_id }}</td>
                    <td>{{ $f->customer->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($f->comment, 40) }}</td>
                    <td>
                        <span class="status {{ $f->reply ? 'replied' : 'not-replied' }}">
                            @if(!$f->reply) 
                                <i class="fas fa-clock"></i> Not Replied 
                            @else 
                                <i class="fas fa-check-circle"></i> Replied 
                            @endif
                        </span>
                    </td>
                    <td class="admin-actions">
                        <button type="button" class="btn-action"
                            data-id="{{ $f->feedback_id }}"
                            data-comment="{{ $f->comment }}"
                            data-reply="{{ $f->reply ?? '' }}"
                            data-isreplied="{{ $f->reply ? '1' : '0' }}"
                            title="{{ $f->reply ? 'View Reply' : 'Reply to Feedback' }}">
                            @if(!$f->reply) 
                                <i class="fas fa-reply"></i>
                            @else 
                                <i class="fas fa-eye"></i>
                            @endif
                        </button>
                    </td>
                </tr>
                @endforeach

                @if($feedbacks->isEmpty())
                <tr>
                    <td colspan="5" class="empty">No feedback found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($feedbacks as $f)
        <div class="feedback-card">
            <div class="card-header">
                <div class="feedback-icon {{ $f->reply ? 'replied-icon' : 'pending-icon' }}">
                    <i class="fas fa-{{ $f->reply ? 'check-circle' : 'comment-dots' }}"></i>
                </div>
                <div class="feedback-info">
                    <h3>{{ $f->customer->name ?? 'N/A' }}</h3>
                    <span class="feedback-id">ID: {{ $f->feedback_id }}</span>
                </div>
                <span class="status {{ $f->reply ? 'replied' : 'not-replied' }}">
                    @if(!$f->reply) 
                        <i class="fas fa-clock"></i> Pending
                    @else 
                        <i class="fas fa-check-circle"></i> Replied
                    @endif
                </span>
            </div>
            <div class="card-body">
                <div class="feedback-message">
                    <i class="fas fa-comment"></i>
                    <p>{{ $f->comment }}</p>
                </div>
                @if($f->reply)
                <div class="feedback-reply">
                    <i class="fas fa-reply"></i>
                    <p>{{ $f->reply }}</p>
                </div>
                @endif
            </div>
            <div class="card-actions">
                <button type="button" class="btn-action"
                    data-id="{{ $f->feedback_id }}"
                    data-comment="{{ $f->comment }}"
                    data-reply="{{ $f->reply ?? '' }}"
                    data-isreplied="{{ $f->reply ? '1' : '0' }}">
                    @if(!$f->reply) 
                        <i class="fas fa-reply"></i> Reply
                    @else 
                        <i class="fas fa-eye"></i> View
                    @endif
                </button>
            </div>
        </div>
        @empty
        <p class="empty">No feedback found.</p>
        @endforelse
    </div>
</div>

<!-- Reply / View Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3>
            <i class="fas fa-comments"></i> Customer Feedback
        </h3>
        <div class="feedback-comment" id="feedbackComment"></div>

        <form id="replyForm" method="POST">
            @csrf
            <div class="form-group">
                <label>
                    <i class="fas fa-reply"></i> Your Reply
                </label>
                <textarea name="reply_message" id="replyText" rows="4" placeholder="Type your reply..." class="admin-input"></textarea>
            </div>
            <button type="submit" class="create-btn">
                <i class="fas fa-paper-plane"></i> Send Reply
            </button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
/* Base Styles */
.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* Search Input */
.search-input {
    max-width: 350px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
    width: 100%;
}

.search-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255,87,34,0.4);
    outline: none;
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.pagination-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.per-page-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.per-page-form label {
    font-weight: 600;
    color: #555;
}

.per-page-form select {
    width: auto;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.desktop-table {
    display: block;
    overflow-x: auto;
}

.mobile-cards {
    display: none;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    min-width: 700px;
}

.admin-table th, .admin-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #ff5722;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    white-space: nowrap;
}

.admin-table tbody tr:nth-child(even) {
    background: #fff7f0;
}

.admin-table tbody tr:hover {
    background: #ffe0d6;
    transition: 0.3s;
}

/* Status Badges */
.status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.not-replied {
    background: #fff3cd;
    color: #856404;
}

.status.replied {
    background: #d4edda;
    color: #155724;
}

/* Action Buttons */
.admin-actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.btn-action {
    background: var(--green-turquoise);
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-action:hover {
    background: var(--green-turquoise-dark);
    transform: translateY(-2px);
}

.empty {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-style: italic;
}

/* Mobile Card Styles */
.feedback-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.feedback-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.feedback-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pending-icon {
    background: linear-gradient(135deg, #ffc107, #ff9800);
}

.replied-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.feedback-info {
    flex: 1;
}

.feedback-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.feedback-id {
    font-size: 12px;
    color: #999;
    font-weight: 600;
}

.card-body {
    margin-bottom: 15px;
}

.feedback-message, .feedback-reply {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.feedback-message {
    background: #f8f9fa;
    border-left: 4px solid #ff5722;
}

.feedback-reply {
    background: #e8f5e9;
    border-left: 4px solid #28a745;
}

.feedback-message i, .feedback-reply i {
    color: #ff5722;
    font-size: 16px;
    margin-top: 2px;
}

.feedback-reply i {
    color: #28a745;
}

.feedback-message p, .feedback-reply p {
    margin: 0;
    flex: 1;
    font-size: 14px;
    color: #555;
    line-height: 1.5;
}

.card-actions {
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.card-actions .btn-action {
    width: 100%;
    justify-content: center;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    padding: 30px 25px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content h3 {
    background: linear-gradient(90deg, #ff5722, #ff784e);
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    margin: -30px -25px 20px -25px;
    font-size: 18px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff3d00;
    color: white;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 10;
}

.close-modal:hover {
    background: #e53935;
    transform: rotate(90deg);
}

.feedback-comment {
    padding: 15px;
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: left;
    min-height: 60px;
    background: #f8f9fa;
    font-size: 14px;
    line-height: 1.6;
    color: #555;
}

.form-group {
    margin-bottom: 18px;
    text-align: left;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.form-group label i {
    color: #ff5722;
    width: 16px;
}

.admin-input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: all 0.3s;
    font-size: 14px;
    font-family: inherit;
}

.admin-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255,87,34,0.1);
    outline: none;
}

textarea.admin-input {
    resize: vertical;
    min-height: 100px;
}

.create-btn {
    background: #28a745;
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 15px;
}

.create-btn:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Responsive Styles */
@media(max-width: 768px) {
    .page-title {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .pagination-header {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .per-page-form {
        justify-content: space-between;
        width: 100%;
    }

    /* Hide desktop table, show mobile cards */
    .desktop-table {
        display: none;
    }

    .mobile-cards {
        display: block;
    }

    .table-container {
        padding: 15px;
    }

    .modal-content {
        padding: 25px 20px;
        max-height: 85vh;
    }

    .modal-content h3 {
        margin: -25px -20px 15px -20px;
        font-size: 16px;
    }
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .feedback-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .feedback-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }

    .feedback-info h3 {
        font-size: 16px;
    }

    .feedback-message, .feedback-reply {
        font-size: 13px;
        padding: 10px;
    }

    .status {
        font-size: 11px;
        padding: 4px 10px;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Open feedback modal
    document.querySelectorAll('.btn-action').forEach(function(button){
        button.addEventListener('click', function(){
            const feedback_id = this.dataset.id;
            const comment = this.dataset.comment;
            const reply = this.dataset.reply;
            const isReplied = this.dataset.isreplied === '1';

            const modal = document.getElementById('feedbackModal');
            const textarea = document.getElementById('replyText');
            document.getElementById('feedbackComment').innerText = comment;
            textarea.value = reply;

            if(isReplied){
                textarea.setAttribute('readonly', true);
                textarea.style.background = '#f8f9fa';
                document.querySelector('#replyForm button[type="submit"]').style.display = 'none';
            } else {
                textarea.removeAttribute('readonly');
                textarea.style.background = 'white';
                document.querySelector('#replyForm button[type="submit"]').style.display = 'flex';
            }

            document.getElementById('replyForm').action = `/admin/feedback/${feedback_id}/reply`;
            modal.style.display = 'flex';
        });
    });

    // Live Search Functionality
    const feedbackSearchInput = document.getElementById('feedbackSearch');
    if (feedbackSearchInput) {
        feedbackSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search in desktop table
            document.querySelectorAll('.desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in mobile cards
            document.querySelectorAll('.mobile-cards .feedback-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// Close modal
function closeModal(){
    document.getElementById('feedbackModal').style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('feedbackModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
@endsection