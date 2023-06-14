//add event listener to input password
let $btnTogglePass = document.querySelector('.pass span');
if ($btnTogglePass) {
    $btnTogglePass.addEventListener('click', function () {
        let typ = this.closest('.pass').querySelector('input').getAttribute('type')
        if (typ === 'text') {
            this.closest('.pass').querySelector('input').setAttribute('type', 'password')
        } else {
            this.closest('.pass').querySelector('input').setAttribute('type', 'text')
        }
    })
}


document.querySelectorAll('.link_delete').forEach($el => {
    $el.addEventListener('click', function (event) {
        event.preventDefault();
        if (confirm('êtes vous sur de vouloir supprimer ')) {
            window.location.href = $el.getAttribute('href');
        }
    })
})
