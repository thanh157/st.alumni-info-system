<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
    }

    .thank-you-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        position: relative;
        padding: 20px;
    }

    .thank-you-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .thank-you-card {
        background: white;
        border-radius: 24px;
        padding: 50px 40px;
        max-width: 560px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
        animation: slideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .icon-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: scaleIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .icon-wrapper::before {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        opacity: 0.2;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.2;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.1;
        }
    }

    .checkmark {
        width: 60px;
        height: 60px;
        position: relative;
        z-index: 1;
    }

    .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke: white;
        stroke-width: 3;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
    }

    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke: white;
        stroke-width: 3;
        fill: none;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    .thank-you-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 16px;
        line-height: 1.3;
        animation: fadeInUp 0.6s ease 0.4s both;
    }

    .thank-you-message {
        font-size: 1.1rem;
        color: #718096;
        line-height: 1.7;
        margin-bottom: 30px;
        animation: fadeInUp 0.6s ease 0.5s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .divider {
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        margin: 0 auto 25px;
        border-radius: 2px;
        animation: expandWidth 0.8s ease 0.6s both;
    }

    @keyframes expandWidth {
        from {
            width: 0;
        }

        to {
            width: 60px;
        }
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        background: white;
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 126, 52, 0.3);
        animation: fadeInUp 0.6s ease 0.7s both;
        width: 200px;
        height: 80px;
        overflow: hidden;
    }

    .back-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 126, 52, 0.5);
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #28a745;
        animation: confetti-fall 3s ease-in-out infinite;
    }

    @keyframes confetti-fall {
        to {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    @media (max-width: 576px) {
        .thank-you-card {
            padding: 40px 30px;
        }

        .thank-you-title {
            font-size: 1.6rem;
        }

        .thank-you-message {
            font-size: 1rem;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
        }

        .checkmark {
            width: 50px;
            height: 50px;
        }
    }
</style>
</head>

<body>
    <div class="thank-you-wrapper">
        <div class="thank-you-card text-center">
            <!-- Icon with animated checkmark -->
            <div class="icon-wrapper">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" />
                    <path class="checkmark-check" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="thank-you-title">
                Cảm ơn Anh/Chị đã hoàn thành khảo sát!
            </h1>

            <div class="divider"></div>

            <!-- Message -->
            <p class="thank-you-message">
                Học viện đã ghi nhận phản hồi của Anh/Chị.<br>
                Cảm ơn Anh/Chị đã dành thời gian quý báu để giúp chúng tôi cải thiện chất lượng đào tạo.
            </p>

            <!-- Optional Button -->
            {{-- <a href="#" class="back-btn">
                <img src="{{asset('assets/client/images/logo-vnua.jpg')}}"
                    alt="Logo Học viện Nông nghiệp Việt Nam">
            </a> --}}
        </div>
    </div>

    <script>
        // Create confetti effect
        function createConfetti() {
            const colors = ['#1e7e34', '#28a745', '#20c997', '#5cb85c'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    document.querySelector('.thank-you-wrapper').appendChild(confetti);

                    setTimeout(() => confetti.remove(), 5000);
                }, i * 30);
            }
        }

        // Trigger confetti on load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 800);
        });
    </script>
