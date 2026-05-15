@extends('layouts.app')

@section('additional_styles')
<style>
    .departments-section {
        padding: 80px 0;
        background-color: var(--background-light);
    }

    .departments-section h2 {
        font-size: 32px;
        color: #004aad;
        margin-bottom: 40px;
    }

    .department-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .department-card:hover {
        transform: translateY(-10px);
    }

    .department-card .icon {
        font-size: 48px;
        color: #004aad;
        margin-bottom: 10px;
    }

    .department-card h4 {
        font-size: 22px;
        color: #004aad;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .department-card p {
        font-size: 16px;
        color: #333;
        margin-bottom: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }

    .department-card a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .department-card a:hover {
        color: #0056b3;
    }
</style>
@endsection

@section('content')
<section class="departments-section text-center">
    <div class="container">
        <h2 class="mb-5">Our Departments</h2>   
        <div class="row">
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#129504;</div>
                    <h4>Neurology</h4>
                    <p>Neurology is the branch of medicine that deals with the study and treatment of disorders of the nervous system.</p>
                    <a href="https://en.wikipedia.org/wiki/Neurology">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128151;</div>
                    <h4>Cardiology</h4>
                    <p>Cardiology is the study of the heart and a branch of medicine that deals with disorders of the heart.</p>
                    <a href="https://en.wikipedia.org/wiki/Cardiology">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128137;</div>
                    <h4>Surgery</h4>
                    <p>Surgery is a branch of medicine that involves manual and instrumental treatment of injuries, diseases, and other disorders.</p>
                    <a href="https://en.wikipedia.org/wiki/Surgery">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128300;</div>
                    <h4>Gastroenterology</h4>
                    <p>Gastroenterology is the branch of medicine focused on the digestive system and its disorders.</p>
                    <a href="https://en.wikipedia.org/wiki/Gastroenterology">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128568;</div>
                    <h4>Ophthalmology</h4>
                    <p>Ophthalmology is a clinical and surgical specialty within medicine that deals with the diagnosis and treatment of eye disorders.</p>
                    <a href="https://en.wikipedia.org/wiki/Ophthalmology">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128118;</div>
                    <h4>Pediatrics</h4>
                    <p>Pediatrics is the branch of medicine that involves the medical care of infants, children and young adults.</p>
                    <a href="https://en.wikipedia.org/wiki/Pediatrics">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128170;</div>
                    <h4>Orthopedics</h4>
                    <p> Orthopedics is a branch of medicine that focuses on the care of the skeletal system and its interconnecting parts.</p>
                    <a href="https://en.wikipedia.org/wiki/Orthopedic_surgery">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128177;</div>
                    <h4>Dentistry</h4>
                    <p>Dentistry, also known as dental medicine, focuses on oral health and diseases of the teeth and gums.</p>
                    <a href="https://en.wikipedia.org/wiki/Dentistry">READ MORE</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="department-card">
                    <div class="icon">&#128300;</div>
                    <h4>Radiology</h4>
                    <p>Radiology involves imaging techniques to diagnose and treat diseases within the body.</p>
                    <a href="https://en.wikipedia.org/wiki/Radiology">READ MORE</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

