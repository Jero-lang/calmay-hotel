
@extends('layouts.app')

@section('title', 'FAQs - Calmay River Hotel')

@section('content')
<div class="faq-page">
    {{-- ── FAQ Hero Section ────────────────────────────────── --}}
    <div class="faq-hero">
        <h1 class="faq-title">
            Frequently Asked Questions
        </h1>
        <p class="faq-subtitle">
            Find answers to common questions about our hotel, booking process, and services.
        </p>
    </div>

    {{-- ── FAQ Content Section ────────────────────────────────── --}}
    <div class="faq-content">
        <div class="faq-search">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search for questions...">
            </div>
            <p class="search-hint">Can't find what you're looking for? <a href="#contact">Contact our support team</a></p>
        </div>

        {{-- ── Questions List ────────────────────────────────── --}}
        <div class="faq-questions">
            {{-- Question 1 --}}
            <div class="faq-question-card">
                <div class="question-header">
                    <div class="question-number">Q1</div>
                    <h3 class="question-title">If I have problems, who should I contact?</h3>
                    <button class="question-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="question-answer">
                    <div class="answer-content">
                            <p>Please contact the staff of Calmay River Hotel as soon as possible!</p>
                        <div class="answer-actions">
                            <div class="action-item">
                                <i class="fas fa-phone"></i>
                                <span>Call us: +1 (555) 123-4567</span>
                            </div>
                            <div class="action-item">
                                <i class="fas fa-envelope"></i>
                                <span>Email: support@calmayriver.com</span>
                            </div>
                            <div class="action-item">
                                <i class="fas fa-clock"></i>
                                <span>Available 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Question 2 --}}
            <div class="faq-question-card">
                <div class="question-header">
                    <div class="question-number">Q2</div>
                    <h3 class="question-title">I want to cancel my approved booking. What should I do?</h3>
                    <button class="question-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="question-answer">
                    <div class="answer-content">
                            <p>Please contact us as soon as possible to cancel your confirmed booking. We will process your cancellation request and assist you with your receipt credentials.</p>
                        <div class="answer-actions">
                            <div class="action-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Note: Cancellation policy applies based on booking terms</span>
                            </div>
                            <div class="action-item">
                                <i class="fas fa-file-invoice"></i>
                                <span>Have your booking reference number ready</span>
                            </div>
                            <div class="action-item">
                                <i class="fas fa-headset"></i>
                                <span>Contact our support team for assistance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Additional Help Section ────────────────────────── --}}
        <div class="additional-help">
            <h3 class="help-title">Still Need Help?</h3>
            <p class="help-description">Our customer support team is ready to assist you with any questions or concerns.</p>
            
            <div class="help-options">
                <div class="help-option">
                    <div class="option-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Call Us</h4>
                    <p>+1 (555) 123-4567</p>
                    <small>24/7 Support Line</small>
                </div>
                
                <div class="help-option">
                    <div class="option-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Us</h4>
                    <p>support@calmayriver.com</p>
                    <small>Response within 2 hours</small>
                </div>
                
                <div class="help-option">
                    <div class="option-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>Live Chat</h4>
                    <p>Chat with our team</p>
                    <small>Available 8AM-10PM</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .faq-page {
        padding: 0;
    }

    /* ── FAQ Hero ────────────────────────────────── */
    .faq-hero {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.15) 0%, rgba(102, 187, 106, 0.08) 100%);
        border: 1px solid rgba(79, 195, 247, 0.25);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        margin-bottom: 50px;
    }

    .faq-title {
        font-size: 3rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #4fc3f7, #0288d1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .faq-subtitle {
        font-size: 1.2rem;
        color: #b0bec5;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── FAQ Search ────────────────────────────────── */
    .faq-search {
        margin-bottom: 50px;
    }

    .search-container {
        position: relative;
        max-width: 700px;
        margin: 0 auto 15px;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #4fc3f7;
        font-size: 1.2rem;
        z-index: 2;
    }

    .search-input {
        width: 100%;
        padding: 18px 20px 18px 55px;
        border-radius: 12px;
        border: 2px solid rgba(79, 195, 247, 0.2);
        background: rgba(255, 255, 255, 0.04);
        color: #e0e0e0;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #4fc3f7;
        box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.15);
        background: rgba(255, 255, 255, 0.06);
    }

    .search-hint {
        text-align: center;
        color: #78909c;
        font-size: 0.9rem;
    }

    .search-hint a {
        color: #4fc3f7;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .search-hint a:hover {
        color: #66bb6a;
        text-decoration: underline;
    }

    /* ── FAQ Questions ────────────────────────────────── */
    .faq-questions {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-bottom: 60px;
    }

    .faq-question-card {
        background: rgba(26, 26, 46, 0.6);
        border: 1px solid rgba(79, 195, 247, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-question-card:hover {
        border-color: rgba(79, 195, 247, 0.2);
        box-shadow: 0 8px 30px rgba(79, 195, 247, 0.1);
    }

    .question-header {
        display: flex;
        align-items: center;
        padding: 25px 30px;
        cursor: pointer;
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.05) 0%, rgba(2, 136, 209, 0.02) 100%);
        transition: all 0.3s ease;
    }

    .question-header:hover {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.1) 0%, rgba(2, 136, 209, 0.05) 100%);
    }

    .question-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4fc3f7, #0288d1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a0e17;
        font-weight: 800;
        font-size: 1.2rem;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .question-title {
        flex: 1;
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        padding-right: 20px;
    }

    .question-toggle {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        border: 2px solid rgba(79, 195, 247, 0.3);
        background: rgba(79, 195, 247, 0.1);
        color: #4fc3f7;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .question-toggle:hover {
        background: rgba(79, 195, 247, 0.2);
        border-color: #4fc3f7;
        transform: rotate(180deg);
    }

    .question-toggle i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    /* ── Answer Section ────────────────────────────────── */
    .question-answer {
        display: block;
        padding: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.03) 0%, rgba(102, 187, 106, 0.01) 100%);
    }

    .answer-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .answer-content p {
        font-size: 1.1rem;
        color: #e0e0e0;
        line-height: 1.6;
        margin: 0;
        padding: 0 10px;
    }

    .answer-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
        color: #90a4ae;
    }

    .action-item i {
        color: #4fc3f7;
        width: 20px;
        flex-shrink: 0;
    }

    .action-item span {
        flex: 1;
    }

    /* ── Additional Help ────────────────────────────────── */
    .additional-help {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.08) 0%, rgba(102, 187, 106, 0.04) 100%);
        border: 1px solid rgba(79, 195, 247, 0.15);
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
    }

    .help-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .help-description {
        font-size: 1.1rem;
        color: #b0bec5;
        max-width: 700px;
        margin: 0 auto 40px;
        line-height: 1.6;
    }

    .help-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .help-option {
        background: rgba(26, 26, 46, 0.6);
        border: 1px solid rgba(79, 195, 247, 0.1);
        border-radius: 16px;
        padding: 30px 25px;
        transition: all 0.3s ease;
    }

    .help-option:hover {
        transform: translateY(-8px);
        border-color: rgba(79, 195, 247, 0.3);
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.15);
    }

    .option-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.2), rgba(102, 187, 106, 0.1));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: #4fc3f7;
    }

    .help-option h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .help-option p {
        font-size: 1.1rem;
        color: #4fc3f7;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .help-option small {
        font-size: 0.8rem;
        color: #78909c;
    }

    /* ── JavaScript Toggle ────────────────────────────── */
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionToggles = document.querySelectorAll('.question-toggle');
            
            questionToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const questionCard = this.closest('.faq-question-card');
                    const answer = questionCard.querySelector('.question-answer');
                    const icon = this.querySelector('i');
                    
                    // Toggle answer visibility
                    if (answer.style.display === 'none' || answer.style.display === '') {
                        answer.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                        this.style.transform = 'rotate(180deg)';
                        questionCard.style.borderColor = 'rgba(79, 195, 247, 0.3)';
                    } else {
                        answer.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                        this.style.transform = 'rotate(0deg)';
                        questionCard.style.borderColor = 'rgba(79, 195, 247, 0.1)';
                    }
                });
            });

            // Show all answers by default
            const answers = document.querySelectorAll('.question-answer');
            answers.forEach(answer => {
                answer.style.display = 'block';
            });
        });
    </script>

    /* ── Responsive Design ────────────────────────────── */
    @media (max-width: 1024px) {
        .faq-title {
            font-size: 2.5rem;
        }
        
        .help-options {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .faq-hero {
            padding: 40px 25px;
        }
        
        .faq-title {
            font-size: 2rem;
        }
        
        .faq-subtitle {
            font-size: 1rem;
        }
        
        .question-header {
            padding: 20px;
            flex-wrap: wrap;
        }
        
        .question-number {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            margin-right: 15px;
        }
        
        .question-title {
            font-size: 1.1rem;
            padding-right: 10px;
            flex: 1;
            min-width: 70%;
        }
        
        .question-toggle {
            width: 36px;
            height: 36px;
            order: 3;
            margin-top: 10px;
            margin-left: auto;
        }
        
        .help-options {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .question-answer {
            padding: 20px;
        }
        
        .search-input {
            padding: 15px 15px 15px 50px;
        }
    }
</style>
@endsection