@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa')
@section('page-title', 'Manajemen Mahasiswa')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Mahasiswa (Kiri/Tengah) -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4 flex items-center">
            <span class="mr-2">🎓</span> Daftar Seluruh Mahasiswa
        </h3>

        @if($mahasiswas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xxs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 select-none">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3">Program Studi / Email</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($mahasiswas as $mhs)
                            <tr>
                                <td class="px-4 py-3.5">{{ $mhs->nama_lengkap }}</td>
                                <td class="px-4 py-3.5 text-xs font-semibold text-slate-800">{{ $mhs->nim }}</td>
                                <td class="px-4 py-3.5 text-xs">
                                    <span class="text-slate-900 block text-xxs font-bold px-2 py-0.5 bg-slate-100 border border-slate-200 rounded-full w-max mb-1">{{ $mhs->program_studi }}</span>
                                    <span class="text-slate-400 font-normal">{{ $mhs->user->email }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right flex items-center justify-end space-x-2">
                                    <!-- Edit Trigger Button -->
                                    <button onclick="editStudent({{ json_encode($mhs) }})" 
                                        class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xxs font-bold transition">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa ini? Ini juga akan menghapus user login terkait.');">
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
            <p class="text-sm text-slate-400 text-center py-8">Belum ada data mahasiswa terdaftar.</p>
        @endif
    </div>

    <!-- Form Add/Edit (Kanan) -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <!-- Form Title Header -->
            <h3 id="formTitle" class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4 flex items-center">
                <span class="mr-2">➕</span> Tambah Mahasiswa Baru
            </h3>

            <form id="studentForm" action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="studentName" required placeholder="Contoh: Ririn Anggraini" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">NIM (Nomor Induk)</label>
                    <input type="text" name="nim" id="studentNim" required placeholder="Contoh: 2401092003" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Akun</label>
                    <input type="email" name="email" id="studentEmail" required placeholder="Contoh: ririn@pnp.ac.id" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Program Studi</label>
                    <select name="program_studi" id="studentProdi" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="D3 Teknik Komputer">D3 Teknik Komputer</option>
                        <option value="D3 Manajemen Informatika">D3 Manajemen Informatika</option>
                        <option value="D4 Teknologi Rekayasa Perangkat Lunak">D4 Teknologi Rekayasa Perangkat Lunak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="studentPassword" placeholder="Masukkan password akun" 
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
    function editStudent(student) {
        document.getElementById('formTitle').innerHTML = '<span class="mr-2">✏️</span> Edit Data Mahasiswa';
        document.getElementById('studentForm').action = '/admin/mahasiswa/' + student.id;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('studentName').value = student.nama_lengkap;
        document.getElementById('studentNim').value = student.nim;
        document.getElementById('studentEmail').value = student.user.email;
        document.getElementById('studentProdi').value = student.program_studi;
        
        // Atur input password opsional pada edit
        document.getElementById('studentPassword').required = false;
        document.getElementById('studentPassword').placeholder = "••••••••";
        document.getElementById('passwordHelp').classList.remove('hidden');

        document.getElementById('cancelBtn').classList.remove('hidden');
        document.getElementById('submitBtn').innerHTML = 'Perbarui Data';
        document.getElementById('submitBtn').classList.replace('bg-indigo-600', 'bg-slate-900');
        document.getElementById('submitBtn').classList.replace('hover:bg-indigo-700', 'hover:bg-slate-800');
    }

    // Mereset form kembali ke mode Tambah
    function resetForm() {
        document.getElementById('formTitle').innerHTML = '<span class="mr-2">➕</span> Tambah Mahasiswa Baru';
        document.getElementById('studentForm').action = '{{ route("admin.mahasiswa.store") }}';
        document.getElementById('formMethod').value = 'POST';

        document.getElementById('studentName').value = '';
        document.getElementById('studentNim').value = '';
        document.getElementById('studentEmail').value = '';
        document.getElementById('studentProdi').value = 'D4 Teknologi Rekayasa Perangkat Lunak';
        
        document.getElementById('studentPassword').value = '';
        document.getElementById('studentPassword').required = true;
        document.getElementById('studentPassword').placeholder = "Masukkan password akun";
        document.getElementById('passwordHelp').classList.add('hidden');

        document.getElementById('cancelBtn').classList.add('hidden');
        document.getElementById('submitBtn').innerHTML = 'Simpan Data';
        document.getElementById('submitBtn').classList.replace('bg-slate-900', 'bg-indigo-600');
        document.getElementById('submitBtn').classList.replace('hover:bg-slate-800', 'hover:bg-indigo-700');
    }
</script>
@endsection
