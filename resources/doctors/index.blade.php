<x-layout>
    <x-slot name="title">Our Doctors - MediCare</x-slot>
    
    @push('styles')
    <style>
        .doctors-section {
            padding: 4rem 0;
        }
        
        .doctor-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }
        
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .doctor-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .doctor-specialty {
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .doctor-department {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .doctor-contact {
            font-size: 0.9rem;
        }
        
        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
    </style>
    @endpush
    
    <div class="main-content" style="align-items: flex-start;">
        <div class="container py-5">
            <h1 class="text-center mb-5">Our Medical Team</h1>
            
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="departmentFilter" class="form-label">Filter by Department</label>
                        <select id="departmentFilter" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="specialtyFilter" class="form-label">Filter by Specialty</label>
                        <select id="specialtyFilter" class="form-select">
                            <option value="">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}">{{ $specialty }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="searchDoctor" class="form-label">Search by Name</label>
                        <input type="text" id="searchDoctor" class="form-control" placeholder="Search doctors...">
                    </div>
                </div>
            </div>
            
            <div class="row" id="doctorsContainer">
                @forelse($doctors as $doctor)
                <div class="col-md-6 col-lg-4 mb-4 doctor-item" 
                     data-department="{{ $doctor->department }}" 
                     data-specialty="{{ $doctor->specialty }}">
                    <div class="card doctor-card h-100">
                        <img src="{{ asset($doctor->avatar) }}" class="doctor-image" alt="{{ $doctor->name }}">
                        <div class="card-body">
                            <h3 class="card-title h5">{{ $doctor->name }}</h3>
                            <p class="doctor-specialty mb-1">{{ $doctor->specialty }}</p>
                            <p class="doctor-department mb-2">{{ $doctor->department }}</p>
                            <div class="doctor-contact mb-3">
                                <div><i class="fas fa-envelope text-primary me-2"></i>{{ $doctor->email }}</div>
                                <div><i class="fas fa-phone text-primary me-2"></i>{{ $doctor->phone }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-primary">View Profile</a>
                                <a href="{{ route('chat.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-comments me-1"></i> Chat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No doctors are currently available. Please check back later.
                    </div>
                </div>
                @endforelse
            </div>
            
            <div id="noResults" class="alert alert-info text-center" style="display: none;">
                <i class="fas fa-search me-2"></i>
                No doctors match your search criteria. Please try different filters.
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentFilter = document.getElementById('departmentFilter');
            const specialtyFilter = document.getElementById('specialtyFilter');
            const searchInput = document.getElementById('searchDoctor');
            const doctorsContainer = document.getElementById('doctorsContainer');
            const noResults = document.getElementById('noResults');
            const doctorItems = document.querySelectorAll('.doctor-item');
            
            function filterDoctors() {
                const department = departmentFilter.value.toLowerCase();
                const specialty = specialtyFilter.value.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();
                
                let visibleCount = 0;
                
                doctorItems.forEach(item => {
                    const itemDepartment = item.dataset.department.toLowerCase();
                    const itemSpecialty = item.dataset.specialty.toLowerCase();
                    const doctorName = item.querySelector('.card-title').textContent.toLowerCase();
                    
                    const departmentMatch = department === '' || itemDepartment.includes(department);
                    const specialtyMatch = specialty === '' || itemSpecialty.includes(specialty);
                    const nameMatch = searchTerm === '' || doctorName.includes(searchTerm);
                    
                    if (departmentMatch && specialtyMatch && nameMatch) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                if (visibleCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
            
            departmentFilter.addEventListener('change', filterDoctors);
            specialtyFilter.addEventListener('change', filterDoctors);
            searchInput.addEventListener('input', filterDoctors);
        });
    </script>
    @endpush
</x-layout>