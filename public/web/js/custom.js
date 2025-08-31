function handleShare() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: document.title,
            text: "Check out this page:",
            url: url,
        });
    } else {
        navigator.clipboard
            .writeText(url)
            .then(() => alert("URL copied to clipboard!"))
            .catch(() => alert("Copy failed. Please copy manually."));
    }
}

function toArabicNumber(number) {
    const arabicDigits = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
    return String(number)
        .split("")
        .map((d) => arabicDigits[d] || d)
        .join("");
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".ar-number").forEach((span) => {
        const number = span.textContent.trim();
        span.textContent = toArabicNumber(number);
    });

    toastr.options = {
        closeButton: true, // Show the close (X) icon
        positionClass: "toast-bottom-right",
        timeOut: 5000, // Auto close in 5s
        extendedTimeOut: 2000, // Extra time if hovered
        escapeHtml: true,
        progressBar: true,
    };
});

function toggleLoader(show) {
    $('#pageLoader').toggleClass("d-none", !show);
}
