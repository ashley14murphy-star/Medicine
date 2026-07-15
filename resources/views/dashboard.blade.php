<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Medical Inventory Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Alpine.js state para sa modal control, search, at category filtering -->
    <div class="py-12" x-data="{ 
        openModal: @json((bool)$editingMedicine),
        searchQuery: '',
        selectedCategory: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success Notification Banner -->
            @if(session('success'))
                <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 font-medium shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Category Summary Cards Section -->
            <div class="flex flex-wrap gap-6 mb-6">
                <!-- Tablet Card -->
                <div @click="selectedCategory = (selectedCategory === 'Tablet' ? '' : 'Tablet')" 
                     :class="selectedCategory === 'Tablet' ? 'ring-2 ring-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : ''"
                     class="w-full sm:w-64 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-between cursor-pointer hover:shadow transition-all duration-200">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Tablets</span>
                        <div class="text-4xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $categoryCounts['Tablet'] ?? 0 }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Products in inventory
                    </div>
                </div>

                <!-- Capsule Card -->
                <div @click="selectedCategory = (selectedCategory === 'Capsule' ? '' : 'Capsule')"
                     :class="selectedCategory === 'Capsule' ? 'ring-2 ring-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : ''"
                     class="w-full sm:w-64 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-between cursor-pointer hover:shadow transition-all duration-200">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Capsules</span>
                        <div class="text-4xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $categoryCounts['Capsule'] ?? 0 }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Products in inventory
                    </div>
                </div>

                <!-- Syrup Card -->
                <div @click="selectedCategory = (selectedCategory === 'Syrup' ? '' : 'Syrup')"
                     :class="selectedCategory === 'Syrup' ? 'ring-2 ring-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : ''"
                     class="w-full sm:w-64 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-between cursor-pointer hover:shadow transition-all duration-200">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Syrups</span>
                        <div class="text-4xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $categoryCounts['Syrup'] ?? 0 }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Products in inventory
                    </div>
                </div>

                <!-- Ointment Card -->
                <div @click="selectedCategory = (selectedCategory === 'Ointment' ? '' : 'Ointment')"
                     :class="selectedCategory === 'Ointment' ? 'ring-2 ring-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : ''"
                     class="w-full sm:w-64 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-between cursor-pointer hover:shadow transition-all duration-200">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Ointments</span>
                        <div class="text-4xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $categoryCounts['Ointment'] ?? 0 }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Products in inventory
                    </div>
                </div>

                <!-- Injection Card -->
                <div @click="selectedCategory = (selectedCategory === 'Injection' ? '' : 'Injection')"
                     :class="selectedCategory === 'Injection' ? 'ring-2 ring-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : ''"
                     class="w-full sm:w-64 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-between cursor-pointer hover:shadow transition-all duration-200">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">Injections</span>
                        <div class="text-4xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $categoryCounts['Injection'] ?? 0 }}
                        </div>
                    </div>
                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Products in inventory
                    </div>
                </div>
            </div>

            <!-- Main Inventory Table Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    
                    <!-- ONE-LINE ROW LAYOUT (Search -> Dropdown -> Button) -->
                    <div class="flex flex-row items-center justify-between gap-4 mb-6">
                        <!-- Pamagat sa Kaliwa -->
                        <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            {{ __('All Products') }}
                        </h3>
                        
                        <!-- Pahalang na Grupo sa Kanan (Laging magkakatabi) -->
                        <div class="flex flex-row items-center gap-3">
                            <!-- Search Input Box -->
                            <div class="w-48 sm:w-64">
                                <input 
                                    type="text" 
                                    x-model="searchQuery" 
                                    placeholder="Search inventory..." 
                                    class="px-4 py-2 text-sm w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 placeholder-gray-400"
                                />
                            </div>

                            <!-- Category Dropdown Filter -->
                            <div class="w-36 sm:w-44">
                                <select 
                                    x-model="selectedCategory" 
                                    class="px-7 py-2 text-sm w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 text-gray-600 dark:text-gray-400"
                                >
                                    <option value="">All Categories</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Injection">Injection</option>
                                </select>
                            </div>

                            <!-- Add Product Button (Blue layout) -->
                            <button @click="openModal = true" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                + Add Product
                            </button>
                        </div>
                    </div>
                    
                    @if($medicines->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ __('No medical records found.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expiry Date</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($medicines as $medicine)
                                        <tr 
                                            x-show="(searchQuery === '' || '{{ strtolower($medicine->name) }}'.includes(searchQuery.toLowerCase())) && (selectedCategory === '' || '{{ $medicine->category ?? '' }}' === selectedCategory)"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $medicine->expiry_date->isPast() ? 'bg-red-50/50 dark:bg-red-950/20' : '' }}"
                                        >
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                                {{ $medicine->name }}
                                                @if($medicine->expiry_date->isPast())
                                                    <span class="ml-2 px-2.5 py-0.5 text-xs font-semibold text-red-700 bg-red-100 rounded-full dark:bg-red-900/40 dark:text-red-300">Expired</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                    {{ $medicine->category ?? 'Uncategorized' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                                {{ $medicine->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                {{ $medicine->expiry_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                                <a href="{{ route('dashboard', ['edit' => $medicine->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-black text-xs font-bold rounded shadow-sm transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow-sm transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- CUSTOM ALPINE.JS MODAL -->
            <div x-show="openModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 aria-labelledby="modal-title" 
                 role="dialog" 
                 aria-modal="true">
                
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 border border-gray-200 dark:border-gray-700">
                        
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                {{ $editingMedicine ? __('Edit Medical Detail') : __('Add New Medical Detail') }}
                            </h3>
                            <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 font-bold text-xl">&times;</button>
                        </div>

                        <form action="{{ $editingMedicine ? route('medicines.update', $editingMedicine->id) : route('medicines.store') }}" method="POST" class="space-y-4">
                            @csrf
                            @if($editingMedicine)
                                @method('PUT')
                            @endif

                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Medicine Name / Detail</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $editingMedicine->name ?? '') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category" id="category" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                    <option value="" disabled {{ !isset($editingMedicine) ? 'selected' : '' }}>Select Category</option>
                                    <option value="Tablet" {{ old('category', $editingMedicine->category ?? '') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Capsule" {{ old('category', $editingMedicine->category ?? '') === 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="Syrup" {{ old('category', $editingMedicine->category ?? '') === 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="Ointment" {{ old('category', $editingMedicine->category ?? '') === 'Ointment' ? 'selected' : '' }}>Ointment</option>
                                    <option value="Injection" {{ old('category', $editingMedicine->category ?? '') === 'Injection' ? 'selected' : '' }}>Injection</option>
                                </select>
                                @error('category') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $editingMedicine->quantity ?? '') }}" min="0" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                @error('quantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', isset($editingMedicine) ? $editingMedicine->expiry_date->format('Y-m-d') : '') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                @error('expiry_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                                <a href="{{ route('dashboard') }}" @click="openModal = false" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-md transition">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-black text-sm font-semibold rounded-md shadow transition">
                                    {{ $editingMedicine ? __('Update') : __('Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>