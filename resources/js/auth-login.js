function toggleHulp(show) {
    document.getElementById('section-login').style.display = show ? 'none' : 'block';
    document.getElementById('section-hulp').style.display = show ? 'block' : 'none';
}

document.querySelectorAll('.form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.original = btn.textContent;
            btn.textContent = btn.textContent + '…';
        }
    });
});

window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        document.querySelectorAll('button[type="submit"]').forEach(function(btn) {
            btn.disabled = false;
            if (btn.dataset.original) btn.textContent = btn.dataset.original;
        });
    }
});

const showPassWordButton = document.getElementById('show-password')
let pathSvg = document.getElementById('path-svg')
const passwordInput = document.getElementById('password')
showPassWordButton.addEventListener('click', ()=>{



    if(passwordInput.type === "password"){
        passwordInput.type = "text"
        pathSvg.setAttribute('d', 'm634-422-48.67-48.67q20.34-63-27-108-47.33-45-107.66-26.66L402-654q17-10 36.83-14.67 19.84-4.66 41.17-4.66 72.33 0 122.83 50.5T653.33-500q0 21.33-5 41.5T634-422Zm128.67 128-46-45.33Q762-373 796.17-414.17q34.16-41.16 52.5-85.83-50-107.67-147.84-170.5-97.83-62.83-214.16-62.83-37.67 0-76.34 6.66Q371.67-720 346-710l-51.33-52q37-16.33 87.66-27.17Q433-800 483.33-800q145.67 0 264 82.17Q865.67-635.67 920-500q-25 62.33-64.83 114.5-39.84 52.17-92.5 91.5ZM808-61.33 640-226.67q-35 13-76.17 19.84Q522.67-200 480-200q-147.67 0-266.33-82.17Q95-364.33 40-500q20.33-52.33 54.67-100.5 34.33-48.17 82-90.17L56-812l46.67-47.33 750 750-44.67 48ZM222.67-644q-34.34 26.67-65.34 66.33-31 39.67-46.66 77.67 50.66 107.67 150.16 170.5t224.5 62.83q28.67 0 56.34-3.5 27.66-3.5 45-9.83L532-335.33q-11 4.33-25 6.5-14 2.16-27 2.16-71.67 0-122.5-50.16Q306.67-427 306.67-500q0-13.67 2.16-27 2.17-13.33 6.5-25l-92.66-92Zm309.66 125.67Zm-127.66 63.66Z')
    }else{
        passwordInput.type = "password"
        pathSvg.setAttribute('d', "M602.83-377.17q50.5-50.5 50.5-122.83t-50.5-122.83q-50.5-50.5-122.83-50.5t-122.83 50.5q-50.5 50.5-50.5 122.83t50.5 122.83q50.5 50.5 122.83 50.5t122.83-50.5ZM401.5-421.5q-32.17-32.17-32.17-78.5t32.17-78.5q32.17-32.17 78.5-32.17t78.5 32.17q32.17 32.17 32.17 78.5t-32.17 78.5q-32.17 32.17-78.5 32.17t-78.5-32.17Zm-186.17 139Q96.67-365 40-500q56.67-135 175.33-217.5Q334-800 480-800t264.67 82.5Q863.33-635 920-500q-56.67 135-175.33 217.5Q626-200 480-200t-264.67-82.5ZM480-500Zm217.5 169.83q99.17-63.5 151.17-169.83-52-106.33-151.17-169.83-99.17-63.5-217.5-63.5t-217.5 63.5Q163.33-606.33 110.67-500q52.66 106.33 151.83 169.83 99.17 63.5 217.5 63.5t217.5-63.5Z")
    }


})

passwordInput.addEventListener('click', () =>{
    const textError = document.getElementById('check-input-password')

    passwordInput.addEventListener('blur', () => {
        if (passwordInput.value.trim() === '') {
            passwordInput.style.borderColor = 'red';
            textError.style.display = 'block';
        } else {
            passwordInput.style.borderColor = 'black';
            textError.style.display = 'none';
        }
    });

})

const emailInput = document.getElementById('email')

emailInput.addEventListener('click', () =>{
    const textError = document.getElementById('check-input-email')

    emailInput.addEventListener('blur', () => {
        if (emailInput.value.trim() === '') {
            emailInput.style.borderColor = 'red';
            textError.style.display = 'block';
        } else {
            emailInput.style.borderColor = 'black';
            textError.style.display = 'none';
        }
    });

})

