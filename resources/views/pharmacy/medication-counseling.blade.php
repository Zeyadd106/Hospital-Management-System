@extends('layouts.app')

@section('content')
<div class="main-content" style="align-items: flex-start;">
    <div class="container py-5">
        <h1 class="text-center mb-5">Medication Counseling Services</h1>
        
        <div class="row mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('images/pharmacy/counseling.jpg') }}" alt="Pharmacist counseling a patient" class="img-fluid rounded shadow-sm">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Expert Medication Guidance</h2>
                <p class="lead">Our pharmacists provide personalized medication counseling to ensure you understand your treatment plan and get the most from your medications.</p>
                <p>During a medication counseling session, our pharmacists will:</p>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Review all your current medications</li>
                    <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Explain how to take medications properly</li>
                    <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Discuss potential side effects and how to manage them</li>
                    <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Identify possible drug interactions</li>
                    <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Answer any questions you have about your medications</li>
                </ul>
                <a href="{{ route('contact.index') }}" class="btn btn-primary">Schedule a Consultation</a>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Benefits of Medication Counseling</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="text-center">
                                    <i class="fas fa-pills fa-3x text-primary mb-3"></i>
                                    <h4>Improved Medication Adherence</h4>
                                    <p>Understanding your medications better leads to better adherence to your treatment plan.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="text-center">
                                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                    <h4>Reduced Side Effects</h4>
                                    <p>Learn how to minimize and manage potential side effects of your medications.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-heartbeat fa-3x text-primary mb-3"></i>
                                    <h4>Better Health Outcomes</h4>
                                    <p>Proper medication use leads to better management of your health conditions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Frequently Asked Questions</h3>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How long does a medication counseling session take?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        A typical medication counseling session takes about 15-30 minutes, depending on the complexity of your medication regimen and the number of questions you have.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Is there a fee for medication counseling?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Basic medication counseling is provided free of charge when you fill a prescription at our pharmacy. More comprehensive medication reviews may have a fee, which is often covered by insurance.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Should I bring anything to my counseling session?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes, please bring all your current medications (prescription and over-the-counter), supplements, and a list of any questions you have about your medications.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
