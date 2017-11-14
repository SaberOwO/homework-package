/*
    CSE 154
    Assignment 8
    Yu Fu

    weather.js

    This program allows users to search for the current weather information 
    and weather forcast of given city. It will show not found or other error message
    when error occurs.
 */

(function() {
    "use strict";

    var temperatureInFuture;    /* Array of temperatures from now to future hours. */

    /*  When the page loads:
        1. Clear and disable the input box.
        2. Fetch the list of all cities, fill the datalist, then enable input.
     */
    window.onload = function() {
        clearAndDisableInput();
        fetchCityList();
        document.getElementById("graph").style.display = "none";
        document.getElementById("search").onclick = showResult;
        document.getElementById("precip").onclick = showPrecip;
        document.getElementById("temp").onclick = showTemp;
        document.getElementById("temps").oninput = changeTemp;
        temperatureInFuture = [];
    };

    /* Load data and show the result area. */
    function showResult() {
        clearAll();
        document.getElementById("resultsarea").style.display = "block";
        var cityName = document.getElementById("citiesinput").value;
        fetchData(cityName);
        fetchForecast(cityName);
    }

    /* Show the precipitation area. */
    function showPrecip() {
        document.getElementById("graph").style.display = "block";
        document.getElementById("temps").style.display = "none";
        loadTempData(0);
    }

    /* Show the clider to control temperature. */
    function showTemp() {
        document.getElementById("graph").style.display = "none";
        document.getElementById("temps").style.display = "block";
        document.getElementById("slider").value = 0;
    }

    /* Clear and disable the input box. */
    function clearAndDisableInput() {
        var inputBox = document.getElementById("citiesinput");
        inputBox.value = "";
        inputBox.disabled = true;
    }

    /* Send ajax requests. */
    function fetchInfo(requestParas, onloadFunc) {
        var ajax = new XMLHttpRequest();
        ajax.onload = onloadFunc;
        var requestHtml = "https://webster.cs.washington.edu/cse154/weather.php?" + requestParas;
        ajax.open("GET", requestHtml, true);
        ajax.send();
    }

    /* Fetch the list of all cities. */
    function fetchCityList() {
        fetchInfo("mode=cities", loadCityList);
    }

    /* Fill the datalist with cities, then enable input. */
    function loadCityList() {
        if (this.status == 200) {
            var cityList = document.getElementById("cities");
            var cities = this.responseText.split("\n");
            for (var i = 0; i < cities.length; i++) {
                var city = document.createElement("OPTION");
                city.setAttribute("value", cities[i]);
                cityList.appendChild(city);
            }
            document.getElementById("citiesinput").disabled = false;
        } else {
            addError("ERROR: Fail to load city list\tERRORCODE: " + this.status);
        }
        document.getElementById("loadingnames").style.display = "none";
    }

    /* Fetch the data (city, precipitation and temperature) of given city. */
    function fetchData(city) {
        fetchInfo("mode=rain&city=" + city, loadData);
    }

    /* load the city data, precipitation data, and temperature data. */
    function loadData() {
        if (this.status == 200) {
            var weatherData = this.responseXML;

            /* Load variables related to city data. */
            var name = weatherData.querySelector("location name").textContent;
            var dNow = new Date();
            var currentTime = dNow.toString();
            var currentDesc = "";

            /* Load variables related to precipitation data. */
            var precGraph = document.getElementById("graph");
            var row = document.createElement("TR");
            precGraph.appendChild(row);

            var times = weatherData.querySelectorAll("forecast time");
            var tempRecording = false;
            for (var i = 0; i < times.length; i++) {
                var dFrom = new Date(times[i].getAttribute("from"));
                var dTo   = new Date(times[i].getAttribute("to"));

                if (dFrom.getTime() < dNow.getTime() && dTo.getTime() > dNow.getTime()) {
                    var currentDesc = times[i].querySelector("symbol").getAttribute("name");
                    tempRecording = true;
                }

                /* get precipitation data */
                if (i < 7) {
                    var rainChance = times[i].querySelector("clouds").getAttribute("all");
                    var td = document.createElement("TD");
                    var d = document.createElement("DIV");
                    d.innerHTML = rainChance + "%";
                    d.style.height = rainChance + "px";
                    td.appendChild(d);
                    row.appendChild(td);
                }

                /* Record temperature */
                if (tempRecording) {
                    var temp = times[i].querySelector("temperature").getAttribute("value");
                    temperatureInFuture.push(Math.round(temp));
                }
            }

            /* Show current temperature */
            loadTempData(0);

            /* Show the city data. */
            var location = document.getElementById("location");
            location.appendChild(createParagraph(name));
            location.appendChild(createParagraph(currentTime));
            location.appendChild(createParagraph(currentDesc));

            document.getElementById("buttons").style.display = "block";
            showTemp();
            
        } else if (this.status == 410) {
            document.getElementById("nodata").style.display = "block";  
        } else {
            addError("ERROR: Fail to load weather data\tERRORCODE: " + this.status);
        }

        /* Hide loading animation */
        document.getElementById("loadinglocation").style.display = "none";
        document.getElementById("loadinggraph").style.display = "none";
    }

    /* Change the current temperature value based on the value of slider. */
    function changeTemp() {
        loadTempData(document.getElementById("slider").value);
    }

    /* Return the temperature that is given hours away from now. */
    function loadTempData(futureHour) {
        var currentTempDiv = document.getElementById("currentTemp");
        clearChild(currentTempDiv);
        var index = futureHour / 3;
        if (index >= temperatureInFuture.length) {
            var p = createParagraph("Sorry, temperature at this time is not avaiable.");
        } else {
            var p = createParagraph(temperatureInFuture[futureHour / 3] + "&#8457;");
        }
        currentTempDiv.appendChild(p);
    }

    /* Fetch the forecast of given city. */
    function fetchForecast(city) {
        fetchInfo("mode=forecast&city=" + city, loadForecast);
    }

    /* Load the forecast from returned JSON data. */
    function loadForecast() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            var forecastTable = document.getElementById("forecast");
            var iconRow = document.createElement("TR");
            var tempRow = document.createElement("TR");
            forecastTable.appendChild(iconRow);
            forecastTable.appendChild(tempRow);

            for (var i = 0; i < data.list.length; i++) {
                var dayInfo = data.list[i];

                var iconSrc = "https://openweathermap.org/img/w/" + 
                              dayInfo.weather[0].icon + ".png";
                var icon = document.createElement("IMG");
                icon.src = iconSrc;
                var iconCell = document.createElement("TD");
                iconCell.appendChild(icon);
                iconRow.appendChild(iconCell);

                var temp = Math.round(dayInfo.temp.day);
                var tempArea = document.createElement("DIV");
                tempArea.innerHTML = temp + "&#176;";
                var tempCell = document.createElement("TD");
                tempCell.appendChild(tempArea);
                tempRow.appendChild(tempCell);
            }
        } else if (this.status != 410) {
            addError("ERROR: Fail to load forecast\tERRORCODE: " + this.status);
        }
        document.getElementById("loadingforecast").style.display = "none";
    }

    /* Create and return a p tag with given text in it. */
    function createParagraph(text) {
        var p = document.createElement("P");
        p.innerHTML = text;
        return p;
    }

    /* Clear all children of given DOM element. */
    function clearChild(element) {
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
    }

    /* Clear all the information on the page for a new search. */
    function clearAll() {
        clearChild(document.getElementById("location"));
        clearChild(document.getElementById("graph"));
        clearChild(document.getElementById("currentTemp"));
        clearChild(document.getElementById("forecast"));
        clearChild(document.getElementById("errors"));
        temperatureInFuture = [];
        document.getElementById("loadinglocation").style.display = "block";
        document.getElementById("loadinggraph").style.display = "block";
        document.getElementById("loadingforecast").style.display = "block";
        document.getElementById("nodata").style.display = "none";
        document.getElementById("buttons").style.display = "none";
        document.getElementById("temps").style.display = "none";
    }

    /* Handle Error information */
    function addError(text) {
        var errorP = createParagraph(text);
        document.getElementById("errors").appendChild(errorP);
    }
})();