'use strict';

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
      } else {
        document.querySelector('.bulle').innerHTML = '???';
        document.getElementById('vlia').src = './img/neutre.png';
        document.getElementById('res').value =data.ques;
      }

    })
    .catch(error => { document.querySelector('.bulle').innerHTML ="erreure";})
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