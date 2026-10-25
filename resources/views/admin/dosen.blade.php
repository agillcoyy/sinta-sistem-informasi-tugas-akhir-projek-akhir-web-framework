@extends('layouts.app')

@section('title', 'Manajemen Dosen')
@section('page-title', 'Manajemen Dosen')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Dosen (Kiri/Tengah) -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4 flex items-center">
            <span class="mr-2">👨‍🏫</span> Daftar Seluruh Dosen
        </h3>

        @if($dosens->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xxs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 select-none">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">NIP</th>
                            <th class="px-4 py-3">Keahlian / Email</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($dosens as $dosen)
                            <tr>
                                <td class="px-4 py-3.5">{{ $dosen->nama_lengkap }}</td>
                                <td class="px-4 py-3.5 text-xs font-semibold text-slate-800">{{ $dosen->nip }}</td>
                                <td class="px-4 py-3.5 text-xs">
                                    <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded font-medium inline-block mb-1">{{ $dosen->bidang_keahlian }}</span>
                                    <span class="text-slate-400 font-normal block">{{ $dosen->user->email }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right flex items-center justify-end space-x-2">
                                    <!-- Edit Trigger Button -->
                                    <button onclick="editDosen({{ json_encode($dosen) }})" 
                                        class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xxs font-bold transition">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" onsubmit="return confirm('Hapus dosen ini? Ini juga akan menghapus user login terkait.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded text-xxs font-bold transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-slate-400 text-center py-8">Belum ada data dosen terdaftar.</p>
        @endif
    </div>

    <!-- Form Add/Edit (Kanan) -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <!-- Form Title Header -->
            <h3 id="formTitle" class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4 flex items-center">
                <span class="mr-2">➕</span> Tambah Dosen Baru
            </h3>

            <form id="dosenForm" action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" id="dosenName" required placeholder="Contoh: Dr. Rian Prasetya, M.T." 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">NIP</label>
                    <input type="text" name="nip" id="dosenNip" required placeholder="Contoh: 198501012010121002" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Akun</label>
                    <input type="email" name="email" id="dosenEmail" required placeholder="Contoh: rian@pnp.ac.id" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bidang Keahlian</label>
                    <input type="text" name="bidang_keahlian" id="dosenKeahlian" required placeholder="Contoh: Kecerdasan Buatan (AI) / Web Dev" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="dosenPassword" placeholder="Masukkan password akun" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                    <p id="passwordHelp" class="text-xxs text-slate-400 mt-1 hidden">Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div class="flex space-x-2 pt-2">
                    <button type="submit" id="submitBtn" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                        Simpan Data
                    </button>
                    <button type="button" id="cancelBtn" onclick="resetForm()" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-sm font-semibold transition hidden">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Membuka form dalam mode Edit
    function editDosen(dosen) {
        document.getElementById('formTitle').innerHTML = '<span class="mr-2">✏️</span> Edit Data Dosen';
        document.getElementById('dosenForm').action = '/admin/dosen/' + dosen.id;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('dosenName').value = dosen.nama_lengkap;
        document.getElementById('dosenNip').value = dosen.nip;
        document.getElementById('dosenEmail').value = dosen.user.email;
        document.getElementById('dosenKeahlian').value = dosen.bidang_keahlian;
        
        // Atur input password opsional pada edit
        document.getElementById('dosenPassword').required = false;
        document.getElementById('dosenPassword').placeholder = "••••••••";
        document.getElementById('passwordHelp').classList.remove('hidden');

        document.getElementById('cancelBtn').classList.remove('hidden');
        document.getElementById('submitBtn').innerHTML = 'Perbarui Data';
        document.getElementById('submitBtn').classList.replace('bg-indigo-600', 'bg-slate-900');
        document.getElementById('submitBtn').classList.replace('hover:bg-indigo-700', 'hover:bg-slate-800');
    }

    // Mereset form kembali ke mode Tambah
    function resetForm() {
        document.getElementById('formTitle').innerHTML = '<span class="mr-2">➕</span> Tambah Dosen Baru';
        document.getElementById('dosenForm').action = '{{ route("admin.dosen.store") }}';
        document.getElementById('formMethod').value = 'POST';

        document.getElementById('dosenName').value = '';
        document.getElementById('dosenNip').value = '';
        document.getElementById('dosenEmail').value = '';
        document.getElementById('dosenKeahlian').value = '';
        
        document.getElementById('dosenPassword').value = '';
        document.getElementById('dosenPassword').required = true;
        document.getElementById('dosenPassword').placeholder = "Masukkan password akun";
        document.getElementById('passwordHelp').classList.add('hidden');

        document.getElementById('cancelBtn').classList.add('hidden');
        document.getElementById('submitBtn').innerHTML = 'Simpan Data';
        document.getElementById('submitBtn').classList.replace('bg-slate-900', 'bg-indigo-600');
        document.getElementById('submitBtn').classList.replace('hover:bg-slate-800', 'hover:bg-indigo-700');
    }
</script>
@endsection
