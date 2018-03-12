function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor(t / (1000 * 60 * 60));
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);

  if (!clock) {
    return;
  }
  var hoursSpan = clock.querySelector('#countdown-hours');
  var minutesSpan = clock.querySelector('#countdown-minutes');
  var secondsSpan = clock.querySelector('#countdown-seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);
    hours = ('0' + t.hours).slice(-2);
    minutes = ('0' + t.minutes).slice(-2);
    seconds = ('0' + t.seconds).slice(-2);

    hoursSpan.innerHTML = hours;
    minutesSpan.innerHTML = minutes;
    secondsSpan.innerHTML = seconds;

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);

jQuery(document).ready(function($) {
  var deadline = '2016-02-28T00:00:01-05:00';
  initializeClock('countdown-clock', deadline);
});

