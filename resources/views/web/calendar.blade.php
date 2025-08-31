@extends('layouts.web')

@section('title', __('app.islamic_calendar'))

@push('styles')
    <style>
        :root {
            --col-min-width: 96px;
        }

        .calendar-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 0px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(var(--col-min-width), 1fr));
        }

        .weekday-head {
            background: #4e2d4585;
            font-weight: 700;
            text-align: center;
            padding: 0.55rem 0.4rem;
            border: 1px solid rgba(0, 0, 0, 0.04);
            white-space: nowrap;
            font-size: 0.95rem;
        }

        .day-cell {
            min-height: 84px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            padding: 6px;
            background: #f9f5ef;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .day-number {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .hijri {
            font-size: 0.78rem;
            color: #6c757d;
            margin-top: 4px;
        }

        .today {
            box-shadow: 0 0 0 2px #4E2D452e inset;
            border-left: 4px solid #4E2D45;
        }

        .muted-day {
            background: #4E2D452e !important;
        }

        .text-muted {
            color: #8c949b !important;
        }

        @media (max-width: 420px) {
            :root {
                --col-min-width: 45px;
            }

            .day-cell {
                min-height: 66px;
                padding: 4px;
            }

            .weekday-head {
                font-size: 0.82rem;
                padding: 0.45rem 0.25rem;
            }

            .day-number,
            .weekday-small {
                font-size: 12px;
            }

            .hijri {
                font-size: 9px;
            }
        }

        .card {
            margin: auto;
            background: #eaddc43a;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .date {
            text-align: center;
            font-size: 14px;
            color: #4e2d45ca;
        }

        .prayer-time {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #4E2D452e;
        }

        .prayer-time:last-child {
            border-bottom: none;
        }

        .name {
            font-weight: bold;
            color: #4E2D45
        }

        .current-prayer {
            background: #4E2D452e;
            border-radius: 2px;
        }

        .location {
            text-align: center;
            font-size: 13px;
            color: #4e2d4583;
            margin-bottom: 5px;
        }

        .countdown {
            text-align: center;
            font-size: 14px;
            padding: 5px;
            color: #4E2D45;
            margin-bottom: 10px;
            border: 2px solid #4E2D45;
            border-radius: 12px
        }

        .active-day {
            border: 1px solid #4E2D45;
        }
    </style>
@endpush

@section('content')
    <x-web.container class="notranslate">
        <div class="mb-2 index-card row">
            <div class="d-flex justify-content-between align-items-center mb-3 col-12 col-md-12">
                <div>
                    <button id="prevBtn" class="btn btn-outline-primary btn-sm">&larr; Prev</button>
                </div>

                <div class="text-center">
                    <h3 id="monthLabel" class="mb-0"></h3>
                    <small id="yearLabel" class="text-muted"></small>
                </div>

                <div>
                    <button id="nextBtn" class="btn btn-outline-primary btn-sm">Next &rarr;</button>
                </div>
            </div>

            <div class="calendar-scroll col-12 col-md-8 mb-2">
                <div id="calendarGrid" class="calendar-grid"></div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <h2 class="text-center mb-2 text-primary">{{ __('app.prayer_times') }}</h2>
                    <div class="date" id="date"></div>
                    <div class="location" id="locationName">{{ __('app.location') }}</div>
                    <div class="countdown" id="countdown">{{ __('app.loading') }}</div>

                    <div id="prayerTimes"></div>
                </div>
            </div>
        </div>
    </x-web.container>
@endsection

@push('scripts')
    <script>
        LATITUDE = 21.3891;
        LONGITUDE = 39.8579;
        LOCALE = "en-US";

        /** Format Hijri Date */
        function getHijriDate(date, locale = LOCALE) {
            const calendars = ['islamic-umalqura', 'islamic'];

            for (const cal of calendars) {
                try {
                    return new Intl.DateTimeFormat(`${LOCALE}-u-ca-${cal}`, {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    }).format(date);
                } catch {}
            }

            return '';
        };

        (function() {
            const grid = document.getElementById('calendarGrid');
            const monthLabel = document.getElementById('monthLabel');
            const yearLabel = document.getElementById('yearLabel');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let viewDate = new Date();

            /** Get weekday names starting Sunday */
            const getWeekdayNames = (locale) => {
                let start = new Date();
                while (start.getDay() !== 0) start.setDate(start.getDate() - 1);
                return Array.from({
                        length: 7
                    }, (_, i) =>
                    new Date(start.getFullYear(), start.getMonth(), start.getDate() + i)
                    .toLocaleString(locale, {
                        weekday: 'short'
                    })
                );
            };

            /** Render Calendar */
            const renderCalendar = () => {
                let lang = document.getElementById('languageDropdown').textContent.trim().toLowerCase();
                let locale = 'en-US';

                if (lang == 'ml') {
                    locale = 'ml-IN';
                } else if (lang == 'hi') {
                    locale = 'hi-IN';
                } else if (lang == 'ar') {
                    locale = 'ar-SA';
                }
                LOCALE = locale;

                const year = viewDate.getFullYear();
                const month = viewDate.getMonth();

                monthLabel.textContent = viewDate.toLocaleString(locale, {
                    month: 'long'
                });
                yearLabel.textContent = viewDate.toLocaleString(locale, {
                    year: 'numeric'
                });

                const weekNames = getWeekdayNames(locale);
                let html = weekNames.map(name => `<div class="weekday-head">${name}</div>`).join('');

                const firstDay = new Date(year, month, 1);
                const startWeekday = firstDay.getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const prevMonthDays = new Date(year, month, 0).getDate();
                const today = new Date();

                // Add empty cells before the first day for alignment
                for (let i = 0; i < startWeekday; i++) {
                    const day = prevMonthDays - (startWeekday - 1 - i);
                    const cellDate = new Date(year, month - 1, day);
                    const hijri = getHijriDate(cellDate, locale);

                    html += `
                        <div class="day-cell muted-day">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="day-number text-muted">${cellDate.getDate()}</div>
                                <div class="weekday-small text-muted">${cellDate.toLocaleString(locale, { weekday: 'narrow' })}</div>
                            </div>
                            <div class="hijri">${hijri}</div>
                        </div>`;
                }

                // Current month days
                for (let dayNumber = 1; dayNumber <= daysInMonth; dayNumber++) {
                    let cellDate = new Date(year, month, dayNumber);
                    const isToday = cellDate.toDateString() === today.toDateString();
                    const dt = cellDate.toDateString()
                    const hijri = getHijriDate(cellDate, locale);

                    html += `
                        <div class="day-cell ${isToday ? 'today' : ''} ${dt}" onclick="fetchPrayerTimes('${dt}')" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="day-number">${dayNumber}</div>
                                <div class="weekday-small">${cellDate.toLocaleString(locale, { weekday: 'narrow' })}</div>
                            </div>
                            <div class="hijri">${hijri}</div>
                        </div>`;
                }

                // Fill remaining cells for full weeks
                const totalCells = startWeekday + daysInMonth;
                const remainingCells = (7 - (totalCells % 7)) % 7;
                const nextMonthDate = new Date(year, month + 1, 1);

                for (let i = 0; i < remainingCells; i++) {
                    const cellDate = new Date(nextMonthDate.getFullYear(), nextMonthDate.getMonth(), i + 1);
                    const hijri = getHijriDate(cellDate, locale);

                    html += `
                        <div class="day-cell muted-day">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="day-number">${cellDate.getDate()}</div>
                                <div class="weekday-small">${cellDate.toLocaleString(locale, { weekday: 'narrow' })}</div>
                            </div>
                            <div class="hijri">${hijri}</div>
                        </div>`;
                }

                grid.innerHTML = html;
            };

            // Event Listeners
            prevBtn.addEventListener('click', () => {
                viewDate.setMonth(viewDate.getMonth() - 1);
                renderCalendar();
            });

            nextBtn.addEventListener('click', () => {
                viewDate.setMonth(viewDate.getMonth() + 1);
                renderCalendar();
            });

            // Initial render
            renderCalendar();
        })();
    </script>

    <script>
        let prayerOrder = ["Fajr", "Sunrise", "Dhuhr", "Asr", "Maghrib", "Isha"];
        let trPrayerName = {
            Fajr: "{{ __('app.Fajr') }}",
            Sunrise: "{{ __('app.Sunrise') }}",
            Dhuhr: "{{ __('app.Dhuhr') }}",
            Asr: "{{ __('app.Asr') }}",
            Maghrib: "{{ __('app.Maghrib') }}",
            Isha: "{{ __('app.Isha') }}"
        }
        let prayerTimings = {};

        function fetchPrayerTimes(selectedDate = null) {
            const today = selectedDate ? new Date(selectedDate) : new Date();
            const day = today.getDate();
            const month = today.getMonth() + 1;
            const year = today.getFullYear();
            const hijri = getHijriDate(today);

            const weekday = today.toLocaleString(LOCALE, {
                weekday: 'long',
            });

            const monthLoc = today.toLocaleString(LOCALE, {
                month: 'long'
            });

            document.getElementById('date').innerHTML = `${weekday}, ${day} ${monthLoc} ${year} / ${hijri}`;

            document.querySelectorAll('.day-cell').forEach(cell =>
                cell.classList.remove('active-day')
            );

            if (new Date().toLocaleDateString() !== today.toLocaleDateString()) {
                const selectedCell = document.getElementsByClassName(selectedDate)[0];
                if (selectedCell) {
                    selectedCell.classList.add('active-day');
                }
            }

            fetch(
                    `https://api.aladhan.com/v1/timings/${day}-${month}-${year}?latitude=${LATITUDE}&longitude=${LONGITUDE}&method=2`
                )
                .then(response => response.json())
                .then(data => {
                    const timings = data.data.timings;

                    let html = '';
                    prayerTimings = timings; // store globally

                    const now = new Date();
                    let currentPrayerIndex = -1;

                    const prayerTimesArr = prayerOrder.map(prayer => {
                        const [hour, minute] = timings[prayer].split(':').map(Number);
                        const prayerDate = new Date(year, month - 1, day, hour, minute);
                        return {
                            name: trPrayerName[prayer],
                            time: timings[prayer],
                            date: prayerDate
                        };
                    });

                    for (let i = 0; i < prayerTimesArr.length; i++) {
                        const prayerStart = prayerTimesArr[i].date;
                        const prayerEnd = (i === prayerTimesArr.length - 1) ?
                            new Date(year, month - 1, day, 23, 59, 59) :
                            prayerTimesArr[i + 1].date;

                        if (now >= prayerStart && now < prayerEnd) {
                            currentPrayerIndex = i;
                            break;
                        }
                    }

                    prayerTimesArr.forEach((p, index) => {
                        html += `
                        <div class="prayer-time ${index === currentPrayerIndex ? 'current-prayer' : ''}">
                            <div class="name">${p.name}</div>
                            <div class="time">${p.time}</div>
                        </div>`;
                    });

                    document.getElementById('prayerTimes').innerHTML = html;
                    fetchLocationName(LATITUDE, LONGITUDE);
                    updateCountdown(); // first run
                    setInterval(updateCountdown, 1000); // update every second
                })
                .catch(err => {
                    document.getElementById('prayerTimes').innerHTML = "<p>Error fetching prayer times</p>";
                    console.error(err);
                });
        }

        function updateCountdown() {
            const now = new Date();
            let nextPrayer = null;
            let nextPrayerTime = null;

            for (let prayer of prayerOrder) {
                let [hours, minutes] = prayerTimings[prayer].split(":").map(Number);
                let prayerDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
                if (prayerDate > now) {
                    nextPrayer = trPrayerName[prayer];
                    nextPrayerTime = prayerDate;
                    break;
                }
            }

            if (!nextPrayer) { // next day fajr
                let [hours, minutes] = prayerTimings["Fajr"].split(":").map(Number);
                nextPrayer = "{{ __('app.Fajr') }}";
                nextPrayerTime = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, hours, minutes);
            }

            let diff = nextPrayerTime - now;
            let hoursLeft = Math.floor(diff / (1000 * 60 * 60));
            let minutesLeft = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let secondsLeft = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML =
                `{{ __('app.next') }}: ${nextPrayer} in ${hoursLeft}h ${minutesLeft}m ${secondsLeft}s`;
        }

        function fetchLocationName(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(res => res.json())
                .then(data => {
                    let display_name = data.display_name || "Unknown location";
                    document.getElementById('locationName').innerText = display_name;
                })
                .catch(() => {
                    document.getElementById('locationName').innerText = "Location unavailable";
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    LATITUDE = pos.coords.latitude;
                    LONGITUDE = pos.coords.longitude;
                    fetchPrayerTimes();
                },
                () => fetchPrayerTimes()
            );
        } else {
            fetchPrayerTimes();
        }
    </script>
@endpush
