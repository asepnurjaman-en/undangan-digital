if ($("#countdown").length > 0) {
    (function () {
        const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;
        let thetime = document.getElementById('countdown').getAttribute('data-time');
        const countDown = new Date(thetime).getTime(),
        x = setInterval(function() {
            const now = new Date().getTime(),
            distance = countDown - now;
            document.getElementById("days").innerText = Math.floor(distance / (day)),
            document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
            document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
            document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
            //do something later when date is reached
                if (distance < 0) {
                    document.getElementById("countdown").style.display = "none";
                    clearInterval(x);
                    console.log('a');
                }
            //seconds
        }, 0);
    }());
}