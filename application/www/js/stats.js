'use strict';
/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
let test;


async function stats() {
    const data = await fetch(`http://odrodriguezc.sites.3wa.io/oscarblog/index.php/admin/stats`)
        .then(result => result.json())
        .then(json => graphicStats(analitycs(json)))
}

function analitycs(data) {
    console.log(data);
    let stats = {
        week: [],
        month: []
    }
    let week = Object.values(data.week);
    let totalWeek = 0;
    for (let i = 0; i < week.length; i++) {
        totalWeek += week[i];
    }
    
    if(totalWeek === 0){
        stats.week=[25,25,25,25];
    } else {
        week.forEach((element) => {
            stats.week.push((element * 100) / totalWeek)
        });
        
    }


    let month = Object.values(data.month);
    let totalMonth = 0;
    for (let i = 0; i < week.length; i++) {
        totalMonth += month[i];
    }

    if (totalMonth === 0){
        stats.month = [25,25,25,25];
    } else {
        month.forEach((element) => {
            stats.month.push((element * 100) / totalMonth)
        });
    }

    console.log(stats);

    return stats;

}


function graphicStats(data) {
    
  

    let canvas = document.getElementById('weekRing');
    let context = canvas.getContext('2d');

    let diametre = Math.min(canvas.height, canvas.width) - 100;
    let rayon = diametre / 2;

    // position du centre du camembert
    let position_x = rayon + 20;
    let position_y = canvas.height / 2 + 20;

    let angle_initial = 0;
    let stylecolors = ['rgb(70,63,58)', 'rgb(138,129,124)', 'rgb(188,184,177)', 'rgb(244,243,238)'];

    let largeur_rect = 15;
    let legendes = ['Created Posts', 'Published posts', 'Coments', 'Pics'];
    

    for (let i = 0; i < data.week.length; i++) {
        let angles_degre = (data.week[i] / 100) * 360; // conversion pourcentage -> angle en degré

        DessinerAngle(context, position_x, position_y, rayon, angle_initial, angles_degre, stylecolors[i]);
        angle_initial += angles_degre;

        DessinerRectangle(
            context,
            diametre + 25,
            (largeur_rect + 3) * (i + 1),
            largeur_rect,
            largeur_rect,
            stylecolors[i]
        );
        context.font = '9pt Tahoma'; //legendes
        context.fillStyle = '#000'; //legendes
        context.fillText(legendes[i], diametre + 55, 18 * i + 30); //legendes

    }

    // position du centre du camembert
    position_x = rayon + 180;
    position_y = canvas.height / 2 + 20;

    for (let i = 0; i < data.month.length; i++) {
        let angles_degre = (data.month[i] / 100) * 360; // conversion pourcentage -> angle en degré

        DessinerAngle(context, position_x, position_y, rayon, angle_initial, angles_degre, stylecolors[i]);
        angle_initial += angles_degre;

    }

}
// petit rectangle pour la légende
function DessinerRectangle(context, x0, y0, xl, yl, coloration) {
    context.beginPath();
    context.fillStyle = coloration;
    context.fillRect(x0, y0, xl, yl);
    context.closePath();
    context.fill();
}

function DessinerAngle(context, position_x, position_y, rayon, angle_initial, angles_degre, couleurs) {
    context.beginPath();
    context.fillStyle = couleurs;
    let angle_initial_radian = angle_initial / (180 / Math.PI); // conversion angle en degré -> angle en radian
    let angles_radian = angles_degre / (180 / Math.PI); // conversion angle en degré -> angle en radian
    context.arc(position_x, position_y, rayon, angle_initial_radian, angle_initial_radian + angles_radian, 0);
    context.lineTo(position_x, position_y);
    context.closePath();
    context.fill();
}




/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded', function () {

    stats()

});