'use strict';
const KEY = '0b1c6c630cfd8d8666651db8f91f83f3';
const ipstackKEY = '71ccee7ccd9bd958b41523912d5902ec';
const prefix =  `wi wi-`

const weatherIcons = {
    "200": {
      "label": "thunderstorm with light rain",
      "icon": "storm-showers"
    },
  
    "201": {
      "label": "thunderstorm with rain",
      "icon": "storm-showers"
    },
  
    "202": {
      "label": "thunderstorm with heavy rain",
      "icon": "storm-showers"
    },
  
    "210": {
      "label": "light thunderstorm",
      "icon": "storm-showers"
    },
  
    "211": {
      "label": "thunderstorm",
      "icon": "thunderstorm"
    },
  
    "212": {
      "label": "heavy thunderstorm",
      "icon": "thunderstorm"
    },
  
    "221": {
      "label": "ragged thunderstorm",
      "icon": "thunderstorm"
    },
  
    "230": {
      "label": "thunderstorm with light drizzle",
      "icon": "storm-showers"
    },
  
    "231": {
      "label": "thunderstorm with drizzle",
      "icon": "storm-showers"
    },
  
    "232": {
      "label": "thunderstorm with heavy drizzle",
      "icon": "storm-showers"
    },
  
    "300": {
      "label": "light intensity drizzle",
      "icon": "sprinkle"
    },
  
    "301": {
      "label": "drizzle",
      "icon": "sprinkle"
    },
  
    "302": {
      "label": "heavy intensity drizzle",
      "icon": "sprinkle"
    },
  
    "310": {
      "label": "light intensity drizzle rain",
      "icon": "sprinkle"
    },
  
    "311": {
      "label": "drizzle rain",
      "icon": "sprinkle"
    },
  
    "312": {
      "label": "heavy intensity drizzle rain",
      "icon": "sprinkle"
    },
  
    "313": {
      "label": "shower rain and drizzle",
      "icon": "sprinkle"
    },
  
    "314": {
      "label": "heavy shower rain and drizzle",
      "icon": "sprinkle"
    },
  
    "321": {
      "label": "shower drizzle",
      "icon": "sprinkle"
    },
  
    "500": {
      "label": "light rain",
      "icon": "rain"
    },
  
    "501": {
      "label": "moderate rain",
      "icon": "rain"
    },
  
    "502": {
      "label": "heavy intensity rain",
      "icon": "rain"
    },
  
    "503": {
      "label": "very heavy rain",
      "icon": "rain"
    },
  
    "504": {
      "label": "extreme rain",
      "icon": "rain"
    },
  
    "511": {
      "label": "freezing rain",
      "icon": "rain-mix"
    },
  
    "520": {
      "label": "light intensity shower rain",
      "icon": "showers"
    },
  
    "521": {
      "label": "shower rain",
      "icon": "showers"
    },
  
    "522": {
      "label": "heavy intensity shower rain",
      "icon": "showers"
    },
  
    "531": {
      "label": "ragged shower rain",
      "icon": "showers"
    },
  
    "600": {
      "label": "light snow",
      "icon": "snow"
    },
  
    "601": {
      "label": "snow",
      "icon": "snow"
    },
  
    "602": {
      "label": "heavy snow",
      "icon": "snow"
    },
  
    "611": {
      "label": "sleet",
      "icon": "sleet"
    },
  
    "612": {
      "label": "shower sleet",
      "icon": "sleet"
    },
  
    "615": {
      "label": "light rain and snow",
      "icon": "rain-mix"
    },
  
    "616": {
      "label": "rain and snow",
      "icon": "rain-mix"
    },
  
    "620": {
      "label": "light shower snow",
      "icon": "rain-mix"
    },
  
    "621": {
      "label": "shower snow",
      "icon": "rain-mix"
    },
  
    "622": {
      "label": "heavy shower snow",
      "icon": "rain-mix"
    },
  
    "701": {
      "label": "mist",
      "icon": "sprinkle"
    },
  
    "711": {
      "label": "smoke",
      "icon": "smoke"
    },
  
    "721": {
      "label": "haze",
      "icon": "day-haze"
    },
  
    "731": {
      "label": "sand, dust whirls",
      "icon": "cloudy-gusts"
    },
  
    "741": {
      "label": "fog",
      "icon": "fog"
    },
  
    "751": {
      "label": "sand",
      "icon": "cloudy-gusts"
    },
  
    "761": {
      "label": "dust",
      "icon": "dust"
    },
  
    "762": {
      "label": "volcanic ash",
      "icon": "smog"
    },
  
    "771": {
      "label": "squalls",
      "icon": "day-windy"
    },
  
    "781": {
      "label": "tornado",
      "icon": "tornado"
    },
  
    "800": {
      "label": "clear sky",
      "icon": "sunny"
    },
  
    "801": {
      "label": "few clouds",
      "icon": "cloudy"
    },
  
    "802": {
      "label": "scattered clouds",
      "icon": "cloudy"
    },
  
    "803": {
      "label": "broken clouds",
      "icon": "cloudy"
    },
  
    "804": {
      "label": "overcast clouds",
      "icon": "cloudy"
    },
  
  
    "900": {
      "label": "tornado",
      "icon": "tornado"
    },
  
    "901": {
      "label": "tropical storm",
      "icon": "hurricane"
    },
  
    "902": {
      "label": "hurricane",
      "icon": "hurricane"
    },
  
    "903": {
      "label": "cold",
      "icon": "snowflake-cold"
    },
  
    "904": {
      "label": "hot",
      "icon": "hot"
    },
  
    "905": {
      "label": "windy",
      "icon": "windy"
    },
  
    "906": {
      "label": "hail",
      "icon": "hail"
    },
  
    "951": {
      "label": "calm",
      "icon": "sunny"
    },
  
    "952": {
      "label": "light breeze",
      "icon": "cloudy-gusts"
    },
  
    "953": {
      "label": "gentle breeze",
      "icon": "cloudy-gusts"
    },
  
    "954": {
      "label": "moderate breeze",
      "icon": "cloudy-gusts"
    },
  
    "955": {
      "label": "fresh breeze",
      "icon": "cloudy-gusts"
    },
  
    "956": {
      "label": "strong breeze",
      "icon": "cloudy-gusts"
    },
  
    "957": {
      "label": "high wind, near gale",
      "icon": "cloudy-gusts"
    },
  
    "958": {
      "label": "gale",
      "icon": "cloudy-gusts"
    },
  
    "959": {
      "label": "severe gale",
      "icon": "cloudy-gusts"
    },
  
    "960": {
      "label": "storm",
      "icon": "thunderstorm"
    },
  
    "961": {
      "label": "violent storm",
      "icon": "thunderstorm"
    },
  
    "962": {
      "label": "hurricane",
      "icon": "cloudy-gusts"
    }
  }
/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
async function weather(ip = true) 
{
    let city;
    if (ip){

        const ip = await fetch('https://api.ipify.org?format=json')
            .then(result => result.json())
            .then(json => json.ip)
        
        city = await fetch(`http://ip-api.com/json/${ip}`)
            .then(result => result.json())
            .then(json => json.city) 
    } else{
        city = document.querySelector('#city').textContent;
    }
    const localWeather = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${KEY}&units=metric&lang=fr`)
        .then(result => result.json())
        .then(json => json)

        console.log(localWeather);
        displayWeather(localWeather);
}

function displayWeather(data) 
{
    const name = data.name;
    const temperature = data.main.temp;
    const conditions = data.weather[0].main;
    const description = data.weather[0].description;
    const code = data.weather[0].id;
    
    let icon = weatherIcons[code].icon;

    // If we are not in the ranges mentioned above, add a day/night prefix.
    if (!(code > 699 && code < 800) && !(code > 899 && code < 1000)) {
        icon = 'day-' + icon;
    }

    // Finally tack on the prefix.
    icon = prefix + icon;

    document.querySelector('#city').textContent = name;
    document.querySelector('#temperature').textContent = Math.round(temperature);
    document.querySelector('#conditions').textContent = description;
    document.querySelector('#weatherIcon').className = icon

    document.querySelector('.weather').className = conditions.toLowerCase();

}

/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){    
    
    const city = document.querySelector('#city');
    
    city.addEventListener('click', () =>{
        city.contentEditable = true;
    });

    city.addEventListener('keydown', (e) => {
        if (e.keyCode === 13 ){
            e.preventDefault();
            city.contentEditable = false;
            weather(false);
        }
    });
    
    weather();


    
});