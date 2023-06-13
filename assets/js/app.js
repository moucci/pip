//add event listener to input password
document.querySelector('.pass span')
    .addEventListener('click', function () {
        let typ = this.closest('.pass').querySelector('input').getAttribute('type')
        if (typ === 'text') {
            this.closest('.pass').querySelector('input').setAttribute('type', 'password')
        } else {
            this.closest('.pass').querySelector('input').setAttribute('type', 'text')
        }
    })