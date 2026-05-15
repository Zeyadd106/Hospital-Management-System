@extends('layouts.app')

@section('additional_styles')
<style>
    .blog-container {
        padding: 50px 20px;
        background-color: var(--background-light);
    }

    .blog-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .blog-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .blog-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .blog-card-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 10px;
    }

    .blog-card-desc {
        font-size: 1rem;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -10px;
    }

    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 10px;
        display: flex;
    }

    .blog-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .learn-more-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        text-decoration: none;
        font-size: 14px;
        text-align: center;
        display: inline-block;
        margin-top: 15px;
    }

    .learn-more-btn:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="blog-container">
    <div class="container">
        <div class="blog-header">
            <h2>Our Blog</h2>
            <p>Stay updated with the latest health tips and medical news</p>
        </div>
        
        <div class="row">
            <!-- Blog Post 1 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/stomach-cancer.jpg') }}" alt="Stomach Cancer Awareness">
                        <h3 class="blog-card-title mt-3">Stomach Cancer Awareness Month</h3>
                        <p class="blog-card-desc">Breaking the Silence on a Silent Killer</p>
                        <p>Observed every year during November, Stomach Cancer Awareness month is a global healthcare event.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
            
            <!-- Blog Post 2 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/spinal-tuberculosis.jpg') }}" alt="Managing Spinal TB">
                        <h3 class="blog-card-title mt-3">Managing Spinal TB</h3>
                        <p class="blog-card-desc">Symptoms, Complications and Care</p>
                        <p>For those dealing with spinal tuberculosis (TB), waking up with a persistent, gnawing pain in your spine.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
            
            <!-- Blog Post 3 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/healthy-foods.jpg') }}" alt="Healthy Fat Foods">
                        <h3 class="blog-card-title mt-3">Healthy Fat Foods</h3>
                        <p class="blog-card-desc">12 Fat-Rich Foods to Eat</p>
                        <p>In today's high-adrenaline world, understanding the impact of the foods we consume is crucial to health.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
            
            <!-- Blog Post 4 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/mental-health.jpg') }}" alt="Mental Health Awareness">
                        <h3 class="blog-card-title mt-3">Mental Health Awareness</h3>
                        <p class="blog-card-desc">Understanding Mental Health Challenges</p>
                        <p>Mental health awareness seeks to increase knowledge about mental illnesses and reduce stigma.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
            
            <!-- Blog Post 5 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/healthy-lifestyle.jpg') }}" alt="Healthy Lifestyle Choices">
                        <h3 class="blog-card-title mt-3">Healthy Lifestyle Choices</h3>
                        <p class="blog-card-desc">Key Factors for a Long Life</p>
                        <p>Adopting a healthy lifestyle can help you live longer and enjoy a more fulfilling life.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
            
            <!-- Blog Post 6 -->
            <div class="col-md-4">
                <div class="blog-card">
                    <div>
                        <img src="{{ asset('images/blog/nutrition.jpg') }}" alt="Nutrition and Health">
                        <h3 class="blog-card-title mt-3">Nutrition and Health</h3>
                        <p class="blog-card-desc">The Importance of Good Nutrition</p>
                        <p>Nutrition plays a key role in the prevention and management of chronic diseases.</p>
                    </div>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Blog pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
