@extends('layouts.admin')

@section('title', 'Manage Feedback | Admin Panel')

@section('content')
<h1 class="page-title">Customer Feedback</h1>

<div class="table-container">
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
                        @if(!$f->reply) ❌ Not Replied @else ✅ Replied @endif
                    </span>
                </td>
                <td class="admin-actions">
                    <button type="button" class="btn-action"
                        data-id="{{ $f->feedback_id }}"
                        data-comment="{{ $f->comment }}"
                        data-reply="{{ $f->reply ?? '' }}"
                        data-isreplied="{{ $f->reply ? '1' : '0' }}">
                        @if(!$f->reply) Reply @else View @endif
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

    <div style="margin-top:15px;">
        {{ $feedbacks->links() }}
    </div>
</div>

<!-- Reply / View Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">×</span>
        <h3>Feedback</h3>
        <p id="feedbackComment" class="feedback-comment"></p>

        <form id="replyForm" method="POST">
            @csrf
            <textarea name="reply_message" id="replyText" rows="4" placeholder="Type your reply..." class="admin-input"></textarea>
            <button type="submit" class="create-btn">Send Reply</button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
}

.table-container { 
    background:white; border-radius:12px; padding:20px; overflow-x:auto; 
    box-shadow:0 6px 20px rgba(0,0,0,0.08); 
}
.admin-table { width:100%; border-collapse:collapse; font-size:14px; }
.admin-table th, .admin-table td { padding:12px 16px; text-align:left; }
.admin-table th { background:#ff5722; color:white; font-weight:600; letter-spacing:0.5px; text-transform:uppercase; }
.admin-table tr:nth-child(even) { background:#fff7f0; }
.admin-table tr:hover { background:#ffe0d6; transition:0.3s; }
.empty { text-align:center; color:#999; font-weight:600; padding:15px; }

.status { padding:5px 12px; border-radius:12px; font-size:12px; font-weight:600; display:inline-block; text-align:center; }
.status.not-replied { background:#f8d7da; color:#721c24; }
.status.replied { background:#d4edda; color:#155724; }

.btn-action { 
    display:inline-block; background:#ff9800; color:white; padding:8px 14px; 
    border-radius:8px; font-weight:600; cursor:pointer; border:none; transition:0.3s;
}
.btn-action:hover { background:#f57c00; }

.create-btn { 
    background:#28a745; color:white; padding:10px 15px; border-radius:8px; font-weight:600; border:none; cursor:pointer; width:100%; margin-top:10px; 
    transition:0.3s;
}
.create-btn:hover { background:#218838; }

.modal { 
    display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1001;
}
.modal-content { 
    background:white; border-radius:12px; width:480px; max-width:95%; 
    padding:25px 30px; position:relative; box-shadow:0 8px 30px rgba(0,0,0,0.2); text-align:center; 
}
.modal-content h3 { 
    background: linear-gradient(90deg, #ff5722, #ff784e); 
    color:white; padding:12px 15px; border-radius:8px; margin-bottom:20px; 
    font-size:18px; 
}
.feedback-comment { 
    padding:12px; border:1px solid #ccc; border-radius:8px; margin-bottom:15px; text-align:left; min-height:50px;
}
.admin-input { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; transition:0.3s; }
.admin-input:focus { border-color:#ff5722; box-shadow:0 0 5px rgba(255,87,34,0.5); outline:none; }

.close-modal { 
    position:absolute; top:15px; right:15px; background:#ff3d00; color:white; 
    width:32px; height:32px; border:none; border-radius:50%; font-size:20px; 
    cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; 
}
.close-modal:hover { background:#e53935; }

@media(max-width:768px){ 
    .admin-table th, .admin-table td { font-size:14px; padding:10px; } 
    .page-title { font-size:24px; } 
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                document.querySelector('#replyForm button[type="submit"]').style.display = 'none';
            } else {
                textarea.removeAttribute('readonly');
                document.querySelector('#replyForm button[type="submit"]').style.display = 'inline-block';
            }

            document.getElementById('replyForm').action = `/admin/feedback/${feedback_id}/reply`;
            modal.style.display = 'flex';
        });
    });
});

function closeModal(){
    document.getElementById('feedbackModal').style.display = 'none';
}
</script>
@endsection
