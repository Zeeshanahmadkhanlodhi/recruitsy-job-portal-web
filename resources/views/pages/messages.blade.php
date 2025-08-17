@extends('layouts.dashboard')

@section('title', 'Messages - RecruitSy')
@section('page-title', 'Messages')

@section('content')
<div class="messages-page">
    <div class="chat-layout">
        <aside class="chat-sidebar" id="chat-conversations">
            <div class="placeholder">Loading…</div>
        </aside>
        <section class="chat-main">
            <header class="chat-header" id="chat-header">Select a conversation</header>
            <div class="chat-messages" id="chat-messages"></div>
            <form id="chat-form" class="chat-input" autocomplete="off">
                <input type="text" id="chat-text" placeholder="Type your message…" />
                <button class="btn btn-primary btn-sm" type="submit">Send</button>
            </form>
        </section>
    </div>
</div>

<style>
.messages-page { display: block; }
.chat-layout { display: grid; grid-template-columns: 320px 1fr; gap: 1rem; }
.chat-sidebar { border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #fff; max-height: calc(100vh - 260px); overflow: auto; }
.chat-main { border: 1px solid #e5e7eb; border-radius: 0.5rem; display: flex; flex-direction: column; background: #fff; min-height: 520px; }
.chat-header { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #1f2937; }
.chat-messages { flex: 1; padding: 1rem; overflow: auto; }
.chat-input { display: flex; gap: 0.5rem; padding: 0.75rem; border-top: 1px solid #e5e7eb; }
.chat-input input { flex: 1; padding: 0.625rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
.conv-item { padding: 0.75rem 1rem; display: grid; grid-template-columns: 1fr auto; gap: 0.25rem; border-bottom: 1px solid #f3f4f6; cursor: pointer; }
.conv-item:hover { background: #f9fafb; }
.conv-item.active { background: #eef2ff; }
.conv-title { font-weight: 600; color: #111827; }
.conv-sub { font-size: 0.8125rem; color: #6b7280; }
.conv-time { font-size: 0.75rem; color: #9ca3af; }
.msg { margin-bottom: 0.5rem; max-width: 72%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; background: #f3f4f6; display: inline-block; }
.msg.me { background: #dbeafe; margin-left: auto; }
@media (max-width: 1024px) { .chat-layout { grid-template-columns: 1fr; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const $convs = document.getElementById('chat-conversations');
    const $msgs = document.getElementById('chat-messages');
    const $header = document.getElementById('chat-header');
    const $form = document.getElementById('chat-form');
    const $input = document.getElementById('chat-text');
    let currentConvId = null; let pollTimer = null;

    async function loadConversations() {
        try {
            const res = await window.axios.get('/api/chat/conversations');
            const data = res.data || {};
            const items = Array.isArray(data.data) ? data.data : (Array.isArray(data) ? data : []);
            renderConversations(items);
            if (items[0]) openConversation(items[0].id, $convs.querySelector('.conv-item'));
        } catch (e) {
            $convs.innerHTML = '<div class="placeholder" style="padding:1rem;">Failed to load.</div>';
        }
    }

    function renderConversations(items) {
        if (!items.length) { $convs.innerHTML = '<div style="padding:1rem;">No conversations</div>'; return; }
        $convs.innerHTML = '';
        items.forEach(c => {
            const div = document.createElement('div');
            div.className = 'conv-item';
            div.dataset.id = c.id;
            const title = c.company_name || c.recruiter_name || 'Conversation';
            const sub = c.job_title ? c.job_title : '';
            const time = c.last_message_at ? new Date(c.last_message_at).toLocaleString() : '';
            div.innerHTML = `<div><div class="conv-title">${title}</div><div class="conv-sub">${sub}</div></div><div class="conv-time">${time}</div>`;
            div.addEventListener('click', () => openConversation(c.id, div));
            $convs.appendChild(div);
        });
    }

    async function openConversation(id, el) {
        $convs.querySelectorAll('.conv-item').forEach(n => n.classList.remove('active'));
        if (el) el.classList.add('active');
        try {
            const res = await window.axios.get(`/api/chat/conversations/${id}`);
            const conv = res.data?.conversation; const messages = res.data?.messages || [];
            $header.textContent = `${conv?.company_name || conv?.recruiter_name || 'Conversation'}${conv?.job_title ? ' • ' + conv.job_title : ''}`;
            $msgs.dataset.conversationId = id;
            $msgs.innerHTML = messages.map(m => `<div class="msg ${m.sender_type==='user' ? 'me' : ''}">${escapeHtml(m.body)}</div>`).join('');
            $msgs.scrollTop = $msgs.scrollHeight;
            currentConvId = id;
            startPolling();
        } catch (e) {
            // no-op
        }
    }

    function escapeHtml(s) { return (s||'').replace(/[&<>"']/g, t => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[t])); }

    $form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = $msgs.dataset.conversationId; const text = ($input.value||'').trim();
        if (!id || !text) return;
        try {
            await window.axios.post(`/api/chat/conversations/${id}/messages`, { body: text });
            $input.value = '';
            await openConversation(id);
        } catch (e) { /* no-op */ }
    });

    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(async () => {
            if (!currentConvId) return; try { await openConversation(currentConvId); } catch (e) {}
        }, 5000);
    }

    loadConversations();
});
</script>
@endsection


