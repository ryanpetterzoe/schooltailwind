<?php $adminPageTitle = 'Pesan Masuk'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-envelope text-blue-600"></i>Pesan Masuk</h5>
        <span class="text-sm text-slate-500 dark:text-slate-400"><?= $unreadCount ?? 0 ?> pesan belum dibaca</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Subjek</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($contacts)): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada pesan masuk</td></tr>
                <?php else: ?>
                <?php foreach ($contacts as $msg): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors <?= !$msg['is_read'] ? 'font-semibold' : '' ?>">
                    <td class="px-4 py-3">
                        <div class="text-slate-800 dark:text-white"><?= htmlspecialchars($msg['name']) ?></div>
                        <?php if (!empty($msg['phone'])): ?><small class="text-slate-400 dark:text-slate-500"><?= htmlspecialchars($msg['phone']) ?></small><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($msg['email']) ?></td>
                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300 max-w-[200px] truncate"><?= htmlspecialchars($msg['subject'] ?? '') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $msg['is_read'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' ?>">
                            <?= $msg['is_read'] ? 'Dibaca' : 'Baru' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <button class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors" onclick="viewMessage(<?= htmlspecialchars(json_encode($msg)) ?>)" title="Lihat"><i class="fas fa-eye text-xs"></i></button>
                            <a href="<?= APP_URL ?>/admin/kontak/hapus/<?= $msg['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus pesan ini?"><i class="fas fa-trash text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- View Message Modal -->
<div id="msgModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white">Detail Pesan</h5>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-4 sm:p-6 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div class="text-sm text-slate-500 dark:text-slate-400">Nama</div><div class="sm:col-span-2 text-sm text-slate-800 dark:text-white" id="msgName"></div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Email</div><div class="sm:col-span-2 text-sm text-slate-800 dark:text-white" id="msgEmail"></div>
                <div class="text-sm text-slate-500 dark:text-slate-400">HP</div><div class="sm:col-span-2 text-sm text-slate-800 dark:text-white" id="msgPhone"></div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Subjek</div><div class="sm:col-span-2 text-sm text-slate-800 dark:text-white" id="msgSubject"></div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Tanggal</div><div class="sm:col-span-2 text-sm text-slate-800 dark:text-white" id="msgDate"></div>
            </div>
            <hr class="border-slate-200 dark:border-slate-700">
            <h6 class="text-sm font-medium text-slate-500 dark:text-slate-400">Pesan:</h6>
            <div id="msgContent" class="text-sm text-slate-800 dark:text-white leading-relaxed bg-slate-50 dark:bg-slate-900 p-4 rounded-lg"></div>
        </div>
        <div class="flex items-center justify-end gap-2 p-4 sm:p-6 border-t border-slate-100 dark:border-slate-700">
            <a id="msgReply" href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-reply mr-1"></i>Balas via Email</a>
            <button onclick="closeModal()" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<script>
function viewMessage(msg) {
    document.getElementById('msgName').textContent = msg.name || '-';
    document.getElementById('msgEmail').textContent = msg.email || '-';
    document.getElementById('msgPhone').textContent = msg.phone || '-';
    document.getElementById('msgSubject').textContent = msg.subject || '-';
    document.getElementById('msgDate').textContent = msg.created_at || '-';
    document.getElementById('msgContent').textContent = msg.message || '-';
    document.getElementById('msgReply').href = 'mailto:' + (msg.email || '') + '?subject=Re: ' + encodeURIComponent(msg.subject || '');
    if (!msg.is_read) {
        fetch('<?= APP_URL ?>/admin/kontak/baca/' + msg.id);
    }
    var modal = document.getElementById('msgModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal() {
    var modal = document.getElementById('msgModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
