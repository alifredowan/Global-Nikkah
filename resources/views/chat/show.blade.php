@extends('layouts.app')

@section('title', 'Conversation — Global Nikkah')

@section('content')
<div class="py-6 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-4">

        <!-- Top Navigation & Details -->
        <div class="card-ledger p-4 sm:p-5 bg-[var(--bg-surface)] flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('chat.index') }}" class="btn-secondary h-9 px-3 text-xs font-semibold shrink-0 inline-flex items-center gap-1.5">
                    <span aria-hidden="true">&larr;</span>
                    <span>Inbox</span>
                </a>
                <div class="h-4 w-px bg-[var(--border-subtle)] hidden sm:block"></div>
                <div class="min-w-0">
                    <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)] flex items-center gap-2 flex-wrap">
                        @if($waliObserver && Auth::id() === $waliObserver->id)
                            <span>{{ $conversation->participants->where('pivot.role', 'seeker')->pluck('name')->join(' & ') }}</span>
                            <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                Observing as Guardian
                            </span>
                        @else
                            <span>{{ $otherUser?->name ?? 'Candidate' }}</span>
                            @if($otherUser?->is_verified)
                                <span class="badge-verified" title="Verified Profile">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span>Verified</span>
                                </span>
                            @endif
                            @if($waliObserver)
                                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px] flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    <span>Wali: {{ $waliObserver->name }}</span>
                                </span>
                            @endif
                        @endif
                    </h2>
                    <p class="text-[11px] text-[var(--text-muted)]">Chaperoned Matrimonial Discussion</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                @if($marriage)
                    <a href="{{ route('marriages.celebration') }}" class="btn-secondary h-9 px-3.5 text-xs font-semibold text-[var(--accent-text)] border-[var(--accent-border)] hover:bg-[var(--accent-subtle)] inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[var(--accent-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        @if($marriage->isConfirmed())
                            <span>Nikah Mubarak &rarr;</span>
                        @else
                            <span>Nikah Pending &rarr;</span>
                        @endif
                    </a>
                @elseif($otherUser && !Auth::user()->isMarried() && !$otherUser->isMarried() && $conversation->status !== 'locked' && (!$waliObserver || Auth::id() !== $waliObserver->id))
                    <button type="button" onclick="document.getElementById('declareNikahModal').classList.remove('hidden')" class="btn-primary h-9 px-3.5 text-xs font-semibold inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Declare Nikah</span>
                    </button>
                @endif

                <!-- Report User Button -->
                <button type="button" onclick="document.getElementById('reportChatModal').classList.remove('hidden')" class="h-9 px-2.5 text-xs font-medium text-[var(--text-muted)] hover:text-[var(--color-danger)] transition inline-flex items-center gap-1 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    <span>Report</span>
                </button>
            </div>
        </div>

        <!-- Chaperone Banner (FR-4.3, 6.2) -->
        @if($waliObserver)
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] flex items-start sm:items-center gap-3">
                <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--bg-surface)] text-[var(--accent-text)] border border-[var(--accent-border)] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="text-xs text-[var(--text-secondary)] leading-relaxed">
                    <strong class="font-semibold text-[var(--text-primary)]">Guardian (Wali) Chaperone Present:</strong>
                    <span>{{ $waliObserver->name }} is observing this conversation to ensure Islamic marriage etiquette and dignity for both families.</span>
                </div>
            </div>
        @endif

        <!-- Message Privacy Notice -->
        <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-subtle)] border border-[var(--border-subtle)] text-xs text-[var(--text-muted)] flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[var(--accent-text)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><strong>Privacy Notice:</strong> Direct telephone numbers and off-platform links stay shielded until both families agree to advance.</span>
        </div>

        <!-- Message Thread Box -->
        <div class="card-ledger bg-[var(--bg-surface)] p-5 sm:p-6 min-h-[440px] flex flex-col justify-between">
            <div id="chat-messages-container" class="space-y-4 mb-6 overflow-y-auto max-h-[500px] pr-2 scroll-smooth">
                @forelse($conversation->messages as $msg)
                    @php
                        $isMe = $msg->sender_id === Auth::id();
                        $isWali = $msg->sender?->isWali() || ($waliObserver && $msg->sender_id === $waliObserver->id);
                    @endphp

                    <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}" data-message-id="{{ $msg->id }}">
                        <div class="flex items-center gap-1.5 text-[11px] text-[var(--text-muted)] mb-1 px-1">
                            <span class="font-medium text-[var(--text-secondary)]">{{ $msg->sender->name }}</span>
                            @if($isWali)
                                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[9px] py-0 px-1.5">Guardian Chaperone</span>
                            @endif
                            <span>&bull; {{ $msg->created_at->format('h:i A') }}</span>
                        </div>

                        <div class="max-w-md rounded-2xl px-4 py-3 text-xs leading-relaxed shadow-2xs {{ $isMe ? 'bg-[var(--brand-primary)] text-white rounded-br-xs' : ($isWali ? 'bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--text-primary)] rounded-bl-xs' : 'bg-[var(--bg-subtle)] text-[var(--text-primary)] border border-[var(--border-default)] rounded-bl-xs') }}">
                            <p class="whitespace-pre-line">{{ $msg->body }}</p>
                        </div>

                        @if($msg->is_flagged)
                            <div class="text-[10px] text-[var(--color-warning)] font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <span>Filtered: {{ $msg->flag_reason }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div id="chat-empty-state" class="py-14 text-center text-xs text-[var(--text-muted)]">
                        <p class="font-serif text-base text-[var(--text-secondary)]">No messages in this conversation yet</p>
                        <p class="mt-1">Begin with a warm greeting: &ldquo;Assalamu Alaikum&rdquo;.</p>
                    </div>
                @endforelse
            </div>

            @php
                $myPivot = $conversation->participants->firstWhere('id', Auth::id())?->pivot;
                $isViewOnlyWali = false;
                if ($myPivot && $myPivot->role === 'wali_chaperone') {
                    $isViewOnlyWali = \App\Models\WaliLink::where('wali_user_id', Auth::id())
                        ->whereIn('seeker_user_id', $conversation->participants->pluck('id'))
                        ->where('permission_level', 'view_only')
                        ->exists();
                }
            @endphp

            @if($conversation->status === 'locked')
                <div class="pt-4 border-t border-[var(--border-subtle)] text-center py-3.5 px-4 text-xs text-[var(--text-secondary)] bg-[var(--bg-canvas)] rounded-[var(--radius-md)] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>This conversation is locked ({{ $conversation->locked_reason === 'candidate_married' ? 'Participant completed Nikah milestone' : 'Archived' }}). Messages are preserved as read-only.</span>
                </div>
            @elseif($isViewOnlyWali)
                <div class="pt-4 border-t border-[var(--border-subtle)] text-center py-3.5 px-4 text-xs text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] rounded-[var(--radius-md)]">
                    You are observing this conversation with <strong>View Only</strong> guardian permissions.
                </div>
            @else
                <!-- Send Message Input Form -->
                <form id="chat-send-form" action="{{ route('chat.send', $conversation->id) }}" method="POST" class="pt-4 border-t border-[var(--border-subtle)]">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <textarea id="chat-message-input" name="body" rows="2" required
                                      class="w-full px-3.5 py-2.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] resize-none transition"
                                      placeholder="Write your message with Islamic courtesy (Press Enter to send)..."></textarea>
                        </div>
                        <button type="submit" id="chat-submit-btn" class="btn-primary h-12 px-6 text-sm font-bold tracking-wide shrink-0 inline-flex items-center gap-2 cursor-pointer">
                            <span>Send</span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </form>
            @endif
        </div>

    </div>
</div>

<!-- Report Modal -->
<div id="reportChatModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="card-ledger bg-[var(--bg-surface)] max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-serif text-lg font-semibold text-[var(--text-primary)]">Report Inappropriate Behavior</h3>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">Our moderation team reviews reports confidentially.</p>
            </div>
            <button type="button" onclick="document.getElementById('reportChatModal').classList.add('hidden')" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-sm cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="reported_id" value="{{ $otherUser?->id }}">
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">

            <div>
                <label for="report_reason" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">Reason</label>
                <select id="report_reason" name="reason" required class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                    <option value="inappropriate_language">Disrespectful or inappropriate language</option>
                    <option value="unsolicited_contact_push">Unsolicited push for off-platform contact</option>
                    <option value="harassment">Harassment or coercion</option>
                </select>
            </div>

            <div>
                <label for="report_details" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">Details (Optional)</label>
                <textarea id="report_details" name="details" rows="3" class="w-full p-3 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]" placeholder="Briefly describe what happened..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('reportChatModal').classList.add('hidden')" class="btn-secondary h-10 px-4 text-xs font-semibold cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="h-10 px-4 text-xs font-semibold rounded-[var(--radius-md)] bg-[var(--color-danger)] text-white hover:opacity-90 transition cursor-pointer">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

@if($otherUser && !Auth::user()->isMarried() && !$otherUser->isMarried() && $conversation->status !== 'locked')
<!-- Declare Nikah Completion Modal -->
<div id="declareNikahModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="card-ledger bg-[var(--bg-surface)] max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5">
        <div class="flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">Sacred Milestone</span>
                <h3 class="font-serif text-2xl font-semibold text-[var(--text-primary)]">Declare Nikah Milestone</h3>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                    Confirm that you and {{ $otherUser->name }} have completed your Nikah under the Sunnah of Allah's Messenger &lrm;ﷺ.
                </p>
            </div>
            <button type="button" onclick="document.getElementById('declareNikahModal').classList.add('hidden')" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('marriages.initiate') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="spouse_id" value="{{ $otherUser->id }}">

            <div>
                <label for="marriage_date" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">Nikah Date (dd/mm/yyyy)</label>
                <input type="text" id="marriage_date" name="marriage_date" value="{{ date('d/m/Y') }}" required
                       inputmode="numeric" placeholder="dd/mm/yyyy" maxlength="10"
                       pattern="\d{2}/\d{2}/\d{4}"
                       x-data
                       x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/^(\d{2})(\d)/, '$1/$2').replace(/^(\d{2})\/(\d{2})(\d)/, '$1/$2/$3').slice(0, 10)"
                       class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
            </div>

            <div>
                <label for="confirmation_notes" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">Personal Reflection / Story (Optional)</label>
                <textarea id="confirmation_notes" name="confirmation_notes" rows="3" placeholder="Alhamdulillah, our families met and our Nikah ceremony was conducted with grace..."
                          class="w-full p-3 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]"></textarea>
            </div>

            <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-xs text-[var(--text-secondary)] leading-relaxed">
                Once submitted, a confirmation prompt will be sent to <strong>{{ $otherUser->name }}</strong>. Upon their agreement, both accounts will transition to Married, public search profiles will be shielded, and your story can inspire others on the Nikah Mubarak page.
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('declareNikahModal').classList.add('hidden')" class="btn-secondary h-11 px-5 text-xs font-semibold cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="btn-primary h-11 px-6 text-xs font-semibold tracking-wide cursor-pointer">
                    Submit Nikah Declaration
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('chat-messages-container');
    const form = document.getElementById('chat-send-form');
    const input = document.getElementById('chat-message-input');
    const emptyState = document.getElementById('chat-empty-state');
    const currentUserId = {{ Auth::id() }};
    const conversationId = {{ $conversation->id }};
    const waliObserverId = {{ $waliObserver ? $waliObserver->id : 'null' }};

    // Track already rendered message IDs to guarantee zero duplicates
    const renderedMessageIds = new Set();
    if (container) {
        container.querySelectorAll('[data-message-id]').forEach(el => {
            const id = el.getAttribute('data-message-id');
            if (id) renderedMessageIds.add(String(id));
        });
    }

    function scrollToBottom() {
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    scrollToBottom();

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Append a message bubble to the chat container
    function appendMessageBubble(msg, isMe, isWali) {
        if (!msg) return;
        const msgId = msg.id ? String(msg.id) : null;

        // Check if message with this ID already rendered
        if (msgId && renderedMessageIds.has(msgId)) {
            return;
        }
        if (msgId && container && container.querySelector(`[data-message-id="${msgId}"]`)) {
            renderedMessageIds.add(msgId);
            return;
        }
        if (msgId) {
            renderedMessageIds.add(msgId);
        }

        if (emptyState && emptyState.parentElement) {
            emptyState.remove();
        }

        const wrapper = document.createElement('div');
        wrapper.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'} transition-opacity duration-200`;
        if (msgId) wrapper.setAttribute('data-message-id', msgId);

        const waliBadge = isWali ? '<span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[9px] py-0 px-1.5">Guardian Chaperone</span>' : '';
        const bubbleStyle = isMe 
            ? 'bg-[var(--brand-primary)] text-white rounded-br-xs' 
            : (isWali ? 'bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--text-primary)] rounded-bl-xs' : 'bg-[var(--bg-subtle)] text-[var(--text-primary)] border border-[var(--border-default)] rounded-bl-xs');

        const flaggedNotice = msg.is_flagged 
            ? `<div class="text-[10px] text-[var(--color-warning)] font-medium mt-1 flex items-center gap-1"><span>Filtered: ${escapeHtml(msg.flag_reason || 'Sensitive info')}</span></div>` 
            : '';

        wrapper.innerHTML = `
            <div class="flex items-center gap-1.5 text-[11px] text-[var(--text-muted)] mb-1 px-1">
                <span class="font-medium text-[var(--text-secondary)]">${escapeHtml(msg.sender_name || 'User')}</span>
                ${waliBadge}
                <span>&bull; ${escapeHtml(msg.created_at || 'Just now')}</span>
            </div>
            <div class="max-w-md rounded-2xl px-4 py-3 text-xs leading-relaxed shadow-2xs ${bubbleStyle}">
                <p class="whitespace-pre-line">${escapeHtml(msg.body)}</p>
            </div>
            ${flaggedNotice}
        `;

        container.appendChild(wrapper);
        scrollToBottom();
    }

    // Handle AJAX message submit
    const submitBtn = document.getElementById('chat-submit-btn');

    if (form && input) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
            }

            const socketId = (window.Echo && typeof window.Echo.socketId === 'function') ? window.Echo.socketId() : null;
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (socketId) {
                headers['X-Socket-ID'] = socketId;
            }

            fetch(form.action, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ body: text })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errData => {
                        throw new Error(errData.error || errData.message || 'Server returned error ' + res.status);
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.success && data.message) {
                    input.value = '';
                    appendMessageBubble(data.message, true, false);
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(err => {
                console.error('Send message failed:', err);
                alert('Could not send message: ' + (err.message || 'Please check your connection and try again.'));
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                }
                input.focus();
            });
        });

        // Submit on Enter without Shift
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    // Reverb WebSocket listener for live chat messages
    function setupConversationEcho() {
        if (!window.Echo) return;
        const handleIncoming = function(e) {
            if (!e) return;
            // Only render if sent by another participant
            if (parseInt(e.sender_id) !== currentUserId) {
                const isWali = Boolean(e.is_wali) || (waliObserverId !== null && parseInt(e.sender_id) === waliObserverId);
                appendMessageBubble(e, false, isWali);
            }
        };

        window.Echo.private('conversation.' + conversationId)
            .listen('.MessageSentEvent', handleIncoming)
            .listen('MessageSentEvent', handleIncoming);
    }

    if (window.Echo) {
        setupConversationEcho();
    } else {
        window.addEventListener('echo:ready', setupConversationEcho);
        let echoTimer = setInterval(() => {
            if (window.Echo) {
                clearInterval(echoTimer);
                setupConversationEcho();
            }
        }, 200);
    }
});
</script>
@endsection
