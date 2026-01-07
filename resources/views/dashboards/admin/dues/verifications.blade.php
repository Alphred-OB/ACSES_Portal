<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <header class="mb-10">
            <h1 class="text-3xl font-bold text-slate-900">Pending Manual Verifications</h1>
            <p class="mt-2 text-sm text-slate-500">Review receipts submitted by students for manual bank or Momo transfers.</p>
        </header>

        @if (session('status'))
            <div class="mb-8 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="space-y-6">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Due Details</th>
                            <th class="px-6 py-4">Submission Date</th>
                            <th class="px-6 py-4">Reference</th>
                            <th class="px-6 py-4 text-center">Receipt</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendingDues as $due)
                        <tr class="transition hover:bg-slate-50/50">
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-900">{{ $due->student->fullname }}</div>
                                <div class="text-[10px] text-slate-400">ID: {{ $due->student->index_number }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-slate-700 font-medium">{{ $due->description }}</div>
                                <div class="text-[11px] font-bold text-emerald-600">GHS {{ number_format($due->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-5 text-slate-500">
                                {{ $due->updated_at->format('M j, Y') }}
                                <div class="text-[10px]">{{ $due->updated_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <code class="text-[10px] bg-slate-100 px-2 py-1 rounded text-slate-600">{{ $due->payment_reference ?? 'N/A' }}</code>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($due->receipt_path)
                                    <button 
                                        type="button"
                                        @click="$dispatch('open-modal', 'view-receipt-{{ $due->due_id }}')"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
                                    >
                                        <i class="ri-image-line text-xl"></i>
                                    </button>
                                    
                                    <!-- Modal for Receipt -->
                                    <x-modal name="view-receipt-{{ $due->due_id }}" focusable>
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h2 class="text-lg font-bold text-slate-900 leading-tight">Receipt Verification</h2>
                                                <button @click="$dispatch('close')" class="text-slate-400 hover:text-slate-600"><i class="ri-close-line text-2xl"></i></button>
                                            </div>
                                            <div class="rounded-2xl overflow-hidden border border-slate-200">
                                                <img src="{{ asset('storage/' . $due->receipt_path) }}" alt="Receipt" class="w-full max-h-[70vh] object-contain bg-slate-100">
                                            </div>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <button @click="$dispatch('close')" class="px-6 py-2 rounded-xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition">Close View</button>
                                            </div>
                                        </div>
                                    </x-modal>
                                @else
                                    <span class="text-[10px] text-slate-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.dues.verifications.approve', $due) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition" onclick="return confirm('Confirm this payment as valid?')">
                                            Approve
                                        </button>
                                    </form>
                                    <button 
                                        type="button" 
                                        @click="$dispatch('open-modal', 'reject-payment-{{ $due->due_id }}')"
                                        class="rounded-xl bg-rose-50 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-100 transition"
                                    >
                                        Reject
                                    </button>

                                    <!-- Reject Reason Modal -->
                                    <x-modal name="reject-payment-{{ $due->due_id }}" focusable>
                                        <form action="{{ route('admin.dues.verifications.reject', $due) }}" method="POST" class="p-6">
                                            @csrf
                                            <h2 class="text-lg font-bold text-slate-900">Reject Payment Record</h2>
                                            <p class="mt-1 text-sm text-slate-500">Provide a reason for the student to fix (e.g. "Blurred image" or "Reference mismatch").</p>
                                            
                                            <textarea name="rejection_reason" rows="3" class="mt-4 w-full rounded-2xl border-slate-200 focus:border-rose-500 focus:ring-rose-500" placeholder="Type reason here..." required></textarea>
                                            
                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button" @click="$dispatch('close')" class="px-6 py-2 rounded-xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition">Cancel</button>
                                                <button type="submit" class="px-6 py-2 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 transition shadow-lg shadow-rose-200">Submit Rejection</button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <i class="ri-verified-badge-line text-4xl"></i>
                                    </div>
                                    <p class="text-slate-600 font-bold">Clear queue!</p>
                                    <p class="text-xs text-slate-400">There are no pending manual payments requiring verification.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $pendingDues->links() }}
            </div>
        </section>
    </div>
</x-layouts.admin>
