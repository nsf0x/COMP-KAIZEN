<div class="space-y-4">
    <dl class="grid gap-4 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-gray-500">Nama</dt>
            <dd class="mt-1 text-sm text-gray-950">{{ $record->name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
            <dd class="mt-1 text-sm text-gray-950">{{ $record->phone }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="mt-1 text-sm text-gray-950">{{ $record->email ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Waktu dikirim</dt>
            <dd class="mt-1 text-sm text-gray-950">{{ $record->created_at->format('d M Y H:i') }}</dd>
        </div>
    </dl>

    <div>
        <h3 class="text-sm font-medium text-gray-500">Isi pesan</h3>
        <p class="mt-1 whitespace-pre-line text-sm text-gray-950">{{ $record->message }}</p>
    </div>
</div>