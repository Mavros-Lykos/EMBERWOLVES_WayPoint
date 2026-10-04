<!-- SweetAlert2 for Toast Notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-toast {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        border-radius: 8px !important;
    }
</style>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session("success") }}'
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session("error") }}'
        });
    @endif

    @if(session('warning'))
        Toast.fire({
            icon: 'warning',
            title: '{{ session("warning") }}'
        });
    @endif
</script>

<!-- Global Notification Bell System (AlpineJS powered) -->
<style>
    .notif-bell-container {
        position: relative;
        display: inline-block;
        margin-right: 16px;
    }
    .notif-bell-btn {
        background: transparent;
        border: none;
        color: inherit;
        cursor: pointer;
        position: relative;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .notif-bell-btn:hover {
        background: rgba(148, 163, 184, 0.2);
    }
    .notif-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        min-width: 16px;
        height: 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        box-shadow: 0 0 0 2px var(--surf, #fff);
    }
    
    .notif-panel-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.3);
        z-index: 100;
        backdrop-filter: blur(2px);
    }
    .notif-panel {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 360px;
        max-width: 100vw;
        background: var(--surf-c, #FAFAFA);
        z-index: 101;
        box-shadow: -8px 0 32px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateX(100%);
        color: var(--on-surf, #1B1B1F);
    }
    .notif-panel.open {
        transform: translateX(0);
    }
    
    .notif-header {
        padding: 20px;
        border-bottom: 1px solid var(--outline, rgba(116,119,127,0.2));
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--surf, #fff);
    }
    .notif-title {
        font-size: 18px;
        font-weight: 600;
    }
    .notif-close {
        background: none;
        border: none;
        cursor: pointer;
        color: #74777F;
        display: flex;
        align-items: center;
    }
    .notif-close:hover { color: inherit; }
    
    .notif-list {
        flex: 1;
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .notif-item {
        padding: 16px 20px;
        border-bottom: 1px solid var(--outline, rgba(116,119,127,0.1));
        display: flex;
        gap: 12px;
        transition: background 0.2s;
    }
    .notif-item:hover {
        background: var(--surf, #F3F3F6);
    }
    .notif-item.unread {
        background: rgba(59,130,246,0.05);
    }
    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-success { background: rgba(34,197,94,0.1); color: #22c55e; }
    .icon-warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .icon-error { background: rgba(239,68,68,0.1); color: #ef4444; }
    .icon-info { background: rgba(59,130,246,0.1); color: #3b82f6; }
    
    .notif-content { flex: 1; }
    .notif-item-title { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
    .notif-item-msg { font-size: 13px; color: #74777F; margin-bottom: 6px; line-height: 1.4; }
    .notif-item-time { font-size: 11px; color: #94a3b8; }
    
    .notif-empty {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
</style>

<div x-data="notificationSystem()" x-init="init()" class="notif-bell-container" id="global-notifications">
    <!-- Bell Button -->
    <button class="notif-bell-btn" @click="togglePanel()" aria-label="Notifications">
        <span class="material-symbols-outlined">notifications</span>
        <span class="notif-badge" x-show="unreadCount > 0" x-text="unreadCount" x-cloak></span>
    </button>
    
    <!-- Slide Panel -->
    <template x-teleport="body">
        <div>
            <div class="notif-panel-overlay" x-show="panelOpen" x-transition.opacity @click="togglePanel()" x-cloak style="display: none;"></div>
            <div class="notif-panel" :class="{ 'open': panelOpen }" x-cloak>
                <div class="notif-header">
                    <span class="notif-title">Notifications</span>
                    <div style="display:flex; gap:12px; align-items:center;">
                        <button x-show="unreadCount > 0" @click="markAllRead()" style="background:none; border:none; color:var(--primary, #3b82f6); font-size:13px; cursor:pointer; font-weight:500;">Mark all read</button>
                        <button class="notif-close" @click="togglePanel()"><span class="material-symbols-outlined">close</span></button>
                    </div>
                </div>
                
                <ul class="notif-list">
                    <template x-if="notifications.length === 0">
                        <div class="notif-empty">
                            <span class="material-symbols-outlined" style="font-size: 48px; opacity: 0.5;">notifications_paused</span>
                            <span style="font-size: 15px; font-weight:500;">All caught up!</span>
                            <span style="font-size: 13px;">You have no new notifications.</span>
                        </div>
                    </template>
                    <template x-for="notif in notifications" :key="notif.id">
                        <li class="notif-item" :class="{ 'unread': !notif.read_at }">
                            <div class="notif-icon" :class="'icon-' + (notif.data.type || 'info')">
                                <span class="material-symbols-outlined" x-text="getIcon(notif.data.type)"></span>
                            </div>
                            <div class="notif-content">
                                <div class="notif-item-title" x-text="notif.data.title"></div>
                                <div class="notif-item-msg" x-text="notif.data.message"></div>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <span class="notif-item-time" x-text="formatTime(notif.created_at)"></span>
                                    <template x-if="!notif.read_at">
                                        <button @click="markRead(notif.id)" style="background:none; border:none; color:var(--primary, #3b82f6); font-size:11px; cursor:pointer; font-weight:600;">Mark read</button>
                                    </template>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationSystem', () => ({
            panelOpen: false,
            unreadCount: 0,
            notifications: [],
            
            init() {
                this.fetchNotifications();
                // Optionally poll every 30 seconds for new notifications
                setInterval(() => this.fetchNotifications(), 30000);
            },
            
            togglePanel() {
                this.panelOpen = !this.panelOpen;
                if (this.panelOpen) {
                    this.fetchNotifications();
                }
            },
            
            fetchNotifications() {
                fetch('{{ route("notifications.index") }}')
                    .then(r => r.json())
                    .then(data => {
                        this.unreadCount = data.unread_count || 0;
                        this.notifications = data.notifications || [];
                    })
                    .catch(e => console.error(e));
            },
            
            markRead(id) {
                fetch('{{ route("notifications.read") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                }).then(() => {
                    this.fetchNotifications();
                });
            },
            
            markAllRead() {
                fetch('{{ route("notifications.read") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({})
                }).then(() => {
                    this.fetchNotifications();
                });
            },
            
            getIcon(type) {
                switch(type) {
                    case 'success': return 'check_circle';
                    case 'warning': return 'warning';
                    case 'error': return 'error';
                    default: return 'info';
                }
            },
            
            formatTime(dateStr) {
                const date = new Date(dateStr);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                
                if (diffMins < 1) return 'Just now';
                if (diffMins < 60) return diffMins + 'm ago';
                
                const diffHrs = Math.floor(diffMins / 60);
                if (diffHrs < 24) return diffHrs + 'h ago';
                
                return date.toLocaleDateString();
            }
        }));
    });
</script>
