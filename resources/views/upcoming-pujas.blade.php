@extends('layouts.app')

@section('content')
    <div class="spiritual-container">
        <div class="mandala-bg"></div>

        <!-- Floating Lotus Elements -->
        <div class="floating-lotus">🪷</div>
        <div class="floating-lotus">🪷</div>
        <div class="floating-lotus">🪷</div>
        <div class="floating-lotus">🪷</div>

        <div class="sacred-calendar">
            <!-- Sacred Header -->
            <div class="om-header">
                <div class="om-symbol">ॐ</div>
                <h1 class="sacred-title">आध्यात्मिक पंचांग</h1>
                <p class="sanskrit-blessing">सर्वे भवन्तु सुखिनः सर्वे सन्तु निरामयाः</p>
            </div>

            <!-- Lotus Calendar Card -->
            <div class="lotus-calendar-card">
                <div class="pattern-overlay"></div>

                <!-- Sacred Navigation -->
                <div class="sacred-nav">
                    <button class="nav-lotus-btn" id="prevMonth">❮</button>
                    <div class="month-display">
                        <div class="month-name" id="monthName">September</div>
                        <div class="month-year" id="yearDisplay">2025</div>
                    </div>
                    <button class="nav-lotus-btn" id="nextMonth">❯</button>
                </div>

                <!-- Day Labels -->
                <div class="day-labels">
                    <div class="day-label">रवि</div>
                    <div class="day-label">सोम</div>
                    <div class="day-label">मंगल</div>
                    <div class="day-label">बुध</div>
                    <div class="day-label">गुरु</div>
                    <div class="day-label">शुक्र</div>
                    <div class="day-label">शनि</div>
                </div>

                <!-- Calendar Days Grid -->
                <div class="calendar-days" id="calendarDays">
                    <!-- Days will be populated by JavaScript -->
                </div>

                <!-- Sacred Mantra -->
                <div class="mantra-text">
                    गुरुब्रह्मा गुरुर्विष्णुः गुरुर्देवो महेश्वरः । गुरुर्साक्षात् परब्रह्म तस्मै श्रीगुरवे नमः ॥
                </div>
            </div>
        </div>
    </div>

    <!-- Festival Modal -->
    <div class="festival-modal" id="festivalModal">
        <div class="modal-content">
            <div class="modal-header">
                <img class="festival-image" id="modalFestivalImage" src="" alt="">
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body">
                <h2 class="modal-festival-name" id="modalFestivalName"></h2>
                <div class="modal-festival-date" id="modalFestivalDate"></div>
                <p class="modal-festival-desc" id="modalFestivalDesc"></p>
                <div class="modal-festival-price">
                    <span class="current-price" id="modalCurrentPrice"></span>
                    <span class="original-price" id="modalOriginalPrice"></span>
                    <span class="discount-badge" id="modalDiscount"></span>
                </div>
                <button class="modal-cta-btn" id="modalCtaBtn">Add to Cart</button>
            </div>
        </div>
    </div>

    <style>

        /* === Overflow and Theme Patch START === */
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { margin: 0; padding: 0; width:100%; overflow-x: hidden; }

        .mandala-bg { position: absolute; inset: 0; width:100%; height:100%; overflow:hidden; max-width:100%; max-height:100%; pointer-events:none; }

        .spiritual-container { position: relative; min-height: 100vh; width:100%; max-width:100%; overflow-x:hidden; overflow-y:visible; }

        .sacred-calendar { width:100%; max-width:1200px; margin:0 auto; }
        .lotus-calendar-card { overflow: visible; }
        .calendar-grid, .calendar-days, .calendar-day, .calendar-cell { overflow: visible; }

        .festival-modal { position: fixed; inset: 0; display: flex; align-items:center; justify-content:center; overflow: hidden; opacity:0; visibility:hidden; z-index:9999; }
        .festival-modal.show { opacity:1; visibility:visible; overflow-y:auto; }
        .modal-content { max-height: 90vh; overflow: auto; }

        @media (max-width: 640px) {
            .spiritual-container, .sacred-calendar, .lotus-calendar-card, .calendar-days { width:100%; max-width:100%; }
        }
        /* === Overflow and Theme Patch END === */

        :root {
            --vibrant-pink: #ff3c65;
            --vibrant-pink-light: #ff9aaf;
            --vibrant-pink-dark: #dc002e;
            --sacred-gold: #d4af37;
            --divine-saffron: #ff8f00;
            --lotus-pink: #f06292;
            --sacred-orange: #ff6b35;
            --peace-blue: #5c6bc0;
            --meditation-purple: #8e24aa;
            --temple-brown: #8d6e63;
        }

        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }



        /* Sacred Geometry Background */
        .spiritual-container {
            position: relative;
            background: radial-gradient(circle at 30% 40%, rgba(255, 143, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(240, 98, 146, 0.1) 0%, transparent 50%),
                linear-gradient(135deg, #fef7e0 0%, #fff8e1 100%);
            min-height: calc(100vh - 200px);
            /* Account for header/footer */
            overflow-x: hidden;
            width: 100%;
            padding-top: 2rem;
        }

        /* Animated Mandala Background */
        .mandala-bg {
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            opacity: 0.05;
            background-image:
                radial-gradient(circle at 25% 25%, var(--vibrant-pink) 2px, transparent 2px),
                radial-gradient(circle at 50% 50%, var(--lotus-pink) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, var(--peace-blue) 1.5px, transparent 1.5px);
            background-size: 60px 60px, 40px 40px, 80px 80px;
            animation: mandalaRotate 60s linear infinite;
        }

        @keyframes mandalaRotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Sacred Calendar Container */
        .sacred-calendar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            position: relative;
            z-index: 10;
            width: 100%;
        }

        /* Om Symbol Header */
        .om-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .om-symbol {
            font-size: 4rem;
            color: var(--vibrant-pink);
            margin-bottom: 1rem;
            animation: omGlow 3s ease-in-out infinite alternate;
            text-shadow: 0 0 20px rgba(255, 60, 101, 0.5);
        }

        @keyframes omGlow {
            0% {
                text-shadow: 0 0 20px rgba(255, 60, 101, 0.5);
            }

            100% {
                text-shadow: 0 0 40px rgba(255, 60, 101, 0.8), 0 0 60px rgba(255, 60, 101, 0.3);
            }
        }

        .sacred-title {
            font-family: 'Noto Serif Devanagari', serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--vibrant-pink), var(--divine-saffron), var(--lotus-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .sanskrit-blessing {
            font-family: 'Noto Sans Devanagari', sans-serif;
            color: var(--meditation-purple);
            font-size: 1.1rem;
            opacity: 0.8;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Lotus Calendar Card */
        .lotus-calendar-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 248, 225, 0.95) 100%);
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 2px solid rgba(255, 60, 101, 0.2);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 100%;
        }

        /* Decorative Corners */
        .lotus-calendar-card::before,
        .lotus-calendar-card::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--vibrant-pink) 0%, transparent 60%);
            border-radius: 50%;
            opacity: 0.1;
        }

        .lotus-calendar-card::before {
            top: -50px;
            left: -50px;
        }

        .lotus-calendar-card::after {
            bottom: -50px;
            right: -50px;
        }

        /* Calendar Navigation */
        .sacred-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem 0;
            width: 100%;
        }

        .nav-lotus-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, var(--vibrant-pink) 0%, var(--divine-saffron) 100%);
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(255, 60, 101, 0.3);
            flex-shrink: 0;
        }

        .nav-lotus-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transition: all 0.6s;
            transform: translate(-50%, -50%);
        }

        .nav-lotus-btn:hover::before {
            width: 120px;
            height: 120px;
        }

        .nav-lotus-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 12px 35px rgba(255, 60, 101, 0.4);
        }

        .month-display {
            text-align: center;
            font-family: 'Noto Serif Devanagari', serif;
            flex: 1;
            margin: 0 1rem;
            overflow: hidden;
        }

        .month-name {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--meditation-purple);
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
        }

        .month-year {
            font-size: 1.1rem;
            color: var(--temple-brown);
            opacity: 0.8;
        }

        /* Sacred Day Labels */
        .day-labels {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
            width: 100%;
        }

        .day-label {
            text-align: center;
            font-weight: 600;
            color: var(--meditation-purple);
            padding: 1rem 0;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            word-wrap: break-word;
        }

        /* Enhanced Calendar Grid */
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1rem;
            width: 100%;
            max-width: 100%;
        }

        /* Improved Calendar Day Box */
        .calendar-day {
            aspect-ratio: 1;
            min-height: 140px;
            background: linear-gradient(135deg, #ffffff 0%, #faf5f0 100%);
            border-radius: 24px;
            border: 3px solid transparent;
            position: relative;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 1.2rem 0.8rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 100%;
        }

        .calendar-day::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--vibrant-pink), var(--lotus-pink));
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
            border-radius: 21px;
        }

        /* Hover effect for entire calendar day when it has festival */
        .calendar-day.has-festival:hover::before {
            opacity: 0.15;
        }

        .calendar-day.has-festival:hover {
            transform: translateY(-12px) scale(1.08);
            border-color: var(--vibrant-pink);
            box-shadow:
                0 20px 50px rgba(255, 60, 101, 0.25),
                0 0 30px rgba(255, 60, 101, 0.2);
        }

        /* Regular hover for days without festivals */
        .calendar-day:not(.has-festival):hover {
            transform: translateY(-8px) scale(1.05);
            border-color: rgba(255, 60, 101, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Day Number */
        .day-number {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--temple-brown);
            margin-bottom: 0.8rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            pointer-events: none;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, var(--peace-blue) 0%, var(--meditation-purple) 100%);
            color: white;
            transform: scale(1.15);
            box-shadow:
                0 0 40px rgba(92, 107, 192, 0.5),
                0 15px 35px rgba(92, 107, 192, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .calendar-day.today .day-number {
            color: white;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }

        .calendar-day.other-month {
            opacity: 0.25;
            transform: scale(0.9);
        }

        /* Enhanced Festival Events */
        .festival-event {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 248, 225, 0.95) 100%);
            border-left: 5px solid var(--sacred-orange);
            border-radius: 15px;
            padding: 0.6rem 0.8rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            line-height: 1.3;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(15px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            min-height: 32px;
            display: flex;
            align-items: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
            pointer-events: none;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            word-wrap: break-word;
        }

        .festival-event.popular {
            background: linear-gradient(135deg, var(--vibrant-pink) 0%, var(--divine-saffron) 100%);
            color: white;
            border-left-color: white;
            animation: popularPulse 2.5s ease-in-out infinite;
            box-shadow: 0 5px 20px rgba(255, 60, 101, 0.3);
        }

        @keyframes popularPulse {

            0%,
            100% {
                box-shadow: 0 5px 20px rgba(255, 60, 101, 0.3);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 8px 30px rgba(255, 60, 101, 0.5);
                transform: scale(1.02);
            }
        }

        .festival-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            pointer-events: none;
            flex-shrink: 0;
        }

        .festival-name {
            font-weight: 700;
            flex: 1;
            font-family: 'Arial', sans-serif;
            pointer-events: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Festival Modal */
        .festival-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem;
        }

        .festival-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 248, 225, 0.95) 100%);
            border-radius: 30px;
            padding: 0;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.2),
                0 0 50px rgba(255, 60, 101, 0.1);
            border: 3px solid rgba(255, 60, 101, 0.3);
            position: relative;
            transform: scale(0.8) translateY(50px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .festival-modal.show .modal-content {
            transform: scale(1) translateY(0);
        }

        /* Modal Header with Image */
        .modal-header {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, var(--vibrant-pink) 0%, var(--divine-saffron) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .festival-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 40px;
            height: 40px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 20px;
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        /* Modal Body */
        .modal-body {
            padding: 2rem;
            text-align: center;
            overflow-y: auto;
            max-height: calc(90vh - 200px);
        }

        .modal-festival-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--meditation-purple);
            margin-bottom: 0.5rem;
            font-family: 'Arial', sans-serif;
            word-wrap: break-word;
        }

        .modal-festival-date {
            color: var(--temple-brown);
            font-size: 1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
            word-wrap: break-word;
        }

        .modal-festival-desc {
            color: var(--temple-brown);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .modal-festival-price {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .current-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--vibrant-pink);
        }

        .original-price {
            font-size: 1.2rem;
            text-decoration: line-through;
            color: #999;
        }

        .discount-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .modal-cta-btn {
            background: linear-gradient(135deg, var(--vibrant-pink) 0%, var(--divine-saffron) 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(255, 60, 101, 0.3);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 300px;
        }

        .modal-cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .modal-cta-btn:hover::before {
            left: 100%;
        }

        .modal-cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 60, 101, 0.4);
        }

        /* Spiritual Patterns */
        .pattern-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            opacity: 0.02;
            background-image:
                repeating-conic-gradient(from 0deg at 50% 50%,
                    var(--vibrant-pink) 0deg 6deg,
                    transparent 6deg 60deg);
            background-size: 40px 40px;
        }

        /* Floating Lotus Animation */
        .floating-lotus {
            position: absolute;
            font-size: 2rem;
            color: var(--lotus-pink);
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .floating-lotus:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-lotus:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-lotus:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }

        .floating-lotus:nth-child(4) {
            bottom: 15%;
            right: 10%;
            animation-delay: 6s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-20px) rotate(120deg);
            }

            66% {
                transform: translateY(-10px) rotate(240deg);
            }
        }

        /* Sacred Mantras */
        .mantra-text {
            text-align: center;
            margin-top: 2rem;
            font-family: 'Noto Sans Devanagari', sans-serif;
            color: var(--meditation-purple);
            font-size: 1.1rem;
            opacity: 0.7;
            animation: mantraGlow 4s ease-in-out infinite alternate;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        @keyframes mantraGlow {
            0% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
                text-shadow: 0 0 10px rgba(142, 36, 170, 0.3);
            }
        }

        /* MOBILE RESPONSIVE FIXES */

        /* Large Tablets */
        @media screen and (max-width: 1024px) {
            .sacred-calendar {
                padding: 1.5rem 1rem;
            }

            .lotus-calendar-card {
                padding: 2rem 1.5rem;
            }

            .calendar-day {
                min-height: 120px;
                padding: 1rem 0.6rem;
            }

            .day-number {
                font-size: 1.4rem;
            }

            .festival-event {
                font-size: 0.8rem;
                padding: 0.5rem 0.7rem;
            }
        }

        /* Tablet Portrait - Critical Fix Point */
        @media screen and (max-width: 768px) {
            .spiritual-container {
                overflow-x: hidden;
                width: 100vw;
                max-width: 100%;
            }

            .sacred-calendar {
                padding: 1rem;
                width: 100%;
                max-width: 100%;
            }

            .lotus-calendar-card {
                padding: 1.5rem 1rem;
                border-radius: 20px;
                margin: 0;
                width: 100%;
                max-width: 100%;
            }

            .calendar-days {
                gap: 0.75rem;
                width: 100%;
            }

            .day-labels {
                gap: 0.75rem;
                width: 100%;
            }

            .calendar-day {
                min-height: 100px;
                border-radius: 16px;
                padding: 0.8rem 0.5rem;
                width: 100%;
            }

            .day-label {
                padding: 0.8rem 0.2rem;
                font-size: 0.9rem;
            }

            .om-symbol {
                font-size: 3rem;
            }

            .sacred-title {
                font-size: 2rem;
            }

            .month-name {
                font-size: 1.8rem;
            }

            .nav-lotus-btn {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }

            .day-number {
                font-size: 1.3rem;
                margin-bottom: 0.6rem;
            }

            .festival-event {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
                border-radius: 12px;
                min-height: 28px;
                width: 100%;
            }

            .festival-icon {
                font-size: 1rem;
                margin-right: 0.4rem;
            }

            .modal-content {
                width: 95vw;
                border-radius: 25px;
                max-width: 95vw;
            }

            .modal-body {
                padding: 1.5rem 1rem;
            }

            .modal-header {
                height: 180px;
            }

            .festival-image {
                width: 100px;
                height: 100px;
            }

            .modal-festival-name {
                font-size: 1.6rem;
            }

            .modal-festival-price {
                gap: 0.8rem;
            }

            .current-price {
                font-size: 1.6rem;
            }

            .original-price {
                font-size: 1.1rem;
            }
        }

        /* Mobile Landscape - Key Fix */
        @media screen and (max-width: 640px) {
            .spiritual-container {
                width: 100vw;
                max-width: 100vw;
                overflow-x: hidden;
            }

            .sacred-calendar {
                padding: 1rem 0.5rem;
                width: 100%;
                max-width: 100%;
            }

            .lotus-calendar-card {
                padding: 1rem;
                margin: 0 0.25rem;
                width: calc(100% - 0.5rem);
                max-width: calc(100% - 0.5rem);
            }

            .calendar-days {
                gap: 0.5rem;
                width: 100%;
            }

            .day-labels {
                gap: 0.5rem;
                width: 100%;
            }

            .calendar-day {
                min-height: 85px;
                padding: 0.6rem 0.3rem;
                border-radius: 14px;
            }

            .day-number {
                font-size: 1.2rem;
                margin-bottom: 0.4rem;
            }

            .festival-event {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
                margin-top: 0.3rem;
                min-height: 24px;
            }

            .festival-name {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }

            .festival-icon {
                font-size: 0.9rem;
                margin-right: 0.3rem;
            }

            .sacred-nav {
                margin-bottom: 1.5rem;
                padding: 0.5rem 0;
            }

            .nav-lotus-btn {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }

            .month-display {
                margin: 0 0.5rem;
            }

            .month-name {
                font-size: 1.6rem;
            }

            .month-year {
                font-size: 1rem;
            }

            .day-label {
                font-size: 0.8rem;
                padding: 0.6rem 0.1rem;
            }

            .modal-content {
                max-height: 85vh;
                width: 98vw;
                max-width: 98vw;
            }

            .modal-header {
                height: 160px;
            }

            .festival-image {
                width: 80px;
                height: 80px;
            }

            .modal-body {
                padding: 1.2rem 1rem;
            }

            .modal-festival-name {
                font-size: 1.4rem;
            }

            .modal-festival-desc {
                font-size: 1rem;
            }

            .current-price {
                font-size: 1.5rem;
            }

            .modal-cta-btn {
                padding: 0.9rem 1.5rem;
                font-size: 1rem;
                width: 100%;
                max-width: 280px;
            }
        }

        /* Mobile Portrait - Most Critical */
        @media screen and (max-width: 480px) {
            .spiritual-container {
                width: 100vw;
                max-width: 100vw;
                padding: 0;
                overflow-x: hidden;
            }

            .sacred-calendar {
                padding: 0.8rem 0.5rem;
                width: 100%;
                max-width: 100%;
            }

            .lotus-calendar-card {
                padding: 1rem 0.8rem;
                margin: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 20px;
            }

            .calendar-days {
                gap: 0.4rem;
                width: 100%;
            }

            .day-labels {
                gap: 0.4rem;
                margin-bottom: 0.8rem;
                width: 100%;
            }

            .calendar-day {
                min-height: 75px;
                padding: 0.5rem 0.2rem;
                border-radius: 12px;
            }

            .day-number {
                font-size: 1.1rem;
                margin-bottom: 0.3rem;
            }

            .festival-event {
                font-size: 0.65rem;
                padding: 0.25rem 0.4rem;
                margin-top: 0.2rem;
                min-height: 22px;
                border-radius: 8px;
            }

            .festival-name {
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .festival-icon {
                font-size: 0.8rem;
                margin-right: 0.2rem;
            }

            .om-symbol {
                font-size: 2.5rem;
            }

            .sacred-title {
                font-size: 1.8rem;
            }

            .sanskrit-blessing {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .nav-lotus-btn {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .month-name {
                font-size: 1.4rem;
            }

            .month-year {
                font-size: 0.9rem;
            }

            .day-label {
                font-size: 0.75rem;
                padding: 0.5rem 0;
            }

            .mantra-text {
                font-size: 1rem;
                margin-top: 1.5rem;
                padding: 0 1rem;
                line-height: 1.6;
            }

            .modal-content {
                width: 100vw;
                max-width: 100vw;
                max-height: 95vh;
                border-radius: 20px;
                margin: 0;
            }

            .modal-header {
                height: 140px;
            }

            .festival-image {
                width: 70px;
                height: 70px;
            }

            .modal-close {
                width: 35px;
                height: 35px;
                font-size: 18px;
                top: 10px;
                right: 15px;
            }

            .modal-body {
                padding: 1rem 1rem;
            }

            .modal-festival-name {
                font-size: 1.3rem;
                margin-bottom: 0.4rem;
            }

            .modal-festival-date {
                font-size: 0.9rem;
                margin-bottom: 0.8rem;
            }

            .modal-festival-desc {
                font-size: 0.95rem;
                margin-bottom: 1.2rem;
                line-height: 1.5;
            }

            .modal-festival-price {
                gap: 0.5rem;
                margin-bottom: 1.2rem;
                flex-direction: column;
                align-items: center;
            }

            .current-price {
                font-size: 1.4rem;
            }

            .original-price {
                font-size: 1rem;
            }

            .discount-badge {
                font-size: 0.8rem;
                padding: 0.25rem 0.6rem;
            }

            .modal-cta-btn {
                padding: 0.8rem 1.2rem;
                font-size: 0.95rem;
                border-radius: 20px;
                width: 100%;
                max-width: 100%;
            }
        }

        /* Extra Small Mobile - Ultra Compact */
        @media screen and (max-width: 360px) {
            .lotus-calendar-card {
                padding: 0.8rem 0.5rem;
            }

            .calendar-days {
                gap: 0.3rem;
            }

            .day-labels {
                gap: 0.3rem;
            }

            .calendar-day {
                min-height: 65px;
                padding: 0.4rem 0.1rem;
            }

            .day-number {
                font-size: 1rem;
            }

            .festival-event {
                font-size: 0.6rem;
                padding: 0.2rem 0.3rem;
                min-height: 20px;
            }

            .festival-name {
                display: none;
                /* Hide text completely, show only icon */
            }

            .nav-lotus-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .month-name {
                font-size: 1.2rem;
            }

            .day-label {
                font-size: 0.7rem;
            }

            .modal-body {
                padding: 0.8rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .calendar-day.has-festival:hover::before {
                opacity: 0;
            }

            .calendar-day.has-festival:hover {
                transform: none;
            }

            .calendar-day:not(.has-festival):hover {
                transform: none;
            }

            .nav-lotus-btn:hover {
                transform: none;
            }

            .modal-close:hover {
                transform: none;
            }

            .modal-cta-btn:hover {
                transform: none;
            }
        }

        /* Landscape orientation on small screens */
        @media screen and (max-height: 600px) and (orientation: landscape) {
            .om-header {
                margin-bottom: 1.5rem;
            }

            .om-symbol {
                font-size: 2.5rem;
            }

            .sacred-title {
                font-size: 1.8rem;
            }

            .sanskrit-blessing {
                font-size: 0.95rem;
            }

            .mantra-text {
                margin-top: 1rem;
                font-size: 0.95rem;
            }

            .modal-content {
                max-height: 95vh;
            }

            .modal-header {
                height: 120px;
            }

            .festival-image {
                width: 60px;
                height: 60px;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .mandala-bg,
            .om-symbol,
            .floating-lotus,
            .mantra-text,
            .festival-event.popular {
                animation: none;
            }
        }

        /* Print styles */
        @media print {

            .floating-lotus,
            .pattern-overlay,
            .mandala-bg {
                display: none;
            }

            .lotus-calendar-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }

            .calendar-day {
                box-shadow: none;
                border: 1px solid #eee;
            }
        }
    </style>

    <script>
        // Global variables - declared once
        let festivals = [];
        let currentDate = new Date(2025, 9, 1); // September 2025
        let modalTimeout;

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const hindiMonths = ["जनवरी", "फरवरी", "मार्च", "अप्रैल", "मई", "जून",
            "जुलाई", "अगस्त", "सितम्बर", "अक्टूबर", "नवम्बर", "दिसम्बर"
        ];

        // Load festivals from JSON file - single function
        async function loadFestivals() {
            try {
                const response = await fetch("/json/festivals.json");

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                festivals = data;
                console.log("Festivals loaded:", festivals.length);

                // Render calendar after data is loaded
                renderCalendar();
            } catch (error) {
                console.error("Failed to load festivals.json:", error);

                // Fallback: Use empty array if JSON fails to load
                festivals = [];
                renderCalendar();

                // Show user-friendly error
                showNotification("Unable to load festival data. Please refresh the page.", "error");
            }
        }

        // Show notification to user
        function showNotification(message, type = "info") {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                      position: fixed;
                      top: 20px;
                      right: 20px;
                      padding: 12px 20px;
                      border-radius: 6px;
                      color: white;
                      font-weight: bold;
                      z-index: 10000;
                      animation: slideIn 0.3s ease-out;
                    `;

            if (type === "error") {
                notification.style.backgroundColor = "#e74c3c";
            } else {
                notification.style.backgroundColor = "#3498db";
            }

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Render calendar function - single unified function
        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            document.getElementById('monthName').textContent = hindiMonths[month];
            document.getElementById('yearDisplay').textContent = year;

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();

            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';

            // Create today string using local date components to avoid timezone issues
            const today = new Date();
            const todayYear = today.getFullYear();
            const todayMonth = String(today.getMonth() + 1).padStart(2, '0');
            const todayDay = String(today.getDate()).padStart(2, '0');
            const todayString = `${todayYear}-${todayMonth}-${todayDay}`;

            // Previous month days
            const prevMonth = new Date(year, month - 1, 0);
            const prevMonthDays = prevMonth.getDate();

            for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                const date = new Date(year, month - 1, prevMonthDays - i);
                calendarDays.appendChild(createDayElement(prevMonthDays - i, true, date, todayString));
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                calendarDays.appendChild(createDayElement(day, false, date, todayString));
            }

            // Next month days
            const remainingCells = 42 - (startingDayOfWeek + daysInMonth);
            for (let day = 1; day <= remainingCells; day++) {
                const date = new Date(year, month + 1, day);
                calendarDays.appendChild(createDayElement(day, true, date, todayString));
            }
        }

        // Create day element function - with timezone fix
        // Create day element function - with ISO date format handling
        function createDayElement(dayNumber, isOtherMonth, date, todayString) {
            const dayDiv = document.createElement('div');
            dayDiv.className = `calendar-day ${isOtherMonth ? 'other-month' : ''}`;

            // Fix: Create date string using local date components to avoid timezone issues
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const dateString = `${year}-${month}-${day}`;

            // Check if this is today
            if (dateString === todayString && !isOtherMonth) {
                dayDiv.classList.add('today');
            }

            const dayNumberDiv = document.createElement('div');
            dayNumberDiv.className = 'day-number';
            dayNumberDiv.textContent = dayNumber;
            dayDiv.appendChild(dayNumberDiv);

            // Find matching festival from JSON - handle both simple date and ISO date formats
            const festival = festivals.find(f => {
                let festivalDateString;

                // Check if the date includes time information (ISO format)
                if (f.date.includes('T')) {
                    // Parse ISO date and extract just the date part
                    const festivalDate = new Date(f.date);
                    const fYear = festivalDate.getFullYear();
                    const fMonth = String(festivalDate.getMonth() + 1).padStart(2, '0');
                    const fDay = String(festivalDate.getDate()).padStart(2, '0');
                    festivalDateString = `${fYear}-${fMonth}-${fDay}`;
                } else {
                    // Simple date format (YYYY-MM-DD)
                    festivalDateString = f.date;
                }

                return festivalDateString === dateString;
            });

            if (festival && !isOtherMonth) {
                // Add festival display
                dayDiv.classList.add('has-festival');

                const festivalDiv = document.createElement('div');
                festivalDiv.className = `festival-event ${festival.popular ? 'popular' : ''}`;
                festivalDiv.innerHTML = `
      <span class="festival-icon">${festival.icon}</span>
      <span class="festival-name">${festival.name}</span>
    `;
                dayDiv.appendChild(festivalDiv);

                // Event listeners for hover and click
                dayDiv.addEventListener('mouseenter', (e) => {
                    showFestivalModal(festival, e);
                });

                dayDiv.addEventListener('mouseleave', (e) => {
                    hideFestivalModal();
                });

                dayDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showFestivalModal(festival, e, true);
                });
            }

            return dayDiv;
        }

        // Show festival modal
        // Show festival modal - updated to handle ISO dates
        function showFestivalModal(festival, event, isClick = false) {
            clearTimeout(modalTimeout);

            const modal = document.getElementById('festivalModal');
            const modalImage = document.getElementById('modalFestivalImage');
            const modalName = document.getElementById('modalFestivalName');
            const modalDate = document.getElementById('modalFestivalDate');
            const modalDesc = document.getElementById('modalFestivalDesc');
            const modalCurrentPrice = document.getElementById('modalCurrentPrice');
            const modalOriginalPrice = document.getElementById('modalOriginalPrice');
            const modalDiscount = document.getElementById('modalDiscount');

            modalImage.src = festival.image;
            modalImage.onerror = function() {
                this.src = '/images/default-festival.jpg'; // Fallback image
            };

            modalName.textContent = festival.name;

            // Handle both ISO date format and simple date format
            let festivalDate;
            if (festival.date.includes('T')) {
                // ISO date format with time
                festivalDate = new Date(festival.date);
            } else {
                // Simple date format (YYYY-MM-DD)
                const dateParts = festival.date.split('-');
                festivalDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
            }

            modalDate.textContent = festivalDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            modalDesc.textContent = festival.description;
            modalCurrentPrice.textContent = `₹${festival.price}`;
            modalOriginalPrice.textContent = `₹${festival.originalPrice}`;
            modalDiscount.textContent = festival.discount;

            if (isClick) {
                modal.style.pointerEvents = 'auto';
                modal.classList.add('show');
            } else {
                modal.style.pointerEvents = 'none';
                modalTimeout = setTimeout(() => {
                    modal.classList.add('show');
                }, 500);
            }
        }

        // Hide festival modal
        function hideFestivalModal() {
            clearTimeout(modalTimeout);
            const modal = document.getElementById('festivalModal');
            if (modal.style.pointerEvents === 'none') {
                modal.classList.remove('show');
            }
        }

        // Event listeners - all in one place
        document.addEventListener('DOMContentLoaded', function() {
            // Load festivals and render calendar
            loadFestivals();

            // Navigation buttons
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            // Modal close functionality
            document.getElementById('modalClose').addEventListener('click', () => {
                document.getElementById('festivalModal').classList.remove('show');
            });

            document.getElementById('festivalModal').addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    document.getElementById('festivalModal').classList.remove('show');
                }
            });

            // Add to cart functionality
            document.getElementById('modalCtaBtn').addEventListener('click', () => {
                alert('Item added to cart!');
                document.getElementById('festivalModal').classList.remove('show');
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            } else if (e.key === 'ArrowRight') {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            } else if (e.key === 'Escape') {
                document.getElementById('festivalModal').classList.remove('show');
            }
        });
    
        // === Scroll & Modal Lock Helpers START ===
        function lockBodyScroll(lock) {
            if (lock) {
                const sb = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = sb > 0 ? sb + 'px' : '';
            } else {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }

        function openFestivalModal() {
            const modal = document.getElementById('festivalModal');
            if (!modal) return;
            modal.classList.add('show');
            lockBodyScroll(true);
        }
        function closeFestivalModal() {
            const modal = document.getElementById('festivalModal');
            if (!modal) return;
            modal.classList.remove('show');
            lockBodyScroll(false);
        }

        document.addEventListener('DOMContentLoaded', function(){
            // precise auto-scroll when linked with #calendar
            if (window.location.hash === '#calendar') {
                const el = document.querySelector('.sacred-calendar');
                if (el) {
                    const y = el.getBoundingClientRect().top + window.pageYOffset - 100;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }
            const closeBtn = document.getElementById('modalClose');
            if (closeBtn) closeBtn.addEventListener('click', closeFestivalModal);
            const modal = document.getElementById('festivalModal');
            if (modal) modal.addEventListener('click', (e)=>{ if(e.target===e.currentTarget) closeFestivalModal(); });
        });
        // === Scroll & Modal Lock Helpers END ===
</script>
@endsection
