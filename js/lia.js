'use strict';
let lia = document.querySelector('.lia');
function res(e) {

  e.preventDefault();

  let form = new FormData(document.getElementById('ques'));

  fetch(`res.php`, { method: 'POST', body: form })
    .then(
      response => {
        return response.json();
      }
    )
    .then(data => {
      if (data.txt) {
        // console.log(data);
        document.querySelector('.bulle').innerHTML = data.txt;
        document.getElementById('vlia').src = './img/' + data.img;
        document.getElementById('res').value = data.ques;
        const textLength = data.txt.length;
        const maxWindowHeight = window.innerHeight;
        const maxWindowWidth = window.innerWidth;
        const height = parseInt(textLength);
        const width = parseInt(textLength);
        lia.style = `overflow: auto;`;

        if (height > maxWindowHeight) {
          // lia.style = `overflow: auto;`;
          lia.style.height = `90%`;
          lia.style.minWidth = `40%`;
          lia.style.width = `length`;

        } else if (width > maxWindowWidth) {
          //lia.style = `overflow: auto;`;
          lia.style.minHeight = `40%`;
          lia.style.height = `length`;
          lia.style.width = `90%`;

        } else {

          if (height > 50) {
            lia.style.minHeight = `40%`;
            lia.style.height = `length`;

          } else {
            lia.style.height = `length`;
            // lia.style.height = `80%`;
          }
          if (width > 50) {
            lia.style.minWidth = `40%`;
            lia.style.width = `length`;

          } else {
            lia.style.width = `length`;
            // lia.style.width = `80%`;
          }

        }
      } else {
        document.querySelector('.bulle').innerHTML = '???';
        document.getElementById('vlia').src = './img/neutre.png';
        document.getElementById('res').value =data.ques;
        lia.style.height = `auto`;
        lia.style.width = `15%`;
      }

    })
    .catch(error => { 
      lia.style.height = `auto`;
      lia.style.width = `15%`;
      document.querySelector('.bulle').innerHTML ="erreure";
  })
    ;
}

document.addEventListener('DOMContentLoaded', function () {

  if (document.getElementById('ria')) {
    document.getElementById('ria').addEventListener('click', res);
  }


  if (document.getElementById('vlia')) {
    setTimeout(function () { document.getElementById('vlia').src = './img/neutre.png'; }, 10000);
    setTimeout(function () {
      document.getElementById('vlia').src = './img/dort.png';
      document.querySelector('.bulle').innerHTML = "zzzzzz";
    }, 50000);
    document.getElementById('res').addEventListener('touchstart', function () { document.getElementById('vlia').src = './img/interrogation.png'; });
    document.getElementById('res').addEventListener('keydown', function () { document.getElementById('vlia').src = './img/interrogation.png'; document.querySelector('.bulle').innerHTML = "????"; });
  }

});