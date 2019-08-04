function updateTime() {
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );   
    var currentHoursleadingzero = currentHours > 9 ? currentHours : '0' + currentHours;
    var currentMinutes = currentTime.getMinutes ( );
    var currentMinutesleadingzero = currentMinutes > 9 ? currentMinutes : '0' + currentMinutes; // If the number is 9 or below we add a 0 before the number.
    var currentDate = currentTime.getDate ( );
    var currentDateSuffix = (currentDate == 1 
                            || currentDate == 2 
                            || currentDate == 21 
                            || currentDate == 22 
                            || currentDate == 31) 
                            ? ":a" : ":e"; 

    var weekday = new Array(7);
    weekday[0] = "Söndag";
    weekday[1] = "Måndag";
    weekday[2] = "Tisdag";
    weekday[3] = "Onsdag";
    weekday[4] = "Torsdag";
    weekday[5] = "Fredag";
    weekday[6] = "Lördag";
    var currentDay = weekday[currentTime.getDay()]; 

    var actualmonth = new Array(12);
    actualmonth[0] = "Januari";
    actualmonth[1] = "Februari";
    actualmonth[2] = "Mars";
    actualmonth[3] = "April";
    actualmonth[4] = "Maj";
    actualmonth[5] = "Juni";
    actualmonth[6] = "Juli";
    actualmonth[7] = "Augusti";
    actualmonth[8] = "September";
    actualmonth[9] = "Oktober";
    actualmonth[10] = "November";
    actualmonth[11] = "December";
    var currentMonth = actualmonth[currentTime.getMonth ()];
    
    var currentTimeString = "<h1>" + currentHoursleadingzero + ":" + currentMinutesleadingzero + "</h1><h2>" + currentDay + " " + currentDate + currentDateSuffix + " " + currentMonth + "</h2>";
    document.getElementById("clock").innerHTML = currentTimeString;
}
document.addEventListener("DOMContentLoaded", function() {
    updateTime();
});
window.setInterval(updateTime, 1000);