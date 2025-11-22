<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>blanjapoin.id - Merchant</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Font optimization for Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
        }
    </style>

    
</head>

<body class="min-h-screen bg-white font-poppins">

    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

    <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-6">
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>

      <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
      @if(Auth::check() && Auth::user()->can_approve == 1) 
        <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
        <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>       @endif
      </div>

       <div class="relative">
        <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 md:h-4 md:w-4 opacity-95">
          <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
         </svg>
         <span class="tracking-tight">{{ Auth::user()->username }}</span>
         <svg id="userDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 md:h-3.5 md:w-3.5 opacity-95 transition-transform duration-300">
          <path d="M7 10l5 5 5-5z"/>
         </svg>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300 ease-out z-50 backdrop-blur-sm">
         <div class="py-1">
          <form method="POST" action="{{ route('logout') }}">
           @csrf
           <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
             <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
            </svg>
            <span>Logout</span>
           </button>
          </form>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </nav>

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <div class="mb-6 -mx-4 sm:mx-0 overflow-x-auto">
            <div class="flex space-x-3 px-4 sm:px-0 min-w-max">
                <button onclick="switchTab('all')" id="tab-all" class="shrink-0 px-6 py-2 rounded-full bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium shadow-lg">Merchant</button>
                <button onclick="switchTab('keyword')" id="tab-keyword" class="shrink-0 px-6 py-2 rounded-full border border-orange-400 text-gray-700 hover:bg-orange-50 transition-colors">Keyword</button>
            </div>
        </div>

        <script>
            // Dropdown Toggle Functions //

            function toggleDateFilter(id) {
                const dropdown = document.getElementById(id || 'dateFilterDropdown');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            // Function to toggle User dropdown
            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                const arrow = document.getElementById('userDropdownArrow');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('opacity-0')) {
                    // Show dropdown
                    dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                    dropdown.classList.add('opacity-100', 'visible', 'scale-100');
                    if (arrow) arrow.style.transform = 'rotate(180deg)';
                } else {
                    // Hide dropdown
                    dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                    dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                }
            }

            // New function to toggle Kategori dropdown
            function toggleKategoriDropdown() {
                const dropdown = document.getElementById('kategoriDropdown');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            // Function to close all dropdowns
            function closeAllDropdowns() {
                const dropdowns = document.querySelectorAll('[id$="Dropdown"], [id^="kategoriDropdown"]');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-6px)';
                        setTimeout(() => {
                            dropdown.classList.add('hidden');
                            dropdown.style.transition = '';
                            dropdown.style.opacity = '';
                            dropdown.style.transform = '';
                        }, 200);
                    }
                });
            }

            ////////////////////////////////////////////////////////////////////
            // File Upload Functions
            ////////////////////////////////////////////////////////////////////

            // Function to handle file upload
            function handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Check if file is an image
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                        return;
                    }
                    
                    // Here you would typically send the file to your server
                    // For now, we'll just show a success message
                    alert(`File selected: ${file.name}\nSize: ${Math.round(file.size/1024)} KB\nType: ${file.type}`);
                    
                    // In a real application, you would do something like:
                    // const formData = new FormData();
                    // formData.append('image', file);
                    // fetch('/upload-image', { method: 'POST', body: formData })
                    //    .then(response => response.json())
                    //    .then(data => console.log('Success:', data))
                    //    .catch(error => console.error('Error:', error));
                }
            }

            // Functions for Merchant section
            function toggleKategoriDropdownMerchant() {
                const dropdown = document.getElementById('kategoriDropdownMerchant');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            function handleFileUploadMerchant(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                        return;
                    }
                    alert(`File selected: ${file.name}\nSize: ${Math.round(file.size/1024)} KB\nType: ${file.type}`);
                }
            }

            // Functions for Telkom section
            function toggleKategoriDropdownTelkom() {
                const dropdown = document.getElementById('kategoriDropdownTelkom');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            function handleFileUploadTelkom(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                        return;
                    }
                    alert(`File selected: ${file.name}\nSize: ${Math.round(file.size/1024)} KB\nType: ${file.type}`);
                }
            }

            // Functions for All section - Merchant
            function toggleKategoriDropdownAll1() {
                const dropdown = document.getElementById('kategoriDropdownAll1');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            function handleFileUploadAll1(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                        return;
                    }
                    alert(`File selected: ${file.name}\nSize: ${Math.round(file.size/1024)} KB\nType: ${file.type}`);
                }
            }

            // Functions for All section - Telkom
            function toggleKategoriDropdownAll3() {
                const dropdown = document.getElementById('kategoriDropdownAll3');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            function handleFileUploadAll3(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                        return;
                    }
                    alert(`File selected: ${file.name}\nSize: ${Math.round(file.size/1024)} KB\nType: ${file.type}`);
                }
            }

            ////////////////////////////////////////////////////////////////////
            // Image Preview Functions
            ////////////////////////////////////////////////////////////////////

            // Function to preview merchant logo
            function previewMerchantLogo(imageUrl, fileName) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
                modal.innerHTML = `
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">${fileName}</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <img src="${imageUrl}" alt="${fileName}" class="w-full h-auto rounded-lg">
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            // Function to preview keyword image
            function previewKeywordImage(imageUrl, fileName) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
                modal.innerHTML = `
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">${fileName}</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <img src="${imageUrl}" alt="${fileName}" class="w-full h-auto rounded-lg">
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            // Function to preview single image
            function previewImage(event, previewId) {
                const file = event.target.files[0];
                const preview = document.getElementById(previewId);
                const fileNameSpan = document.getElementById('logoFileName');
                
                if (file) {
                    fileNameSpan.textContent = file.name;
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = '<div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>';
                        alert('Please select an image file (JPEG, PNG, GIF, etc.)');
                    }
                } else {
                    preview.innerHTML = '<div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>';
                    fileNameSpan.textContent = 'No file chosen';
                }
            }

            // Function to preview multiple images
            function previewImages(event, previewId) {
                const files = event.target.files;
                const preview = document.getElementById(previewId);
                const countSpan = document.getElementById('imagesCount');
                
                preview.innerHTML = '';
                
                if (files.length > 0) {
                    countSpan.textContent = `${files.length} file(s) selected`;
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgContainer = document.createElement('div');
                                imgContainer.className = 'w-16 h-16 relative';
                                imgContainer.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-full object-cover rounded-xl">
                                    <button type="button" onclick="removeImage(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">×</button>
                                `;
                                preview.appendChild(imgContainer);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                } else {
                    countSpan.textContent = 'No files chosen';
                }
            }

            // Function to remove image from preview
            function removeImage(button) {
                const imgContainer = button.parentElement;
                imgContainer.remove();
                
                // Update count
                const preview = document.getElementById('imagesPreview');
                const countSpan = document.getElementById('imagesCount');
                const imageCount = preview.children.length;
                countSpan.textContent = imageCount > 0 ? `${imageCount} file(s) selected` : 'No files chosen';
            }

            ////////////////////////////////////////////////////////////////////
            // Form Handling Functions
            ////////////////////////////////////////////////////////////////////

            // Function to handle form submission
            function handleMerchantFormSubmit(event) {
                event.preventDefault();
                
                // Get form data
                const form = document.getElementById('merchantUploadFormElement');
                const formData = new FormData(form);
                
                // In a real application, you would send this data to your server
                
                // Reset form and hide it
                form.reset();
                document.getElementById('logoPreview').innerHTML = '<div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>';
                document.getElementById('imagesPreview').innerHTML = '';
                document.getElementById('logoFileName').textContent = 'No file chosen';
                document.getElementById('imagesCount').textContent = 'No files chosen';
                toggleMerchantUploadModal();
            }

            // Add event listener for form submission
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('merchantUploadFormElement');
                if (form) {
                    form.addEventListener('submit', handleMerchantFormSubmit);
                }
            });

            ////////////////////////////////////////////////////////////////////
            // Keyword Status Filter Functions
            ////////////////////////////////////////////////////////////////////

            // Store current selected status for keyword
            let selectedKeywordStatus = 'all';

            function toggleStatusDropdownKeyword() {
                const dropdown = document.getElementById('statusDropdownKeyword');
                if (!dropdown) return;
                
                if (dropdown.classList.contains('hidden')) {
                    // Close any other open dropdowns first
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    requestAnimationFrame(() => {
                        dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
                        dropdown.style.opacity = '1';
                        dropdown.style.transform = 'translateY(0)';
                    });
                } else {
                    dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.style.transition = '';
                        dropdown.style.opacity = '';
                        dropdown.style.transform = '';
                    }, 200);
                }
            }

            function filterKeywordByStatus(status) {
                // Close the dropdown after selection
                closeAllDropdowns();
                
                // Update the button text to show the selected status
                const button = document.getElementById('statusBtnKeyword');
                if (button) {
                    let label = 'Status';
                    let buttonClasses = 'flex items-center px-4 py-2 text-sm rounded-full border transition-all duration-300';
                    
                    // Toggle: clicking the same status again resets to All
                    if (selectedKeywordStatus === status) {
                        status = 'all';
                    }
                    selectedKeywordStatus = status;

                    if (status === 'all') {
                        label = 'Status';
                        buttonClasses += ' border-gray-300 text-gray-700 hover:bg-gray-50';
                    } else if (status === 'pending') {
                        label = 'Pending';
                        buttonClasses += ' border-yellow-300 text-yellow-800 bg-gradient-to-r from-yellow-100 to-amber-100 hover:from-yellow-200 hover:to-amber-200';
                    } else if (status === 'reject') {
                        label = 'Rejected';
                        buttonClasses += ' border-red-300 text-red-800 bg-gradient-to-r from-red-100 to-rose-100 hover:from-red-200 hover:to-rose-200';
                    } else if (status === 'approve') {
                        label = 'Approved';
                        buttonClasses += ' border-green-300 text-green-800 bg-gradient-to-r from-green-100 to-emerald-100 hover:from-green-200 hover:to-emerald-200';
                    }
                    
                    button.className = buttonClasses;
                    button.innerHTML = `<i class="fas fa-filter mr-2"></i>${label}<i class=\"fas fa-chevron-down ml-2 text-xs\"></i>`;
                }
                
                // Filter the keyword table rows based on status
                const keywordTableBody = document.getElementById('keyword-table-body');
                const keywordCardsContainer = document.getElementById('keyword-cards-container');
                
                if (keywordTableBody) {
                    const rows = keywordTableBody.querySelectorAll('tr.keyword-row');
                    rows.forEach(row => {
                        // Get all td cells and find the Status column (should be around index 9-10 depending on approve column)
                        const cells = row.querySelectorAll('td');
                        let statusText = '';
                        
                        // Look for the status cell by finding the one with status badge
                        cells.forEach(cell => {
                            const statusSpan = cell.querySelector('span[class*="rounded-full"]');
                            if (statusSpan && (statusSpan.textContent.includes('approve') || statusSpan.textContent.includes('pending') || statusSpan.textContent.includes('reject'))) {
                                statusText = statusSpan.textContent.trim().toLowerCase();
                            }
                        });
                        
                        if (status === 'all' || statusText === status) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
                
                if (keywordCardsContainer) {
                    const cards = keywordCardsContainer.querySelectorAll('.keyword-row');
                    cards.forEach(card => {
                        // Find the status span in the card
                        const statusSpan = card.querySelector('span[class*="rounded-full"]');
                        if (statusSpan) {
                            const statusText = statusSpan.textContent.trim().toLowerCase();
                            if (status === 'all' || statusText === status) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                }
            }

            ////////////////////////////////////////////////////////////////////
            // Event Listeners
            ////////////////////////////////////////////////////////////////////

            // Click outside to close dropdowns
            document.addEventListener('click', function(event) {
                // Handle user dropdown
                const userDropdownBtn = document.getElementById('userDropdownBtn');
                const userDropdown = document.getElementById('userDropdown');
                
                if (userDropdownBtn && userDropdown && 
                    !userDropdownBtn.contains(event.target) && 
                    !userDropdown.contains(event.target) &&
                    !userDropdown.classList.contains('opacity-0')) {
                    toggleUserDropdown();
                }
                
                // Handle main section dropdown
                const kategoriBtn = document.getElementById('kategoriBtn');
                const kategoriDropdown = document.getElementById('kategoriDropdown');
                
                if (kategoriBtn && kategoriDropdown && 
                    !kategoriBtn.contains(event.target) && 
                    !kategoriDropdown.contains(event.target) &&
                    !kategoriDropdown.classList.contains('hidden')) {
                    toggleKategoriDropdown();
                }
                
                // Handle merchant section dropdown
                const kategoriBtnMerchant = document.getElementById('kategoriBtnMerchant');
                const kategoriDropdownMerchant = document.getElementById('kategoriDropdownMerchant');
                
                if (kategoriBtnMerchant && kategoriDropdownMerchant && 
                    !kategoriBtnMerchant.contains(event.target) && 
                    !kategoriDropdownMerchant.contains(event.target) &&
                    !kategoriDropdownMerchant.classList.contains('hidden')) {
                    toggleKategoriDropdownMerchant();
                }
                
                // Handle All section dropdowns
                const kategoriBtnAll1 = document.getElementById('kategoriBtnAll1');
                const kategoriDropdownAll1 = document.getElementById('kategoriDropdownAll1');
                
                if (kategoriBtnAll1 && kategoriDropdownAll1 && 
                    !kategoriBtnAll1.contains(event.target) && 
                    !kategoriDropdownAll1.contains(event.target) &&
                    !kategoriDropdownAll1.classList.contains('hidden')) {
                    toggleKategoriDropdownAll1();
                }
                
                const kategoriBtnAll2 = document.getElementById('kategoriBtnAll2');
                const kategoriDropdownAll2 = document.getElementById('kategoriDropdownAll2');
                
                if (kategoriBtnAll2 && kategoriDropdownAll2 && 
                    !kategoriBtnAll2.contains(event.target) && 
                    !kategoriDropdownAll2.contains(event.target) &&
                    !kategoriDropdownAll2.classList.contains('hidden')) {
                    toggleKategoriDropdownAll2();
                }
                
                const kategoriBtnAll3 = document.getElementById('kategoriBtnAll3');
                const kategoriDropdownAll3 = document.getElementById('kategoriDropdownAll3');
                
                if (kategoriBtnAll3 && kategoriDropdownAll3 && 
                    !kategoriBtnAll3.contains(event.target) && 
                    !kategoriDropdownAll3.contains(event.target) &&
                    !kategoriDropdownAll3.classList.contains('hidden')) {
                    toggleKategoriDropdownAll3();
                }
                
                // Handle status dropdown for keyword section
                const statusBtnKeyword = document.getElementById('statusBtnKeyword');
                const statusDropdownKeyword = document.getElementById('statusDropdownKeyword');
                
                if (statusBtnKeyword && statusDropdownKeyword && 
                    !statusBtnKeyword.contains(event.target) && 
                    !statusDropdownKeyword.contains(event.target) &&
                    !statusDropdownKeyword.classList.contains('hidden')) {
                    toggleStatusDropdownKeyword();
                }
                
                // Also close date filters if clicking outside
                const dateFilterBtns = document.querySelectorAll('[onclick*="toggleDateFilter"]');
                const dateFilterDropdowns = document.querySelectorAll('[id^="dateFilter"]');
                
                let clickedInsideDateFilter = false;
                dateFilterBtns.forEach(btn => {
                    if (btn.contains(event.target)) clickedInsideDateFilter = true;
                });
                dateFilterDropdowns.forEach(dropdown => {
                    if (dropdown.contains(event.target)) clickedInsideDateFilter = true;
                });
                
                if (!clickedInsideDateFilter) {
                    dateFilterDropdowns.forEach(dropdown => {
                        if (!dropdown.classList.contains('hidden')) {
                            const id = dropdown.id;
                            const dropdownElement = document.getElementById(id);
                            if (dropdownElement) {
                                dropdownElement.style.transition = 'opacity .2s ease, transform .2s ease';
                                dropdownElement.style.opacity = '0';
                                dropdownElement.style.transform = 'translateY(-6px)';
                                setTimeout(() => {
                                    dropdownElement.classList.add('hidden');
                                    dropdownElement.style.transition = '';
                                    dropdownElement.style.opacity = '';
                                    dropdownElement.style.transform = '';
                                }, 200);
                            }
                        }
                    });
                }
            });

            ////////////////////////////////////////////////////////////////////
            // Tab Switching Functions
            ////////////////////////////////////////////////////////////////////

            // Store current active tab
            let currentActiveTab = 'all';
            // Make it accessible from partials
            window.currentActiveTab = currentActiveTab;

            // Trigger file input for All section
            function switchTab(tab) {
                // sync tab with URL
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tab);
                window.history.replaceState({}, '', url);

                // Store the current active tab
                currentActiveTab = tab;
                window.currentActiveTab = tab;
                // Reset all tabs
                const tabs = ['all', 'keyword', 'merchant','telkom'];
                tabs.forEach(t => {
                    const btn = document.getElementById('tab-' + t);
                    if (btn) {
                        btn.className = 'shrink-0 px-6 py-2 rounded-full border border-orange-400 text-gray-700 hover:bg-orange-50 transition-colors';
                    }
                });
                // Activate selected tab
                const activeBtn = document.getElementById('tab-' + tab);
                if (activeBtn) {
                    activeBtn.className = 'shrink-0 px-6 py-2 rounded-full bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium shadow-lg';
                }
                // Hide non-active sections with Tailwind utilities
                const sections = ['all', 'keyword', 'merchant','telkom'];
                sections.forEach(s => {
                    const section = document.getElementById('section-' + s);
                    if (section) {
                        if (s !== tab) {
                            section.classList.remove('opacity-100','translate-y-0');
                            section.classList.add('opacity-0','translate-y-5','pointer-events-none');
                            section.classList.add('hidden');
                        }
                    }
                });
                // Show and activate selected section
                const activeSection = document.getElementById('section-' + tab);
                if (activeSection) {
                    activeSection.classList.remove('hidden','pointer-events-none');
                    // ensure starting at hidden state
                    activeSection.classList.add('opacity-0','translate-y-5');
                    requestAnimationFrame(() => {
                        activeSection.classList.remove('opacity-0','translate-y-5');
                        activeSection.classList.add('opacity-100','translate-y-0');
                    });
                }
            }

            // Function to get URL parameter value
            function getUrlParameter(name) {
                const url = new URL(window.location);
                return url.searchParams.get(name);
            }

            // Keep track of selected category per table for toggle behavior
            const selectedCategory = { merchant: 'All', telkom: 'All' };
            // Function to filter table based on category
            function filterTable(tableType, category) {
                // Close the dropdown after selection
                closeAllDropdowns();
                
                // Update the button text to show the selected category
                let buttonId = '';
                if (document.getElementById('section-all').classList.contains('hidden') === false) {
                    // We are in the "All" section
                    if (tableType === 'merchant') buttonId = 'kategoriBtnAll1';
                    else if (tableType === 'telkom') buttonId = 'kategoriBtnAll3';
                } else {
                    // We are in a specific section
                    if (tableType === 'merchant') buttonId = 'kategoriBtnMerchant';
                    else if (tableType === 'telkom') buttonId = 'kategoriBtnTelkom';
                }
                // Toggle: clicking the same category again resets to All
                if (selectedCategory[tableType] === category) {
                    category = 'All';
                }
                selectedCategory[tableType] = category;

                const button = document.getElementById(buttonId);
                if (button) {
                    const label = category === 'All' ? 'Kategori' : category;
                    // Reset button classes
                    button.className = 'flex items-center px-4 py-2 text-sm rounded-full border transition-all duration-300';
                    // Apply category-specific styling
                    if (category === 'All') {
                        button.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    } else if (category === 'F&B') {
                        button.classList.add('border-orange-300', 'text-orange-800', 'bg-gradient-to-r', 'from-orange-100', 'to-red-100', 'hover:from-orange-200', 'hover:to-red-200');
                    } else if (category === 'Entertain') {
                        button.classList.add('border-purple-300', 'text-purple-800', 'bg-gradient-to-r', 'from-purple-100', 'to-pink-100', 'hover:from-purple-200', 'hover:to-pink-200');
                    } else if (category === 'Vacation') {
                        button.classList.add('border-blue-300', 'text-blue-800', 'bg-gradient-to-r', 'from-blue-100', 'to-cyan-100', 'hover:from-blue-200', 'hover:to-cyan-200');
                    } else if (category === 'Shopping') {
                        button.classList.add('border-green-300', 'text-green-800', 'bg-gradient-to-r', 'from-green-100', 'to-emerald-100', 'hover:from-green-200', 'hover:to-emerald-200');
                    } else if (category === 'Beauty & Care') {
                        button.classList.add('border-pink-300', 'text-pink-800', 'bg-gradient-to-r', 'from-pink-100', 'to-rose-100', 'hover:from-pink-200', 'hover:to-rose-200');
                    } else if (category === 'Telkomsel Packet') {
                        button.classList.add('border-indigo-300', 'text-indigo-800', 'bg-gradient-to-r', 'from-indigo-100', 'to-blue-100', 'hover:from-indigo-200', 'hover:to-blue-200');
                    } else if (category === 'Merchandise') {
                        button.classList.add('border-indigo-300', 'text-red-800', 'bg-gradient-to-r', 'from-indigo-100', 'to-blue-100', 'hover:from-indigo-200', 'hover:to-blue-200');
                    }

                    
                    button.innerHTML = `<i class="fas fa-list mr-2"></i>${label}<i class=\"fas fa-chevron-down ml-2 text-xs\"></i>`;
                }
                
                // Filter the table rows based on category
                let tableBodyId = '';
                let rowClass = '';
                
                if (tableType === 'merchant') {
                    tableBodyId = 'merchant-table-body';
                    rowClass = 'merchant-row';
                } else if (tableType === 'telkom') {
                    tableBodyId = 'telkom-table-body';
                    rowClass = 'telkom-row';
                }
                
                const tableBody = document.getElementById(tableBodyId);
                if (tableBody) {
                    const rows = tableBody.querySelectorAll(`.${rowClass}`);
                    rows.forEach(row => {
                        const rowCategory = row.getAttribute('data-category');
                        if (category === 'All' || rowCategory === category) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }

            ////////////////////////////////////////////////////////////////////
            // Modal Functions
            ////////////////////////////////////////////////////////////////////
            // Note: toggleMerchantUploadModal() has been moved to upload-modal.blade.php

            // Keyword upload modal functions are now in upload-modal-keyword.blade.php

            // Initialize tab based on URL parameter on page load
            document.addEventListener('DOMContentLoaded', function () {
                // baca parameter tab dari URL (default 'all' kalau nggak ada)
                const initialTab = "{{ request('tab', 'all') }}";

                // panggil switchTab sesuai tab
                switchTab(initialTab);
            });
                        ////////////////////////////////////////////////////////////////////
            // AJAX Pagination untuk SECTION KEYWORD
            ////////////////////////////////////////////////////////////////////

            document.addEventListener('click', function (event) {
                // Cari <a> yang diklik di dalam #section-keyword
                const link = event.target.closest('#section-keyword a');
                if (!link) return;

                const href = link.getAttribute('href');
                // Kalau link-nya tidak mengandung "page=" → kemungkinan bukan pagination
                if (!href || !href.includes('page=')) return;

                event.preventDefault();

                const url = link.href;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Ambil ulang isi #section-keyword dari HTML yang di-fetch
                    const newSection = doc.querySelector('#section-keyword');
                    const currentSection = document.querySelector('#section-keyword');

                    if (newSection && currentSection) {
                        // Ganti seluruh isi section-keyword (tabel + pagination + cards mobile)
                        currentSection.innerHTML = newSection.innerHTML;
                    }

                    // Pastikan tetap di tab keyword
                    switchTab('keyword');

                    // OPTIONAL: update URL di address bar (tanpa reload) supaya page & tab ikut berubah
                    try {
                        const newUrl = new URL(window.location.href);
                        const fetchedUrl = new URL(url);
                        const newPage = fetchedUrl.searchParams.get('page');

                        if (newPage) {
                            newUrl.searchParams.set('page', newPage);
                        }
                        newUrl.searchParams.set('tab', 'keyword');

                        window.history.replaceState({}, '', newUrl);
                    } catch (e) {
                        console.warn('Tidak bisa update URL state:', e);
                    }
                })
                .catch(error => {
                    console.error('Keyword pagination AJAX error:', error);
                    // fallback: kalau ada error, pakai behaviour normal (reload)
                    window.location.href = url;
                });
            });

        </script>

        <div id="section-all" class="transition-all duration-300 opacity-100 translate-y-0"> 
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4 mt-4 sm:mt-8">Merchant</h2>
            
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div class="flex space-x-3">
                    <div class="relative">
                        <button id="kategoriBtnAll1" onclick="toggleKategoriDropdownAll1()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            Kategori
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="kategoriDropdownAll1" class="hidden absolute left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-64 z-50">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Makanan'); return false;">Makanan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Hiburan'); return false;">Hiburan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Liburan'); return false;">Liburan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Belanja'); return false;">Belanja</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kecantikan'); return false;">Kecantikan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Telkomsel Packet'); return false;">Telkomsel Packet</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-red-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Telkomsel Merchandise'); return false;">Merchandise</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <button
                            type="button"
                            onclick="openUploadMerchant()"
                            class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>
                    </div>

                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" id="merchantSearch" placeholder="Search..." class="w-full sm:w-48 pl-9 pr-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </div>
                    </div>
                    
                    @include('partials.date-filter', ['filterId' => 'dateFilter1'])
                </div>
            </div>
            
            @include('partials.table-merchant')
        </div>

        <div id="section-keyword" class="transition-all duration-300 opacity-0 translate-y-5 hidden pointer-events-none">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Keyword</h2>
            
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div class="flex space-x-3">

                    <div class="relative">
                        <button id="statusBtnKeyword" onclick="toggleStatusDropdownKeyword()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Status 
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="statusDropdownKeyword" class="hidden absolute left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-56 z-50">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-200 hover:text-gray-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('all'); return false;">All</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-yellow-100 hover:to-amber-100 hover:text-yellow-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('pending'); return false;">Pending</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-100 hover:to-rose-100 hover:text-red-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('reject'); return false;">Rejected</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('approve'); return false;">Approved</a>
                            </div>
                        </div>
                    </div>
                    
                <div class="relative">
                        <button type="button" onclick="openUploadKeyword()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Add Keyword
                        </button>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" id="keywordSearch" placeholder="Search..." class="w-full sm:w-48 pl-9 pr-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </div>
                    </div>
                    
                    @include('partials.date-filter', ['filterId' => 'dateFilter6'])
                </div>
            </div>
            
            @include('partials.table-keyword')
        </div>

        <div id="section-merchant" class="transition-all duration-300 opacity-0 translate-y-5 hidden pointer-events-none">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Merchant</h2>
            
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div class="flex space-x-3">
                    <div class="relative">
                        <button id="kategoriBtnMerchant" onclick="toggleKategoriDropdownMerchant()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            Kategori 
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="kategoriDropdownAll1" class="hidden absolute left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-64 z-50">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-200 hover:text-gray-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Semua'); return false;">Semua</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Makanan'); return false;">Makanan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Hiburan'); return false;">Hiburan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'liburan'); return false;">Liburan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Belanja'); return false;">Belanja</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kecantikan'); return false;">Kecantikan</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Telkomsel Packet'); return false;">Telkomsel Packet</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-red-800 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Merhandise'); return false;">Merchandise</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <button type="button" onclick="openUploadMerchant()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" placeholder="Search..." class="w-full sm:w-48 pl-9 pr-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </div>
                    </div>
                    
                    @include('partials.date-filter', ['filterId' => 'dateFilter4'])
                </div>
            </div>
            
            @include('partials.table-merchant')
        </div>

    </main>
    
    @include('partials.upload-modal-merchant')
    @include('partials.upload-modal-merchandise')
    @include('partials.upload-modal-keyword')
    @include('partials.edit-modal-merchant')
    @include('partials.edit-modal-merchandise')
    @include('partials.delete-confirmation-modal')
    @include('partials.approve-confirmation-modal')

    <script>
        let searchTimeout;
        
        // Search functionality for Merchant table - AJAX search across all pages
        document.getElementById('merchantSearch').addEventListener('keyup', function(e) {
            clearTimeout(searchTimeout);
            const searchTerm = e.target.value.trim();
            
            // If search is empty, reload the page to show all merchants
            if (searchTerm === '') {
                location.reload();
                return;
            }
            
            // Debounce the search request
            searchTimeout = setTimeout(() => {
                fetch(`/merchants/search?q=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateMerchantTable(data);
                })
                .catch(error => console.error('Search error:', error));
            }, 300);
        });
        
        function updateMerchantTable(data) {
            const merchants = data.merchants;
            const pagination = data.pagination;
            
            // Update table body
            const tableBody = document.getElementById('merchant-table-body');
            if (tableBody) {
                if (merchants.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="11" class="px-4 py-4 text-center text-sm text-gray-500">Belum ada data merchant.</td></tr>';
                } else {
                    tableBody.innerHTML = merchants.map((merchant, index) => `
                        <tr class="hover:bg-gray-50 transition-colors merchant-row" data-category="${merchant.kategori || 'All'}">
                            <td class="px-4 py-4 w-20 text-center text-sm font-medium text-gray-900">${(pagination.current_page - 1) * pagination.per_page + index + 1}</td>
                            <td class="px-4 py-4 w-20 text-center">
                                <div class="flex items-center justify-center h-full">
                                    <button type="button"
                                            onclick="showDeleteConfirmation('Merchant', '${merchant.nama_merchant}', ${merchant.id})"
                                            class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                            title="Hapus">
                                        <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">${merchant.daerah}</td>
                            <td class="px-4 py-4 w-20 text-center text-sm font-semibold text-gray-900">${merchant.nama_merchant}</td>
                            <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">${merchant.kategori || '-'}</td>
                            <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">
                                ${merchant.logo_merchant ? `
                                    <a href="/storage/${merchant.logo_merchant}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center h-10 w-10 rounded-lg overflow-hidden border border-gray-300 hover:border-blue-500 transition-colors hover:shadow-md">
                                        <img src="/storage/${merchant.logo_merchant}" 
                                             alt="${merchant.nama_merchant}" 
                                             class="h-full w-full object-cover">
                                    </a>
                                ` : '<span class="text-gray-400">-</span>'}
                            </td>
                        </tr>
                    `).join('');
                }
            }
            
            // Update mobile cards
            const cardsContainer = document.getElementById('merchant-cards-container');
            if (cardsContainer) {
                if (merchants.length === 0) {
                    cardsContainer.innerHTML = '<p class="text-sm text-center text-gray-500">Belum ada data merchant.</p>';
                } else {
                    cardsContainer.innerHTML = merchants.map((merchant, index) => `
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col space-y-3 merchant-row" data-category="${merchant.kategori || 'All'}">
                            <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">${(pagination.current_page - 1) * pagination.per_page + index + 1}</p>
                                </div>
                                <div class="flex items-center">
                                    <button type="button"
                                            onclick="showDeleteConfirmation('Merchant', '${merchant.nama_merchant}', ${merchant.id})"
                                            class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                            title="Hapus">
                                        <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Daerah</p>
                                <p class="text-sm text-gray-700 mt-1">${merchant.daerah}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Merchant</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">${merchant.nama_merchant}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Kategori</p>
                                <p class="text-sm text-gray-700 mt-1">${merchant.kategori || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Logo Merchant</p>
                                <div class="mt-2 flex items-center space-x-2">
                                    ${merchant.logo_merchant ? `
                                        <button type="button" 
                                                onclick="previewMerchantLogo('/storage/${merchant.logo_merchant}', '${merchant.logo_merchant.split('/').pop()}')"
                                                class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                                            <img src="/storage/${merchant.logo_merchant}" 
                                                 alt="${merchant.nama_merchant}" 
                                                 class="h-full w-full object-cover">
                                        </button>
                                        <span class="text-sm text-gray-700 font-medium">${merchant.nama_merchant}</span>
                                    ` : '<span class="text-sm text-gray-400">-</span>'}
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            }
            
            // Update pagination
            updatePagination(pagination);
        }
        
        function updatePagination(pagination) {
            const paginationContainer = document.querySelector('.bg-white.px-4.py-4.border-t.border-gray-200');
            if (!paginationContainer) return;
            
            let paginationHTML = `
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">${pagination.from || 0}</span> hingga <span class="font-semibold">${pagination.to || 0}</span> dari <span class="font-semibold">${pagination.total}</span> data
                </div>
                <div class="flex items-center space-x-2">
            `;
            
            // Previous button
            if (pagination.current_page === 1) {
                paginationHTML += '<button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"><i class="fas fa-chevron-left"></i></button>';
            } else {
                paginationHTML += `<button onclick="searchPage(${pagination.current_page - 1})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"><i class="fas fa-chevron-left"></i></button>`;
            }
            
            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    paginationHTML += `<button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">${i}</button>`;
                } else {
                    paginationHTML += `<button onclick="searchPage(${i})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">${i}</button>`;
                }
            }
            
            // Next button
            if (pagination.current_page === pagination.last_page) {
                paginationHTML += '<button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"><i class="fas fa-chevron-right"></i></button>';
            } else {
                paginationHTML += `<button onclick="searchPage(${pagination.current_page + 1})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"><i class="fas fa-chevron-right"></i></button>`;
            }
            
            paginationHTML += '</div>';
            paginationContainer.innerHTML = paginationHTML;
        }
        
        function searchPage(page) {
            const searchTerm = document.getElementById('merchantSearch').value.trim();
            if (!searchTerm) return;
            
            fetch(`/merchants/search?q=${encodeURIComponent(searchTerm)}&page=${page}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateMerchantTable(data);
                window.scrollTo(0, 0);
            })
            .catch(error => console.error('Search error:', error));
        }
    </script>
</body>
</html>